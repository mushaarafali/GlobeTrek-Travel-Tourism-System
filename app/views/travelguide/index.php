<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="page-head guide-head">
    <div>
        <p class="eyebrow-dark">Sri Lanka Travel Knowledge</p>
        <h1>Travel Guides</h1>
        <p class="muted">
            Read destination tips, best travel time and practical guidance before booking.
        </p>
    </div>
</section>

<section class="search-section">
    <form class="advanced-search" method="get" action="<?= BASE_URL ?>/travelguide/index">
        <input 
            name="q" 
            value="<?= e($q ?? '') ?>" 
            placeholder="Search guides by location, category or keyword..."
        >

        <button class="btn primary" type="submit">Search</button>

        <a class="btn reset" href="<?= BASE_URL ?>/travelguide/index">
            Reset
        </a>
    </form>
</section>

<section class="guide-grid page-grid">

<?php if (!empty($guides)): ?>

    <?php foreach ($guides as $g): ?>

        <article class="guide-card premium-guide-card">

            <img 
                src="<?= BASE_URL ?>/assets/images/<?= e(!empty($g['image']) ? $g['image'] : 'default-package.jpg') ?>" 
                alt="<?= e($g['title'] ?? 'Travel Guide') ?>"
            >

            <div class="guide-body">

                <span class="guide-tag">Travel Guide</span>

                <h3><?= e($g['title'] ?? '') ?></h3>

                <p>
                    <i class="fa-solid fa-location-dot"></i>
                    <?= e($g['location'] ?? '') ?>
                </p>

                <p>
                    <i class="fa-solid fa-calendar-days"></i>
                    Best Time: <?= e(!empty($g['best_time']) ? $g['best_time'] : 'Any season') ?>
                </p>

                <!-- ❌ DESCRIPTION REMOVED HERE -->

                <?php if (
                    isset($_SESSION['user']) &&
                    (
                        $_SESSION['user']['role'] === 'admin' ||
                        $_SESSION['user']['role'] === 'staff'
                    )
                ): ?>

                    <div class="price free">
                        Free Guide
                    </div>

                <?php else: ?>

                    <a 
                        class="btn primary package-book-btn" 
                        href="<?= BASE_URL ?>/travelguide/details/<?= e($g['id'] ?? '') ?>"
                    >
                        Read Guide
                    </a>

                <?php endif; ?>

            </div>

        </article>

    <?php endforeach; ?>

<?php else: ?>

    <div class="empty-state">
        <h2>No travel guides found</h2>
        <p>Try another destination or keyword.</p>

        <a class="btn primary" href="<?= BASE_URL ?>/travelguide/index">
            View all guides
        </a>
    </div>

<?php endif; ?>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>