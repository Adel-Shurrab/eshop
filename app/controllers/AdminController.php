<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Models\CountriesModel;
use App\Models\CheckoutModel;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class AdminController extends Controller
{
    public function index()
    {
        $data = $this->checkAdminLogin();
        
        // Get summary statistics for the dashboard
        $userModel = new UserModel();
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $orderModel = new CheckoutModel();
        
        // Count users
        $users = $userModel->getUsers();
        $data['total_users'] = count($users);
        
        // Count products
        $products = $productModel->getProducts();
        $data['total_products'] = count($products);
        
        // Count categories
        $categories = $categoryModel->getCategories();
        $data['total_categories'] = count($categories);
        
        // Get order statistics
        $allOrders = $orderModel->getAllOrders();
        $data['total_orders'] = count($allOrders);
        $data['pending_orders'] = $orderModel->countOrdersByStatus('pending');
        $data['completed_orders'] = $orderModel->countOrdersByStatus('shipped') + $orderModel->countOrdersByStatus('delivered');
        $data['cancelled_orders'] = $orderModel->countOrdersByStatus('cancelled');
        
        // Calculate total revenue
        $totalRevenue = 0;
        foreach ($allOrders as $order) {
            if ($order['payment_status'] === 'paid') {
                $totalRevenue += floatval($order['total']);
            }
        }
        $data['total_revenue'] = number_format($totalRevenue, 2);
        
        // Get recent orders for dashboard
        $recentOrders = array_slice($allOrders, 0, 5);
        foreach ($recentOrders as &$order) {
            $order['items'] = $orderModel->getOrderItems((int)$order['id']);
        }
        $data['recent_orders'] = $recentOrders;
        
        $data['page_title'] = 'Admin';
        $this->view('/admin/index', $data);
    }

    public function categories()
    {
        $data = $this->checkAdminLogin();

        $categoryModel = $this->model('Category');

        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $itemsPerPage = 13;

        $totalCategoriesResult = $categoryModel->getCategories();
        $totalCategories = count($totalCategoriesResult);

        $offset = ($currentPage - 1) * $itemsPerPage;
        $categories = array_slice($totalCategoriesResult, $offset, $itemsPerPage);

        $tbl_rows = $categoryModel->makeTable($categories);
        $data['tbl_rows'] = $tbl_rows;

        $enabledCategories = $categoryModel->getEnabledCategories();
        $parentOptions = $categoryModel->makeParentOptions($enabledCategories);
        $data['parentOptions'] = $parentOptions;

        $data['pagination'] = renderPagination($totalCategories, $itemsPerPage, $currentPage, BASE_URL . 'admin/categories');

        $data['page_title'] = 'categories';
        $this->view('/admin/categories', $data);
    }

    public function products()
    {
        $data = $this->checkAdminLogin();

        $productModel = $this->model('Product');

        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $itemsPerPage = 13;

        $totalProductsResult = $productModel->getProducts();
        $totalProducts = count($totalProductsResult);

        $offset = ($currentPage - 1) * $itemsPerPage;
        $products = array_slice($totalProductsResult, $offset, $itemsPerPage);

        $tbl_rows = $productModel->makeTable($products);
        $data['tbl_rows'] = $tbl_rows;

        $enabledCategories = $productModel->getEnabledCategories();
        $parentOptions = $productModel->makeParentOptions($enabledCategories);
        $data['parentOptions'] = $parentOptions;

        $data['pagination'] = renderPagination($totalProducts, $itemsPerPage, $currentPage, BASE_URL . 'admin/products');

        $data['page_title'] = 'products';
        $this->view('/admin/products', $data);
    }

    public function orders()
    {
        $data = $this->checkAdminLogin();

        $orderModel = new CheckoutModel();
        
        // Check if filtering by user
        $user_filter = $_GET['user'] ?? '';
        
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $itemsPerPage = 7;
        
        if (!empty($user_filter)) {
            // If user filter is provided, get orders for that user
            $allOrders = $orderModel->getOrdersByUser($user_filter);
            $data['user_filter'] = $user_filter;
        } else {
            // Otherwise get all orders
            $allOrders = $orderModel->getAllOrders();
        }
        
        $totalOrders = count($allOrders);
        $offset = ($currentPage - 1) * $itemsPerPage;
        $orders = array_slice($allOrders, $offset, $itemsPerPage);

        foreach ($orders as &$order) {
            $order['items'] = $orderModel->getOrderItems((int)$order['id']);
        }

        // Count orders by status for statistics
        $data['total_orders'] = $totalOrders;
        $data['pending_orders'] = $orderModel->countOrdersByStatus('pending');
        $data['completed_orders'] = $orderModel->countOrdersByStatus('shipped') + $orderModel->countOrdersByStatus('delivered');
        $data['cancelled_orders'] = $orderModel->countOrdersByStatus('cancelled');

        $data['orders'] = $orders;
        $data['page_title'] = 'orders';
        $data['pagination'] = renderPagination($totalOrders, $itemsPerPage, $currentPage, BASE_URL . 'admin/orders');
        
        $this->view('/admin/orders', $data);
    }

    public function users()
    {
        $data = $this->checkAdminLogin();

        $userModel = new UserModel();
        $countriesModel = new CountriesModel();
        $orderModel = new CheckoutModel();

        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $itemsPerPage = 7;

        $totalUsersResult = $userModel->getUsers();
        $totalUsers = count($totalUsersResult);

        $offset = ($currentPage - 1) * $itemsPerPage;
        $users = array_slice($totalUsersResult, $offset, $itemsPerPage);

        foreach ($users as &$user) {
            $user['countries'] = $countriesModel->getCountries();
            $user['order_count'] = $orderModel->getUserOrdersCount($user['url_address']);
        }

        $data['users'] = $users;
        $data['page_title'] = 'users';
        $data['countries'] = $countriesModel->getCountries();
        $data['pagination'] = renderPagination($totalUsers, $itemsPerPage, $currentPage, BASE_URL . 'admin/users');

        $this->view('/admin/users', $data);
    }

    public function ajaxDashboard()
    {
        header('Content-Type: application/json');
        
        if (!isset($_GET['action'])) {
            echo json_encode(['success' => false, 'message' => 'Action is required']);
            return;
        }
        
        $data = $this->checkAdminLogin(false);
        if (!is_array($data)) {
            echo json_encode(['success' => false, 'message' => 'Authentication required']);
            return;
        }
        
        $action = $_GET['action'];
        
        switch ($action) {
            case 'getStats':
                $this->getDashboardStats();
                break;
                
            case 'getRecentOrders':
                $this->getRecentOrders();
                break;
                
            case 'getRecentActivity':
                $this->getRecentActivity();
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }
    
    private function getDashboardStats()
    {
        $userModel = new UserModel();
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $orderModel = new CheckoutModel();
        
        // Count users
        $users = $userModel->getUsers();
        $totalUsers = count($users);
        
        // Count products
        $products = $productModel->getProducts();
        $totalProducts = count($products);
        
        // Count categories
        $categories = $categoryModel->getCategories();
        $totalCategories = count($categories);
        
        // Get order statistics
        $allOrders = $orderModel->getAllOrders();
        $totalOrders = count($allOrders);
        $pendingOrders = $orderModel->countOrdersByStatus('pending');
        $completedOrders = $orderModel->countOrdersByStatus('shipped') + $orderModel->countOrdersByStatus('delivered');
        $cancelledOrders = $orderModel->countOrdersByStatus('cancelled');
        
        // Calculate total revenue
        $totalRevenue = 0;
        foreach ($allOrders as $order) {
            if ($order['payment_status'] === 'paid') {
                $totalRevenue += floatval($order['total']);
            }
        }
        
        echo json_encode([
            'success' => true,
            'stats' => [
                'total_users' => $totalUsers,
                'total_products' => $totalProducts,
                'total_categories' => $totalCategories,
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'completed_orders' => $completedOrders,
                'cancelled_orders' => $cancelledOrders,
                'total_revenue' => number_format($totalRevenue, 2)
            ]
        ]);
    }
    
    private function getRecentOrders()
    {
        $orderModel = new CheckoutModel();
        $allOrders = $orderModel->getAllOrders();
        
        // Get 5 most recent orders
        $recentOrders = array_slice($allOrders, 0, 5);
        
        $formattedOrders = [];
        foreach ($recentOrders as $order) {
            $formattedOrders[] = [
                'id' => $order['id'],
                'customer' => $order['user_name'] ?? 'Guest',
                'amount' => '$' . number_format($order['total'], 2),
                'status' => $order['status'],
                'date' => date('Y-m-d', strtotime($order['date']))
            ];
        }
        
        echo json_encode([
            'success' => true,
            'orders' => $formattedOrders
        ]);
    }
    
    private function getRecentActivity()
    {
        $orderModel = new CheckoutModel();
        $userModel = new UserModel();
        $productModel = new ProductModel();
        
        $activities = [];
        
        // Get recent orders
        $recentOrders = array_slice($orderModel->getAllOrders(), 0, 3);
        foreach ($recentOrders as $order) {
            $activities[] = [
                'type' => 'order',
                'text' => 'New order #' . $order['id'] . ' has been placed',
                'date' => $this->timeAgo(strtotime($order['date']))
            ];
        }
        
        // Get recent user registrations (if you have registration date)
        $recentUsers = array_slice($userModel->getUsers(), 0, 2);
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'text' => 'New user "' . $user['name'] . '" registered',
                'date' => $this->timeAgo(strtotime($user['date'] ?? 'now'))
            ];
        }
        
        // Sort activities by date (most recent first)
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        echo json_encode([
            'success' => true,
            'activities' => array_slice($activities, 0, 5)
        ]);
    }
    
    private function timeAgo($timestamp)
    {
        $difference = time() - $timestamp;
        
        if ($difference < 60) {
            return 'Just now';
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($difference < 604800) {
            $days = floor($difference / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M j, Y', $timestamp);
        }
    }

    private function checkAdminLogin($redirect = true)
    {
        $userModel = $this->model('User');
        $userData = $userModel->checkLogin(true, ['admin']);

        if (is_array($userData)) {
            return ['user_data' => $userData];
        } else {
            if ($redirect) {
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            return false;
        }
    }
}
