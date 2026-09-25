<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="package-detail-section">

    <div class="package-detail-card">

        <div class="package-detail-image">
            <img 
                src="<?= BASE_URL ?>/assets/images/<?= e($guide['image'] ?: 'default-package.jpg') ?>" 
                alt="<?= e($guide['title']) ?>"
            >
        </div>

        <div class="package-detail-content">

            <span class="package-badge">Free Travel Guide</span>

            <h1><?= e($guide['title']) ?></h1>

            <p class="package-location">
                <?= e($guide['location']) ?> • <?= e($guide['category']) ?>
            </p>

            <p>
                <strong>Best Time:</strong> <?= e($guide['best_time'] ?: 'Any season') ?>
            </p>

            <p class="package-description">
                <?= nl2br(e($guide['description'])) ?>
            </p>

            <?php if (!empty($guide['tips'])): ?>
                <h3>Travel Tips</h3>
                <p><?= nl2br(e($guide['tips'])) ?></p>
            <?php endif; ?>

            <div class="price free">Free Guide</div>

            <a href="<?= BASE_URL ?>/travelguide/index" class="btn primary">
                Back to Travel Guides
            </a>

        </div>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>