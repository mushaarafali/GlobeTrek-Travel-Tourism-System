<?php

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function currentUser() {
    return $_SESSION['user'] ?? null;
}

function hasRole($role) {
    return isLoggedIn() && ($_SESSION['user']['role'] ?? '') === $role;
}

function requireLogin() {
    if (!isLoggedIn()) {
        flash('error', 'Please login first.');
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}

function requireRole($role)
{
    if (!isset($_SESSION['user'])) {

        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    if ($_SESSION['user']['role'] !== $role) {

        session_destroy();

        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}

function e($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function flash($key, $message = null) {

    // SET flash message
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    // GET & REMOVE flash message
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }

    return null;
}

function post($key, $default = '') {
    return trim($_POST[$key] ?? $default);
}

?>