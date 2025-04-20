<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ShopController extends Controller
{
    public function index()
    {

        $userModel = $this->model('User');
        $userData = $userModel->checkLogin(false, ['customer', 'admin']);

        if (is_array($userData)) {
            $data['user_data'] = $userData;
        } else {
            $data['user_data'] = null;
        }

        $db = Database::getInstance();
        $products = $db->read("SELECT * FROM products WHERE is_deleted = 0 ORDER BY id DESC");
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
        $this->view('/shop', $data);
    }
}
