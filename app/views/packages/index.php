<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="page-head package-head">
    <div>
        <p class="eyebrow dark">Discover Sri Lanka</p>
        <h1>Tour Packages</h1>
        <p class="muted">Search by destination, activity, category or maximum budget.</p>
    </div>
</section>

<section class="search-section">

    <form class="advanced-search" method="GET" action="<?= BASE_URL ?>/package/index">

        <!-- SEARCH -->
        <input 
            type="text"
            name="q"
            value="<?= e($filters['q'] ?? '') ?>"
            placeholder="Search destination, activity, guide..."
        >

        <!-- CATEGORY -->
        <select 
            name="category"
            onchange="this.form.submit()"
        >

            <option value="">All Categories</option>

            <?php foreach (($categories ?? []) as $cat): ?>

                <option 
                    value="<?= e($cat['category']) ?>"
                    <?= (($filters['category'] ?? '') === $cat['category']) ? 'selected' : '' ?>
                >

                    <?= e($cat['category']) ?>

                </option>

            <?php endforeach; ?>

        </select>

        <!-- PRICE -->
        <input 
            type="number"
            min="0"
            name="max_price"
            value="<?= e($filters['max_price'] ?? '') ?>"
            placeholder="Max price LKR"
        >

        <!-- BUTTONS -->
        <div class="search-buttons">

            <button class="btn primary" type="submit">

                Search

            </button>

            <a 
                class="btn reset"
                href="<?= BASE_URL ?>/package/index"
            >

                Reset

            </a>

        </div>

    </form>

</section>

<?php if (isset($error)): ?>
    <div class="alert error wide"><?= e($error) ?></div>
<?php endif; ?>

<section class="package-grid page-grid">

    <?php if (!empty($packages)): ?>

        <?php foreach ($packages as $p): ?>

            <?php
                $packageId = $p['id'] ?? $p['package_id'] ?? null;
                $role = $_SESSION['user']['role'] ?? 'guest';
            ?>

            <article class="package-card">
                <img 
                    src="<?= BASE_URL ?>/assets/images/<?= e($p['image'] ?: 'default-package.jpg') ?>" 
                    alt="<?= e($p['title']) ?>"
                >

                <div class="package-body">
                    <span class="badge"><?= e($p['category']) ?></span>

                    <h3><?= e($p['title']) ?></h3>

                    <p><?= e($p['destination']) ?> • <?= e($p['days']) ?> days</p>

                    <p><?= e(substr($p['description'], 0, 90)) ?>...</p>

                    <div class="price">
                        LKR <?= number_format((float)$p['price']) ?>
                    </div>

                    <?php if ($packageId): ?>

                        <?php if ($role === 'admin' || $role === 'staff'): ?>

                            <a 
                                href="<?= BASE_URL ?>/package/details/<?= e($packageId) ?>" 
                                class="btn primary package-book-btn"
                            >
                                View
                            </a>

                        <?php elseif (isset($_SESSION['user']['id']) || isset($_SESSION['user_id'])): ?>

                            <a 
                                href="<?= BASE_URL ?>/booking/create/<?= e($packageId) ?>" 
                                class="btn primary package-book-btn"
                            >
                                Book
                            </a>

                        <?php else: ?>

                            <a 
                                href="<?= BASE_URL ?>/auth/login?redirect=booking/create/<?= e($packageId) ?>" 
                                class="btn primary package-book-btn"
                            >
                                Book
                            </a>

                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </article>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="empty-state">
            <h2>No packages found</h2>
            <p>Try another destination, category or lower price filter.</p>
            <a class="btn primary" href="<?= BASE_URL ?>/package/index">View all packages</a>
        </div>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>