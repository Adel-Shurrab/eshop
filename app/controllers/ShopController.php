<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ShopController extends Controller
{
    public function index()
    {
        $categoryId = isset($_GET['category']) ? intval($_GET['category']) : null;
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $itemsPerPage = 9;

        $userModel = $this->model('User');
        $userData = $userModel->checkLogin(false, ['customer', 'admin']);

        if (is_array($userData)) {
            $data['user_data'] = $userData;
        } else {
            $data['user_data'] = null;
        }

        $db = Database::getInstance();

        // Calculate total products
        $totalProductsQuery = "SELECT COUNT(*) as count FROM products WHERE is_deleted = 0";
        $totalProductsParams = [];
        if ($categoryId) {
            $totalProductsQuery .= " AND category_id = :category_id";
            $totalProductsParams['category_id'] = $categoryId;
        }
        $totalProductsResult = $db->read($totalProductsQuery, $totalProductsParams);
        $totalProducts = $totalProductsResult ? $totalProductsResult[0]['count'] : 0;

        // Fetch products for the current page
        $offset = ($currentPage - 1) * $itemsPerPage;
        $productsQuery = "SELECT * FROM products WHERE is_deleted = 0";
        $productsParams = [];
        if ($categoryId) {
            $productsQuery .= " AND category_id = :category_id";
            $productsParams['category_id'] = $categoryId;
        }
        $productsQuery .= " ORDER BY id DESC LIMIT $offset, $itemsPerPage";
        $products = $db->read($productsQuery, $productsParams);

        if ($products === false) {
            $products = [];
        }

        $categories = $db->read("SELECT * FROM categories WHERE is_deleted = 0 AND disabled = 1");
        if ($categories === false) {
            $categories = [];
        }

        $data['page_title'] = 'Shop';
        $data['products'] = $products;
        $data['categories'] = $categories;
        $data['category_name'] = $categoryId ? ($db->read("SELECT category FROM categories WHERE id = :id", ['id' => $categoryId])[0]['category'] ?? 'Unknown Category') : 'Featured Items';
        $data['pagination'] = renderPagination($totalProducts, $itemsPerPage, $currentPage, BASE_URL . 'shop');
        $this->view('/shop', $data);
    }
}
