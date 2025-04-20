<?php

namespace App\Controllers;

use App\Core\Controller;

class LogoutController extends Controller
{
    public function index()
    {
        $userModel = $this->model('User');
        $userModel->logout();
    }
}
