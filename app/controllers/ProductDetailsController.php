<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ProductDetailsController extends Controller
{
    public function index($slag)
    {
        $slag = htmlspecialchars($slag, ENT_QUOTES, 'UTF-8');

        $userModel = $this->model('User');
        $userData = $userModel->checkLogin(false, ['customer', 'admin']);

        if (is_array($userData)) {
            $data['user_data'] = $userData;
        } else {
            $data['user_data'] = null;
        }

        $db = Database::getInstance();

        $categories = $db->read("SELECT * FROM categories WHERE is_deleted = 0 AND disabled = 1");
        if ($categories === false) {
            $categories = [];
        }



        $productDetails = $db->read("SELECT * FROM products WHERE is_deleted = 0 AND slag = :slag", ['slag' => $slag]);
        
        $data['page_title'] = 'Product Details';
        $data['categories'] = $categories;
        $data['productDetails'] = is_array($productDetails) ? $productDetails[0] : false;
        $this->view('/productDetails', $data);
    }
}
