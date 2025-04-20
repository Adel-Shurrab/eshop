<?php

namespace App\Models;

use App\Core\Database;
use Exception;

class CheckoutModel
{
    public function getAllOrders()
    {
        $db = Database::getInstance();
        $query = 'SELECT o.*, COALESCE(u.name, "Guest") AS user_name FROM orders o LEFT JOIN users u ON o.user_url = u.url_address ORDER BY o.id DESC';
        return $db->read($query);
    }

    public function getOrderItems(int $orderId): array
    {
        $db = Database::getInstance();
        $query = 'SELECT oi.*, p.description AS product_description, p.slag AS slag FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id';
        return $db->read($query, [':order_id' => $orderId]);
    }

    public function searchOrders(string $search, string $status = '', string $payment = ''): array|false
    {
        $db = Database::getInstance();
        $search = '%' . trim($search) . '%';
        $params = ['search' => $search];

        $query = 'SELECT o.*, COALESCE(u.name, "Guest") AS user_name FROM orders o LEFT JOIN users u ON o.user_url = u.url_address WHERE (o.id LIKE :search OR COALESCE(u.name, "Guest") LIKE :search OR o.phone LIKE :search)';

        if (!empty($status)) {
            $query .= ' AND o.status = :status';
            $params['status'] = $status;
        }

        if (!empty($payment)) {
            $query .= ' AND o.payment_status = :payment';
            $params['payment'] = $payment;
        }

        $query .= ' ORDER BY o.id DESC';
        return $db->read($query, $params);
    }

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $db = Database::getInstance();
        $query = 'UPDATE orders SET status = :status WHERE id = :id LIMIT 1';
        return $db->write($query, [':status' => $status, ':id' => $orderId]);
    }

    public function updatePaymentStatus(int $orderId, string $status): bool
    {
        $db = Database::getInstance();
        $query = 'UPDATE orders SET payment_status = :status WHERE id = :id LIMIT 1';
        return $db->write($query, [':status' => $status, ':id' => $orderId]);
    }

    public function countAllOrders(): int
    {
        $db = Database::getInstance();
        $query = 'SELECT COUNT(*) as count FROM orders';
        $result = $db->read($query);
        return (int)($result[0]['count'] ?? 0);
    }

    public function countOrdersByStatus(string $status): int
    {
        $db = Database::getInstance();
        $query = 'SELECT COUNT(*) as count FROM orders WHERE status = :status';
        $result = $db->read($query, [':status' => $status]);
        return (int)($result[0]['count'] ?? 0);
    }

    // Get count of orders for a specific user
    public function getUserOrdersCount(string $user_url): int
    {
        try {
            $db = Database::getInstance();
            $query = "SELECT COUNT(*) as count FROM orders WHERE user_url = :user_url";
            $result = $db->read($query, [':user_url' => $user_url]);

            return (int)($result[0]['count'] ?? 0);
        } catch (Exception $e) {
            error_log("Error in getUserOrdersCount: " . $e->getMessage());
            return 0;
        }
    }

    // Get orders for a specific user
    public function getOrdersByUser(string $user_url): array
    {
        try {
            $db = Database::getInstance();
            $query = "SELECT o.*, COALESCE(u.name, 'Guest') AS user_name 
                     FROM orders o 
                     LEFT JOIN users u ON o.user_url = u.url_address 
                     WHERE o.user_url = :user_url 
                     ORDER BY o.id DESC";

            $result = $db->read($query, [':user_url' => $user_url]);
            return $result ?: [];
        } catch (Exception $e) {
            error_log("Error in getOrdersByUser: " . $e->getMessage());
            return [];
        }
    }

    // Get a specific order by ID
    public function getOrderById(int $orderId): array|false
    {
        try {
            $db = Database::getInstance();
            $query = "SELECT o.*, COALESCE(u.name, 'Guest') AS user_name, 
                            u.email, u.phone
                     FROM orders o 
                     LEFT JOIN users u ON o.user_url = u.url_address 
                     WHERE o.id = :order_id 
                     LIMIT 1";

            $result = $db->read($query, [':order_id' => $orderId]);
            return $result ? $result[0] : false;
        } catch (Exception $e) {
            error_log("Error in getOrderById: " . $e->getMessage());
            return false;
        }
    }

    // Get orders for the current logged-in user
    public function getUserOrders(): array
    {
        try {
            if (!isset($_SESSION['user_url'])) {
                return [];
            }
            
            $user_url = $_SESSION['user_url'];
            return $this->getOrdersByUser($user_url);
        } catch (Exception $e) {
            error_log("Error in getUserOrders: " . $e->getMessage());
            return [];
        }
    }

    // Get total spend amount for the current logged-in user
    public function getTotalSpend(): float
    {
        try {
            if (!isset($_SESSION['user_url'])) {
                return 0;
            }
            
            $db = Database::getInstance();
            $query = "SELECT SUM(total) as total_spend 
                     FROM orders 
                     WHERE user_url = :user_url AND payment_status = 'paid'";
                     
            $result = $db->read($query, [':user_url' => $_SESSION['user_url']]);
            return (float)($result[0]['total_spend'] ?? 0);
        } catch (Exception $e) {
            error_log("Error in getTotalSpend: " . $e->getMessage());
            return 0;
        }
    }
}
