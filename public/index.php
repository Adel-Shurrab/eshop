<?php

use App\Core\App;

session_start([
    'cookie_lifetime' => 86400,
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'use_strict_mode' => true,
    'use_only_cookies' => true,
]);

/*
[REQUEST_SCHEME] => http
[SERVER_NAME] => eshop.local
[REQUEST_URI] => /
*/

$path = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/';
define('BASE_URL', $path);
define('ASSETS', $path . 'assets/');
define('UPLOADS_DIR', __DIR__ . '/uploads/');
define('UPLOADS_URL', $path . 'uploads/');

try {
    require_once __DIR__ . '/../vendor/autoload.php';
    include __DIR__ . '/../app/init.php';

    $app = new App();
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "An error occurred. Please try again later.";
}
