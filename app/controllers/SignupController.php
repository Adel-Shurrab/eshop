<?php

namespace App\Controllers;

use App\Core\Controller;

class SignupController extends Controller
{
    public function index()
    {
        $data['page_title'] = 'Signup';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                setSessionMessage('danger', 'Invalid CSRF token.');
                header('Location:' . BASE_URL . 'signup');
                exit;
            }
            $user = $this->model('user');
            $user->signup($_POST);
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->view('/signup', $data);
    }
}
