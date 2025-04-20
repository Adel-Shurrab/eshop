<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\CartModel;
use App\Models\CountriesModel;

class CheckOutController extends Controller
{
    private CountriesModel $countriesModel;
    private CartModel $cartModel;

    public function __construct()
    {
        $this->countriesModel = $this->model('Countries');
        $this->cartModel = $this->model('Cart');
    }

    public function index()
    {
        $db = Database::getInstance();
        $userModel = $this->model('User');
        $userData = $userModel->checkLogin(false, ['customer', 'admin']);

        $data['countries'] = $this->countriesModel->getCountries();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $this->sanitizePostData();

            if (!empty($postData)) {
                // Store the sanitized POST data in the session
                $_SESSION['checkout_data'] = $postData;

                header('Location: ' . BASE_URL . 'checkout/summary');
                exit;
            } else {
                $data['error'] = 'Invalid form submission.';
            }
        }

        $data['cart'] = $this->cartModel->getCartItems() ?? [];
        $data['page_title'] = 'Check Out';
        $this->view('/checkout', $data);
    }

    public function summary()
    {
        $db = Database::getInstance();
        $userModel = $this->model('User');
        $userData = $userModel->checkLogin(false, ['customer', 'admin']);

        $data['user_data'] = is_array($userData) ? $userData : null;

        $ROWS = $this->getCartItems($db);

        if (!empty($ROWS)) {
            $data['cart'] = $ROWS;
            $data['total'] = array_reduce($ROWS, function ($sum, $item) {
                return $sum + ($item['price'] * $item['cart_qty']);
            }, 0);
        } else {
            $data['cart'] = [];
            $data['total'] = 0;
        }

        // Check if the user confirmed the order
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checkoutModel = $this->model('Checkout');
            $postData = $_SESSION['checkout_data'] ?? [];

            if (!empty($postData) && !empty($ROWS)) {
                if ($checkoutModel->saveCheckout($postData, $ROWS, $_SESSION['user_url'] ?? '', session_id())) {
                    $this->clearCart();
                    header('Location: ' . BASE_URL . 'checkout/complete');
                    exit;
                } else {
                    $data['error'] = implode('<br>', $checkoutModel->errors);
                }
            }
        }

        $data['page_title'] = 'Order Summary';
        $this->view('/checkout_summary', $data);
    }

    public function complete()
    {
        // Clear the session data for checkout
        unset($_SESSION['checkout_data']);

        $this->clearCart();
        $data['page_title'] = 'Order Complete';
        $data['message'] = 'Thank you for your order! Your order has been successfully placed.';
        $this->view('/checkout_complete', $data);
    }

    private function getCartItems(Database $db): array
    {
        $ROWS = [];

        if (!empty($_SESSION['user_url'])) {
            // For logged-in users, fetch cart items from the database
            $cartItems = $this->cartModel->getCartItems();
            if (!empty($cartItems)) {
                $productIds = array_column($cartItems, 'product_id');
                $idsStr = implode(',', array_map('intval', $productIds));
                $ROWS = $db->read("SELECT * FROM products WHERE id IN ($idsStr)");

                if (is_array($ROWS)) {
                    foreach ($ROWS as $key => $row) {
                        foreach ($cartItems as $item) {
                            if ($row['id'] == $item['product_id']) {
                                $ROWS[$key] = (array) $row;
                                $ROWS[$key]['cart_qty'] = $item['quantity'];
                                break;
                            }
                        }
                    }
                }
            }
        } elseif (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            // For guest users, fetch cart items from the session
            $prodIds = array_column($_SESSION['cart'], 'id');
            if (!empty($prodIds)) {
                $idsStr = implode(',', array_map('intval', $prodIds));
                $ROWS = $db->read("SELECT * FROM products WHERE id IN ($idsStr)");

                if (is_array($ROWS)) {
                    foreach ($ROWS as $key => $row) {
                        foreach ($_SESSION['cart'] as $item) {
                            if ($row['id'] == $item['id']) {
                                $ROWS[$key] = (array) $row;
                                $ROWS[$key]['cart_qty'] = $item['quantity'];
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $ROWS;
    }

    private function sanitizePostData(): array
    {
        return filter_input_array(INPUT_POST, [
            'address_1' => FILTER_SANITIZE_STRING,
            'address_2' => FILTER_SANITIZE_STRING,
            'zip'       => FILTER_SANITIZE_STRING,
            'countries' => FILTER_SANITIZE_NUMBER_INT,
            'states'    => FILTER_SANITIZE_NUMBER_INT,
            'phone'     => FILTER_SANITIZE_STRING,
            'message'   => FILTER_SANITIZE_STRING,
        ]) ?? [];
    }

    private function clearCart(): void
    {
        unset($_SESSION['cart']);
        $this->cartModel->clearCart();
    }
}
