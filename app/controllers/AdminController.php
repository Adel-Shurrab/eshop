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

        $products = $productModel->getProducts();
        $tbl_rows = $productModel->makeTable($products);
        $data['tbl_rows'] = $tbl_rows;

        $enabledCategories = $productModel->getEnabledCategories();
        $parentOptions = $productModel->makeParentOptions($enabledCategories);
        $data['parentOptions'] = $parentOptions;

        $data['page_title'] = 'Admin';
        $this->view('/admin/products', $data);
    }

    public function orders()
    {
        $data = $this->checkAdminLogin();

        $orderModel = new CheckoutModel();
        
        // Check if filtering by user
        $user_filter = $_GET['user'] ?? '';
        
        if (!empty($user_filter)) {
            // If user filter is provided, get orders for that user
            $orders = $orderModel->getOrdersByUser($user_filter);
            $data['user_filter'] = $user_filter;
        } else {
            // Otherwise get all orders
            $orders = $orderModel->getAllOrders();
        }

        foreach ($orders as &$order) {
            $order['items'] = $orderModel->getOrderItems((int)$order['id']);
        }

        $data['orders'] = $orders;
        $data['page_title'] = 'Admin';
        $this->view('/admin/orders', $data);
    }

    public function users()
    {
        $data = $this->checkAdminLogin();

        $userModel = new UserModel();
        $countriesModel = new CountriesModel();
        $orderModel = new CheckoutModel();

        $users = $userModel->getUsers();

        foreach ($users as &$user) {
            $user['countries'] = $countriesModel->getCountries();
            $user['order_count'] = $orderModel->getUserOrdersCount($user['url_address']);
        }

        $data['users'] = $users;
        $data['page_title'] = 'Admin';
        $data['countries'] = $countriesModel->getCountries();

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
