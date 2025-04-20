<?php

namespace App\Controllers;

use App\Core\Controller;

class LoginController extends Controller
{
    public function index()
    {
        $data['page_title'] = 'Login';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setSessionMessage('danger', 'Invalid CSRF token.');
                header('Location:' . BASE_URL . 'login');
                exit;
            }

            $user = $this->model('user');
            $user->login($_POST);
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->view('/login', $data);
    }
}
