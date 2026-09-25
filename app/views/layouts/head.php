<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';

$brandTitle = 'GlobeTrek';
$brandSubTitle = 'Adventures';

$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? null;

if ($role === 'admin') {
    $brandSubTitle = 'Admin';
} elseif ($role === 'staff') {
    $brandSubTitle = 'Staff';
}

$homeLink = BASE_URL . '/home/index';

if ($role === 'admin') {
    $homeLink = BASE_URL . '/admin/dashboard';
} elseif ($role === 'staff') {
    $homeLink = BASE_URL . '/staff/dashboard';
} elseif ($role === 'customer') {
    $homeLink = BASE_URL . '/home/index';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(APP_NAME) ?></title>

    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/images/logo.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Goldman:wght@400;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/final5.css?v=finaluipolish">
    
    <script defer src="<?= BASE_URL ?>/assets/js/ui-fixes.js?v=nav-toggle-fix"></script>
</head>

<body>

<header class="site-header">
    <div class="header-inner">

        <a class="brand" href="<?= $homeLink ?>" aria-label="GlobeTrek home">
            <img src="<?= BASE_URL ?>/assets/images/logo.svg" alt="GlobeTrek logo">
            <span>
                <?= e($brandTitle) ?>
                <small><?= e($brandSubTitle) ?></small>
            </span>
        </a>

        <button class="menu-btn" id="menuBtn" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mainNav">
            <i class="fa-solid fa-bars"></i>
        </button>


        <nav id="mainNav" class="nav">

            <?php if ($user): ?>

                <?php if ($role === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a>
                    <a href="<?= BASE_URL ?>/admin/staff">Staff</a>
                    <a href="<?= BASE_URL ?>/admin/customers">Customers</a>
                    <a href="<?= BASE_URL ?>/admin/packages">Packages</a>
                    <a href="<?= BASE_URL ?>/admin/inquiries">Inquiries</a>
                    <a href="<?= BASE_URL ?>/admin/reports">Reports</a>
                    <a href="<?= BASE_URL ?>/home/index?preview=readonly">Website Preview</a>
                    <a class="btn small logout-btn" href="<?= BASE_URL ?>/auth/logout">Logout</a>

                <?php elseif ($role === 'staff'): ?>
                    <a href="<?= BASE_URL ?>/staff/dashboard">Dashboard</a>
                    <a href="<?= BASE_URL ?>/staff/bookings">Bookings</a>
                    <a href="<?= BASE_URL ?>/staff/packages">Packages</a>
                    <a href="<?= BASE_URL ?>/staff/inquiries">Inquiries</a>
                    <a href="<?= BASE_URL ?>/staff/reports">Reports</a>
                    <a href="<?= BASE_URL ?>/home/index?preview=readonly">Website Preview</a>
                    <a class="btn small logout-btn" href="<?= BASE_URL ?>/auth/logout">Logout</a>

                <?php else: ?>
                    <a href="<?= BASE_URL ?>/home/index">Home</a>
                    <a href="<?= BASE_URL ?>/home/about">About</a>
                    <a href="<?= BASE_URL ?>/package/index">Packages</a>
                    <a href="<?= BASE_URL ?>/travelguide/index">Travel Guides</a>
                    <a href="<?= BASE_URL ?>/trip/customize">Customize Trip</a>
                    <a href="<?= BASE_URL ?>/inquiry/create">Contact</a>
                    <a href="<?= BASE_URL ?>/customer/dashboard">Dashboard</a>
                    <?php if(isset($_SESSION['user'])): ?>
    <a href="<?= BASE_URL ?>/customer/profile">Profile</a>
<?php endif; ?>
                    <a class="btn small logout-btn" href="<?= BASE_URL ?>/auth/logout">Logout</a>
                <?php endif; ?>

            <?php else: ?>
                <a href="<?= BASE_URL ?>/home/index">Home</a>
                <a href="<?= BASE_URL ?>/home/about">About</a>
                <a href="<?= BASE_URL ?>/package/index">Packages</a>
                <a href="<?= BASE_URL ?>/travelguide/index">Travel Guides</a>
                <a href="<?= BASE_URL ?>/trip/customize">Customize Trip</a>
                <a href="<?= BASE_URL ?>/inquiry/create">Contact</a>
                <a href="<?= BASE_URL ?>/auth/login">Login</a>
            <?php endif; ?>

        </nav>

    </div>
</header>

<main>

<?php if (function_exists('flash') && $msg = flash('success')): ?>
    <div class="flash success"><?= e($msg) ?></div>
<?php endif; ?>

<?php if (function_exists('flash') && $msg = flash('error')): ?>
    <div class="flash error"><?= e($msg) ?></div>
<?php endif; ?>
