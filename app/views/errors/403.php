<?php 
require __DIR__ . '/../layouts/head.php';
?>

<section class="error-page">
    <h1>403</h1>

    <p>You do not have permission to access this page.</p>

    <a class="btn" href="<?= BASE_URL ?>/home/index">
        Go Home
    </a>
</section>

<?php 
require __DIR__ . '/../layouts/footer.php';
?>