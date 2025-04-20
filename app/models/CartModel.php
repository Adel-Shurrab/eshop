<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use Exception;
use App\Models\ProductModel;

class CartModel
{
    public $msg = '';
    private const ERROR_QUERY_FAILURE = 'Something went wrong. Please try again later.';
    private const ERROR_NO_PRODUCT = 'No product found.';

    public function addToCart(int $id, int $quantity = 1): bool
    {
        $db = Database::getInstance();

        if (!$this->productExists($id)) {
            $this->setErrorMessage(self::ERROR_NO_PRODUCT);
            return false;
        }

        if (!isset($_SESSION['user_url'])) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$id])) {
                if (!$this->canIncreaseQuantity($id, $quantity)) {
                    $this->setErrorMessage('Product out of stock!');
                    return false;
                }
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $id,
                    'quantity' => $quantity,
                    'price_snapshot' => $this->getProductPrice($id)
                ];
            }
        } else {
            $cartItem = $this->getCartItem($id);
        }

        $db->beginTransaction();

        try {
            if ($cartItem) {
                if (!$this->canIncreaseQuantity($id, $quantity)) {
                    $this->setErrorMessage('Product out of stock!');
                    return false;
                }
                $this->updateCartItemQuantity($cartItem['id'], $cartItem['quantity'] + $quantity);
            } else {
                $this->insertNewCartItem($id, $quantity);
            }

            $this->updateCartTimestamp();
            $db->commit();
            $this->setSuccessMessage('Added successfully!');
            $this->getTotal();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("General error: " . $e->getMessage());
            $this->setErrorMessage(self::ERROR_QUERY_FAILURE);
            return false;
        }
    }

    private function productExists(int $productId): bool
    {
        $db = Database::getInstance();
        $query = "SELECT COUNT(*) as count FROM products WHERE id = :id";
        $result = $db->read($query, ['id' => $productId]);
        return $result && $result[0]['count'] > 0;
    }

    public function increaseQuantity(int $id): string
    {
        if (!$this->canIncreaseQuantity($id, 1)) {
            $this->setErrorMessage('Product out of stock!');
            return $this->getTotal();
        }

        if (!isset($_SESSION['user_url'])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $db = Database::getInstance();
            $query = "UPDATE cart_items SET quantity = quantity + 1 WHERE product_id = :product_id AND cart_id = (SELECT id FROM carts WHERE user_url = :user_url)";
            $db->write($query, ['product_id' => $id, 'user_url' => $_SESSION['user_url']]);
            $this->updateCartTimestamp();
        }
        return $this->getTotal();
    }

    private function canIncreaseQuantity(int $productId, int $quantity): bool
    {
        $maxQuantity = $this->getProductStock($productId);
        $cartQuantity = $this->getCartItemQuantity($productId);
        return $cartQuantity + $quantity <= $maxQuantity;
    }

    public function decreaseQuantity(int $id): string
    {
        if (!isset($_SESSION['user_url'])) {
            if ($_SESSION['cart'][$id]['quantity'] > 1) {
                $_SESSION['cart'][$id]['quantity']--;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        } else {
            $db = Database::getInstance();
            $query = "UPDATE cart_items SET quantity = quantity - 1 WHERE product_id = :product_id AND cart_id = (SELECT id FROM carts WHERE user_url = :user_url) AND quantity > 1";
            $db->write($query, ['product_id' => $id, 'user_url' => $_SESSION['user_url']]);
            $this->updateCartTimestamp();
        }
        return $this->getTotal();
    }

    private function getCartItem(int $productId): ?array
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM cart_items WHERE product_id = :product_id AND cart_id = (SELECT id FROM carts WHERE user_url = :user_url)";
        $result = $db->read($query, ['product_id' => $productId, 'user_url' => $_SESSION['user_url']]);
        return $result ? $result[0] : null;
    }

    public function getCartItems(): array
    {
        if (!isset($_SESSION['user_url'])) {
            return $_SESSION['cart'] ?? [];
        } else {
            $db = Database::getInstance();
            $query = "SELECT * FROM cart_items WHERE cart_id = (SELECT id FROM carts WHERE user_url = :user_url)";
            $result = $db->read($query, ['user_url' => $_SESSION['user_url']]);
            return $result ?: [];
        }
    }

    public function editQuantity(int $id, int $quantity): string
    {
        if (!isset($_SESSION['user_url'])) {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        } else {
            $db = Database::getInstance();
            $query = "UPDATE cart_items SET quantity = :quantity WHERE product_id = :product_id AND cart_id = (SELECT id FROM carts WHERE user_url = :user_url)";
            $db->write($query, ['quantity' => $quantity, 'product_id' => $id, 'user_url' => $_SESSION['user_url']]);
            $this->updateCartTimestamp();
        }
        return $this->getTotal();
    }

    public function deleteFromCart(int $id): string
    {
        if (!isset($_SESSION['user_url'])) {
            unset($_SESSION['cart'][$id]);
            $this->setSuccessMessage('Deleted successfully!');
        } else {
            $db = Database::getInstance();
            $query = "DELETE FROM cart_items WHERE product_id = :product_id AND cart_id = (SELECT id FROM carts WHERE user_url = :user_url)";
            $db->write($query, ['product_id' => $id, 'user_url' => $_SESSION['user_url']]);
            $this->updateCartTimestamp();
            $this->setSuccessMessage('Deleted successfully!');
        }
        return $this->getTotal();
    }

    public function clearCart(): string
    {
        if (!isset($_SESSION['user_url'])) {
            unset($_SESSION['cart']);
            $this->setSuccessMessage('Cart cleared successfully!');
        } else {
            $db = Database::getInstance();
            $query = "DELETE FROM cart_items WHERE cart_id = (SELECT id FROM carts WHERE user_url = :user_url)";
            $db->write($query, ['user_url' => $_SESSION['user_url']]);
            $this->updateCartTimestamp();
            $this->setSuccessMessage('Cart cleared successfully!');
        }
        return $this->getTotal();
    }

    public function getProductStock(int $id): int
    {
        $db = Database::getInstance();
        $query = "SELECT quantity FROM products WHERE id = :id AND is_deleted = 0";
        $result = $db->read($query, ['id' => $id]);
        return $result ? (int)$result[0]['quantity'] : 0;
    }

    public function getCartItemQuantity(int $id): int
    {
        if (!isset($_SESSION['user_url'])) {
            return $_SESSION['cart'][$id]['quantity'] ?? 0;
        } else {
            $db = Database::getInstance();
            $query = "SELECT quantity FROM cart_items WHERE product_id = :product_id AND cart_id = (SELECT id FROM carts WHERE user_url = :user_url)";
            $result = $db->read($query, ['product_id' => $id, 'user_url' => $_SESSION['user_url']]);
            return $result ? (int)$result[0]['quantity'] : 0;
        }
    }

    private function setErrorMessage(string $message): void
    {
        $this->msg = $message;
        setSessionMessage('danger', $this->msg);
    }

    private function setSuccessMessage(string $message): void
    {
        $this->msg = $message;
        setSessionMessage('success', $this->msg);
    }

    private function updateCartItemQuantity(int $cartItemId, int $quantity): void
    {
        $db = Database::getInstance();
        $query = "UPDATE cart_items SET quantity = :quantity WHERE id = :id";
        $db->write($query, ['quantity' => $quantity, 'id' => $cartItemId]);
    }

    private function insertNewCartItem(int $productId, int $quantity): void
    {
        $db = Database::getInstance();
        $saveCartQuery = "INSERT INTO carts (user_url, created_at) VALUES (:user_url, NOW())";
        $saveCartItemQuery = "INSERT INTO cart_items (cart_id, product_id, quantity, price_snapshot) VALUES (:cart_id, :product_id, :quantity, :price_snapshot)";

        $cartId = $this->getCartIdByUserUrl();
        if (!$cartId) {
            $db->write($saveCartQuery, ['user_url' => $_SESSION['user_url']]);
            $cartId = $db->lastInsertId();
        }

        $productPrice = $this->getProductPrice($productId);

        $db->write($saveCartItemQuery, [
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price_snapshot' => $productPrice
        ]);
    }

    private function getProductPrice(int $id): float
    {
        $db = Database::getInstance();
        $query = "SELECT price FROM products WHERE id = :id";
        $result = $db->read($query, ['id' => $id]);
        return $result ? (float)$result[0]['price'] : 0.0;
    }

    private function getCartIdByUserUrl(): ?int
    {
        $db = Database::getInstance();
        $query = "SELECT id FROM carts WHERE user_url = :user_url";
        $result = $db->read($query, ['user_url' => $_SESSION['user_url']]);
        return $result ? (int)$result[0]['id'] : null;
    }

    public function getSubTotal(int $id): float
    {
        if (!isset($_SESSION['user_url'])) {
            $cartProduct = $_SESSION['cart'][$id];
        } else {
            $cartProduct = $this->getCartItem($id);
        }
        return $cartProduct['price_snapshot'] * $cartProduct['quantity'];
    }

    public function getTotal(): string
    {
        $total = 0.0;

        if (!isset($_SESSION['user_url'])) {
            $cartProducts = $_SESSION['cart'] ?? [];
            foreach ($cartProducts as $product) {
                $total += $this->getSubTotal($product['id']);
            }
        } else {
            $cartItems = $this->getCartItems();
            foreach ($cartItems as $item) {
                $total += $this->getSubTotal($item['product_id']);
            }
        }

        return number_format($total, 2);
    }

    private function updateCartTimestamp(): void
    {
        $db = Database::getInstance();
        $query = "UPDATE carts SET updated_at = NOW() WHERE user_url = :user_url";
        $db->write($query, ['user_url' => $_SESSION['user_url']]);
    }

    public function displayCart(array $cartProducts): string
    {
        $html = '';
        $productModel = new ProductModel();

        foreach ($cartProducts as $row) {
            $productId = isset($_SESSION['user_url']) ? $row['product_id'] : $row['id'];
            $product = $productModel->getOneProduct($productId);
            $quantity = $row['quantity'];
            $priceSnapshot = $row['price_snapshot'];
            $totalPrice = number_format($priceSnapshot * $quantity, 2);

            $html .= '<tr>';
            $html .= '<td class="cart_product">';
            $html .= '<a href="' . BASE_URL . 'productDetails/' . $product['slag'] . '"><img src="' . UPLOADS_URL . $product['image'] . '" style="width: 95px;height: 95px;"></a>';
            $html .= '</td>';
            $html .= '<td class="cart_description">';
            $html .= '<a href="' . BASE_URL . 'productDetails/' . $product['slag'] . '"><h5>' . $product['description'] . '</h5></a>';
            $html .= '<p>ID:' . $product['id'] . '</p>';
            $html .= '</td>';
            $html .= '<td class="cart_price">';
            $html .= '<p>$' . number_format($priceSnapshot, 2) . '</p>';
            $html .= '</td>';
            $html .= '<td class="cart_quantity">';
            $html .= '<div class="cart_quantity_button">';
            $html .= '<button class="cart_quantity_up" onclick="increaseQuantity(' . $product['id'] . ',' . $product['quantity'] . ',' . $quantity . ')"> <i class="fa fa-plus" aria-hidden="true"></i> </button>';
            $html .= '<input class="cart_quantity_input" type="text" oninput="editQuantity(this.value, ' . $product['id'] . ')" name="quantity" value="' . $quantity . '" autocomplete="off" size="2">';
            $html .= '<button class="cart_quantity_down" onclick="decreaseQuantity(' . $product['id'] . ')"> <i class="fa fa-minus" aria-hidden="true"></i> </button>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '<td class="cart_total">';
            $html .= '<p class="cart_total_price">$' . $totalPrice . '</p>';
            $html .= '</td>';
            $html .= '<td class="cart_delete">';
            $html .= '<button class="cart_quantity_delete" onclick="deleteItem(' . $product['id'] . ')"><i class="fa fa-times"></i></button>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
}
