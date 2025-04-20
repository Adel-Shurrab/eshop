<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CheckoutModel;
use Exception;

class AjaxOrderController extends Controller
{
    private CheckoutModel $checkoutModel;

    public function __construct()
    {
        $this->checkoutModel = $this->model('Checkout');
    }

    public function index()
    {
        try {
            $data = $this->getRequestData();

            if (!is_object($data) || !isset($data->dataType)) {
                $this->sendErrorResponse('Invalid data format.', 400);
                return;
            }

            switch ($data->dataType) {
                case 'get_orders':
                    $this->getOrders();
                    break;
                case 'search_orders':
                    $this->searchOrders($data);
                    break;
                case 'change_order_status':
                    $this->changeOrderStatus($data);
                    break;
                case 'change_payment_status':
                    $this->changePaymentStatus($data);
                    break;
                case 'get_order_stats':
                    $this->getOrderStats();
                    break;
                case 'get_order_details':
                    $this->getOrderDetails($data);
                    break;
                default:
                    $this->sendErrorResponse('Invalid dataType.', 400);
                    break;
            }
        } catch (Exception $e) {
            error_log('AjaxOrderController error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while processing your request.', 500);
        }
    }

    private function getOrders()
    {
        try {
            $orders = $this->checkoutModel->getAllOrders();
            foreach ($orders as &$order) {
                $order['items'] = $this->checkoutModel->getOrderItems($order['id']);
            }
            $tableHtml = $this->generateOrdersTable($orders);
            echo json_encode(['success' => true, 'orders' => $orders, 'table_html' => $tableHtml]);
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function searchOrders($data)
    {
        try {
            if (!isset($data->search)) {
                $this->sendErrorResponse('Search term is required.', 400);
                return;
            }

            $orders = $this->checkoutModel->searchOrders(
                $data->search,
                $data->status ?? '',
                $data->payment ?? ''
            );

            if ($orders !== false) {
                foreach ($orders as &$order) {
                    $order['items'] = $this->checkoutModel->getOrderItems($order['id']);
                }
                $tableHtml = $this->generateOrdersTable($orders);
                echo json_encode(['success' => true, 'orders' => $orders, 'table_html' => $tableHtml]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No orders found matching your search criteria.']);
            }
        } catch (Exception $e) {
            error_log('Search orders error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while searching orders.', 500);
        }
    }

    private function changeOrderStatus($data)
    {
        try {
            if (!isset($data->id) || !isset($data->status)) {
                $this->sendErrorResponse('Invalid order ID or status.', 400);
                return;
            }

            $result = $this->checkoutModel->updateOrderStatus((int)$data->id, $data->status);

            if ($result) {
                $this->getOrders(); // Refresh orders after status update
            } else {
                $this->sendErrorResponse('Failed to update order status.', 500);
            }
        } catch (Exception $e) {
            error_log('Change order status error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function changePaymentStatus($data)
    {
        try {
            if (!isset($data->id) || !isset($data->status)) {
                $this->sendErrorResponse('Invalid order ID or payment status.', 400);
                return;
            }

            $result = $this->checkoutModel->updatePaymentStatus((int)$data->id, $data->status);

            if ($result) {
                $this->getOrders(); // Refresh orders after payment status update
            } else {
                $this->sendErrorResponse('Failed to update payment status.', 500);
            }
        } catch (Exception $e) {
            error_log('Change payment status error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function getOrderStats()
    {
        try {
            $stats = [
                'total' => $this->checkoutModel->countAllOrders(),
                'pending' => $this->checkoutModel->countOrdersByStatus('pending'),
                'processing' => $this->checkoutModel->countOrdersByStatus('processing'),
                'completed' => $this->checkoutModel->countOrdersByStatus('shipped') + $this->checkoutModel->countOrdersByStatus('delivered'),
                'cancelled' => $this->checkoutModel->countOrdersByStatus('cancelled'),
            ];

            echo json_encode(['success' => true, 'statistics' => $stats]);
        } catch (Exception $e) {
            error_log('Get order stats error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving order statistics.', 500);
        }
    }

    private function getOrderDetails($data)
    {
        try {
            if (!isset($data->id)) {
                $this->sendErrorResponse('Order ID is required.', 400);
                return;
            }

            $orderId = (int)$data->id;
            $order = $this->checkoutModel->getOrderById($orderId);

            if (!$order) {
                $this->sendErrorResponse('Order not found.', 404);
                return;
            }

            // Get order items
            $order['items'] = $this->checkoutModel->getOrderItems($orderId);

            echo json_encode(['success' => true, 'order' => $order]);
        } catch (Exception $e) {
            error_log('Get order details error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving order details.', 500);
        }
    }

    private function getRequestData()
    {
        $rawData = file_get_contents('php://input');
        return !empty($rawData) ? json_decode($rawData) : (object)$_POST;
    }

    private function sendErrorResponse(string $message, int $statusCode): void
    {
        http_response_code($statusCode);
        echo json_encode(['success' => false, 'message' => $message]);
    }

    private function generateOrdersTable($orders)
    {
        if (empty($orders)) {
            return '<tr><td colspan="7" class="text-center text-muted">No orders found</td></tr>';
        }

        $html = '';
        foreach ($orders as $order) {
            $html .= '<tr>';
            $html .= '<td>#' . htmlspecialchars($order['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars(date('M j, Y H:i', strtotime($order['date']))) . '</td>';
            $html .= '<td>' . htmlspecialchars($order['user_name'] ?? 'Guest') . '</td>';
            $html .= '<td>$' . number_format($order['total'], 2) . '</td>';
            $html .= '<td><span class="order-status status-' . htmlspecialchars($order['status']) . '">' . ucfirst(htmlspecialchars($order['status'])) . '</span></td>';
            $html .= '<td><span class="payment-' . htmlspecialchars($order['payment_status']) . '">' . ucfirst(htmlspecialchars($order['payment_status'])) . '</span></td>';
            $html .= '<td><button class="btn btn-info btn-xs view-order-details" data-id="' . $order['id'] . '"><i class="fa fa-eye"></i> View</button></td>';
            $html .= '</tr>';
        }

        return $html;
    }
}
