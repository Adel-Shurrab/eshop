<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CheckoutModel;

class ProfileController extends Controller
{
    public function index()
    {
        $userModel = $this->model('User');
        $userData = $userModel->checkLogin(true, ['customer', 'admin']);
        if (is_array($userData)) {
            $data['user_data'] = $userData;
        }
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $orderModel = $this->model('Checkout');
        $data['orders'] = $orderModel->getUserOrders();
        $data['total_spend'] = $orderModel->getTotalSpend();

        $data['order_items'] = [];
        foreach ($data['orders'] as $order) {
            $data['order_items'][$order['id']] = $orderModel->getOrderItems((int)$order['id']);
        }

        $data['page_title'] = 'Profile';
        $this->view('/profile', $data);
    }
}
