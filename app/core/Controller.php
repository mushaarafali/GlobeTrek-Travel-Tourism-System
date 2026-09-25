<?php

class Controller
{
    protected function view($view, $data = [])
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View file not found: " . $viewFile);
        }

        extract($data);

        require_once $viewFile;
    }

    protected function model($model)
    {
        $modelFile = __DIR__ . '/../models/' . $model . '.php';

        if (!file_exists($modelFile)) {
            die("Model file not found: " . $modelFile);
        }

        require_once $modelFile;

        if (!class_exists($model)) {
            die("Model class not found: " . $model);
        }

        return new $model();
    }

    protected function redirect($path = '')
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }
}