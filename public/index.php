<?php

require_once __DIR__ . '/../config/config.php';

/* Prevent browser back-button cache after logout */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/helpers/auth_helper.php';

/* =========================================
   GET URL
========================================= */
$url = $_GET['url'] ?? 'home/index';
$url = trim($url, '/');
$parts = explode('/', $url);

/* =========================================
   CONTROLLER / METHOD / PARAMS
========================================= */
$controllerName = ucfirst($parts[0] ?? 'home') . 'Controller';
$method = $parts[1] ?? 'index';
$params = array_slice($parts, 2);

/* =========================================
   AJAX ROUTE
========================================= */
if (
    strtolower($parts[0] ?? '') === 'auth'
    && strtolower($parts[1] ?? '') === 'checkemail'
) {
    $authFile = __DIR__ . '/../app/controllers/AuthController.php';

    if (file_exists($authFile)) {
        require_once $authFile;

        $controller = new AuthController();

        if (method_exists($controller, 'checkEmail')) {
            $controller->checkEmail();
            exit;
        }
    }

    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Route not found'
    ]);
    exit;
}

/* =========================================
   CONTROLLER FILE
========================================= */
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

/* =========================================
   CONTROLLER NOT FOUND
========================================= */
if (!file_exists($controllerFile)) {
    $controllerName = 'HomeController';
    $method = 'notFound';
    $controllerFile = __DIR__ . '/../app/controllers/HomeController.php';
}

/* =========================================
   LOAD CONTROLLER
========================================= */
require_once $controllerFile;

/* =========================================
   INVALID CLASS
========================================= */
if (!class_exists($controllerName)) {
    require_once __DIR__ . '/../app/controllers/HomeController.php';

    $controller = new HomeController();
    $controller->notFound();
    exit;
}

/* =========================================
   CREATE CONTROLLER
========================================= */
$controller = new $controllerName();

/* =========================================
   INVALID METHOD
========================================= */
if (!method_exists($controller, $method)) {
    if (method_exists($controller, 'notFound')) {
        $method = 'notFound';
    } else {
        require_once __DIR__ . '/../app/controllers/HomeController.php';

        $home = new HomeController();
        $home->notFound();
        exit;
    }
}

/* =========================================
   CALL METHOD
========================================= */
call_user_func_array([$controller, $method], $params);