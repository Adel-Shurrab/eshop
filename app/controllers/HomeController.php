<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class HomeController extends Controller
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
        $products = $db->read("SELECT * FROM products WHERE is_deleted = 0 ORDER BY id DESC LIMIT 6");
        if ($products === false) {
            $products = [];
        }

        $categories = $db->read("SELECT * FROM categories WHERE is_deleted = 0 AND disabled = 1");
        if ($categories === false) {
            $categories = [];
        }

        // Get featured categories for tabs
        $categoriesForProducts = $db->read("SELECT * FROM categories WHERE is_deleted = 0 AND id IN (147, 148, 161, 180, 166) AND disabled = 1");
        $categoryProducts = [];
        
        foreach ($categoriesForProducts as $category) {
            // Get products directly from this category AND from its subcategories
            $categoryProducts[$category['id']] = $db->read("
                SELECT * FROM products 
                WHERE (
                    category_id = ? OR 
                    category_id IN (SELECT id FROM categories WHERE parent = ?)
                ) 
                AND is_deleted = 0 
                ORDER BY id DESC 
                LIMIT 4", 
                [$category['id'], $category['id']]
            );
            
            if($categoryProducts[$category['id']] === false) {
                $categoryProducts[$category['id']] = [];
            }
        }

        $recommendedProducts = $db->read("
            SELECT * FROM products 
            WHERE is_deleted = 0 
            ORDER BY RAND() 
            LIMIT 9"
        );
        if ($recommendedProducts === false) {
            $recommendedProducts = [];
        }

        $data['page_title'] = 'Home';
        $data['products'] = $products;
        $data['categories'] = $categories;
        $data['categoryProducts'] = $categoryProducts;
        $data['categoriesForProducts'] = $categoriesForProducts;
        $data['recommendedProducts'] = $recommendedProducts;
        $this->view('/index', $data);
    }
}
