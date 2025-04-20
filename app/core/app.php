<?php

namespace App\Core;

class App
{
    protected $controller = 'home';
    protected $method = 'index';
    protected $params;

    public function __construct()
    {
        $url = $this->parseURL();
        if (class_exists('App\\Controllers\\' . ucfirst($url[0]) . 'Controller')) {
            $this->controller = strtolower($url[0]);
            unset($url[0]);
        }
        $controllerClass = 'App\\Controllers\\' . ucfirst($this->controller) . 'Controller';
        $this->controller = new $controllerClass;

        if (isset($url[1])) {
            $url[1] = strtolower($url[1]);
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = (count($url) > 0) ? $url : ['home'];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseURL()
    {
        $url = $_GET['url'] ?? 'home';
        return explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));
    }
}
