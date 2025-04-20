<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CartModel;

class CartController extends Controller
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

        $data['page_title'] = 'Cart';
        $this->view('/cart', $data);
    }
}