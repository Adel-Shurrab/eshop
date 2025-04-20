<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CartModel;
use Exception;

class AjaxCartController extends Controller
{
    private CartModel $cartModel;

    public function __construct()
    {
        $this->cartModel = $this->model('Cart');
    }

    public function index()
    {
        try {
            $data = file_get_contents("php://input");
            $data = json_decode($data);

            if (!is_object($data)) {
                $this->sendErrorResponse('Invalid data format.', 400);
                return;
            }

            switch ($data->dataType) {
                case 'add_to_cart':
                    $this->addToCart($data);
                    break;
                case 'delete_from_cart':
                    $this->deleteFromCart($data);
                    break;
                case 'clear_cart':
                    $this->clearCart();
                    break;
                case 'increase_quantity':
                    $this->increaseQuantity($data);
                    break;
                case 'decrease_quantity':
                    $this->decreaseQuantity($data);
                    break;
                case 'edit_quantity':
                    $this->editQuantity($data);
                    break;
                case 'get_cart_products':
                    $this->getCartProducts();
                    break;
                case 'get_total':
                    $this->getTotal();
                    break;
                default:
                    $this->sendErrorResponse('Invalid action.', 400);
                    return;
            }
        } catch (Exception $e) {
            error_log("Error in AjaxCart2Controller index: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    public function addToCart($data)
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid product ID. Please provide a valid product ID.', 400);
                return;
            }

            $quantity = isset($data->quantity) && is_numeric($data->quantity) && $data->quantity > 0 ? $data->quantity : 1;
            $result = $this->cartModel->addToCart($data->id, $quantity);
            if ($result) {
                error_log("Product added to cart successfully: " . $data->id);
                $this->displayProducts($result);
            } else {
                error_log("Failed to add product to cart: " . $data->id);
                $this->sendErrorResponse($this->cartModel->msg, 500);
            }
        } catch (Exception $e) {
            error_log("Error in addToCart: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    public function deleteFromCart($data)
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid product ID. Please provide a valid product ID.', 400);
                return;
            }

            $total = $this->cartModel->deleteFromCart($data->id);
            $this->displayProducts($total);
        } catch (Exception $e) {
            error_log("Error in deleteFromCart: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    public function clearCart()
    {
        try {
            $total = $this->cartModel->clearCart();
            $this->displayProducts($total);
        } catch (Exception $e) {
            error_log("Error in clearCart: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    public function increaseQuantity($data)
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid product ID. Please provide a valid product ID.', 400);
                return;
            }

            $cartQuantity = $this->cartModel->getCartItemQuantity($data->id);
            if ($cartQuantity >= $data->maxQuantity) {
                $this->sendErrorResponse('Product out of stock!', 400);
                echo json_encode(['message' => 'Product out of stock!']);
                return;
            }

            $total = $this->cartModel->increaseQuantity($data->id);
            $this->displayProducts($total);
        } catch (Exception $e) {
            error_log("Error in increaseQuantity: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    public function decreaseQuantity($data)
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid product ID. Please provide a valid product ID.', 400);
                return;
            }

            $total = $this->cartModel->decreaseQuantity($data->id);
            $this->displayProducts($total);
        } catch (Exception $e) {
            error_log("Error in decreaseQuantity: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    public function editQuantity($data)
    {
        try {
            if (!$this->isValidId($data->id) || !is_numeric($data->quantity) || $data->quantity < 1) {
                $this->sendErrorResponse('Invalid input. Please provide a valid product ID and quantity.', 400);
                return;
            }

            $maxQty = $this->cartModel->getProductStock($data->id);
            if ($data->quantity <= $maxQty) {
                $total = $this->cartModel->editQuantity($data->id, $data->quantity);
                $this->displayProducts($total);
            } else {
                echo json_encode(['maxQty' => true]);
            }
        } catch (Exception $e) {
            error_log("Error in editQuantity: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    public function getCartProducts()
    {
        try {
            $cartProducts = $this->cartModel->getCartItems();
            $cart = $this->cartModel->displayCart($cartProducts);
            echo json_encode([
                'cart' => $cart,
                'isEmpty' => empty($cartProducts)
            ]);
        } catch (Exception $e) {
            error_log("Error in getCartProducts: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    public function getTotal()
    {
        try {
            $total = $this->cartModel->getTotal();
            echo json_encode(['total' => $total]);
        } catch (Exception $e) {
            error_log("Error in getTotal: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    private function displayProducts($total = null): void
    {
        try {
            $cartProducts = $this->cartModel->getCartItems();
            $cart = $this->cartModel->displayCart($cartProducts);
            echo json_encode([
                'cart' => $cart,
                'isEmpty' => empty($cartProducts),
                'total' => $total
            ]);
        } catch (Exception $e) {
            error_log("Error in displayProducts: " . $e->getMessage());
            $this->sendErrorResponse('Internal Server Error', 500);
        }
    }

    private function isValidId($id): bool
    {
        return isset($id) && is_numeric($id);
    }

    private function sendErrorResponse(string $message, int $statusCode): void
    {
        http_response_code($statusCode);
        echo json_encode(['message' => $message]);
    }
}
