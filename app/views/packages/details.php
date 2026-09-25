<?php 
require __DIR__ . '/../layouts/head.php'; 
?>

<?php if (empty($package)): ?>

    <section class="package-detail-section">
        <div class="package-detail-card">
            <h1>Package not found</h1>
            <a href="<?= BASE_URL ?>/package/index" class="btn primary">
                Back to Packages
            </a>
        </div>
    </section>

<?php else: ?>

<section class="package-detail-section">

    <div class="package-detail-card">

        <div class="package-detail-image">
            <img 
                src="<?= BASE_URL ?>/assets/images/<?= e($package['image']) ?>" 
                alt="<?= e($package['title']) ?>"
            >
        </div>

        <div class="package-detail-content">

            <span class="package-badge">
                <?= e($package['category']) ?>
            </span>

            <h1>
                <?= e($package['title']) ?>
            </h1>

            <p class="package-location">
                <?= e($package['destination']) ?> • <?= e($package['days']) ?> days
            </p>

            <p class="package-description">
                <?= e($package['description']) ?>
            </p>

            <h2 class="package-price">
                LKR <?= number_format($package['price']) ?>
            </h2>

            <?php 
            if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'customer'): ?>

        <div class="package-button-center">
        <a href="<?= BASE_URL ?>/booking/create/<?= e($package['id'] ?? '') ?>" class="btn primary"> Book This Package
        </a>
        </div>

            <?php elseif (!isset($_SESSION['user'])): ?>

                <div class="package-button-center">
                    <a href="<?= BASE_URL ?>/auth/login" class="btn primary">
                        Login to Book
                    </a>
                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<?php endif; ?>

<?php 
require __DIR__ . '/../layouts/footer.php'; 
?>