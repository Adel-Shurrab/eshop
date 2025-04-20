<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view($view, $data = [])
    {
        if (file_exists(__DIR__ . '/../views/' . THEME . $view . '.php')) {
            include __DIR__ . '/../views/' . THEME . $view . '.php';
        } else {
            include __DIR__ . '/../views/' . THEME . '/404.php';
        }
    }

    protected function model($model)
    {
        $modelClass = ucfirst($model) . 'Model';
        $modelFile = __DIR__ . '/../models/' . $modelClass . '.php';

        if (file_exists($modelFile)) {
            include $modelFile;
            $fullClassName = 'App\\Models\\' . $modelClass;
            if (class_exists($fullClassName)) {
                return new $fullClassName();
            } else {
                throw new \Exception("Class $fullClassName not found");
            }
        } else {
            throw new \Exception("Model file $modelFile not found");
        }
    }
}
