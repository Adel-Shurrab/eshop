<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Models\CountriesModel;
use App\Models\CheckoutModel;

class AdminController extends Controller
{
    public function index()
    {
        $data = $this->checkAdminLogin();
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

        $data['page_title'] = 'Admin';
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

        $data['page_title'] = 'Admin';
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
        $data['page_title'] = 'Admin';
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
        $data['page_title'] = 'Admin';
        $data['countries'] = $countriesModel->getCountries();
        $data['pagination'] = renderPagination($totalUsers, $itemsPerPage, $currentPage, BASE_URL . 'admin/users');

        $this->view('/admin/users', $data);
    }

    private function checkAdminLogin()
    {
        $userModel = $this->model('User');
        $userData = $userModel->checkLogin(true, ['admin']);

        if (is_array($userData)) {
            return ['user_data' => $userData];
        } else {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }
}
