<?php
// App configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'GlobeTrek Adventures');
define('ADMIN_EMAIL', 'globetrekproject@gmail.com');

// Dynamic base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php')), '/');

define('BASE_URL', $protocol . '://' . $host . $scriptDir);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_globetrek');
define('DB_USER', 'root');
define('DB_PASS', '');

// SMTP configuration
define('SMTP_ENABLED', true);

define('SMTP_HOST', 'smtp-relay.brevo.com');
define('SMTP_PORT', 587);

define('SMTP_USERNAME', 'YOUR_SMTP_USERNAME');
define('SMTP_PASSWORD', 'YOUR_SMTP_PASSWORD');

define('SMTP_FROM_EMAIL', 'YOUR_EMAIL_ADDRESS');
define('SMTP_FROM_NAME', 'GlobeTrek Adventures');