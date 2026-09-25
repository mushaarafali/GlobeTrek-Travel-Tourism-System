<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="dashboard-hero">
    <h1>Staff Dashboard</h1>
    <p>Welcome back, Staff Member.</p>
</section>

<section class="dashboard-panel">

    <div class="dashboard-stats-grid">

        <div class="dashboard-stat-card">
            <strong><?= count($packages ?? []) ?></strong>
            <span>Packages</span>
        </div>

        <div class="dashboard-stat-card">
            <strong><?= count($bookings ?? []) ?></strong>
            <span>Bookings</span>
        </div>

        <div class="dashboard-stat-card">
            <strong><?= count($inquiries ?? []) ?></strong>
            <span>Inquiries</span>
        </div>

        <div class="dashboard-stat-card revenue-card">
            <strong>LKR <?= number_format($revenue ?? 0, 2) ?></strong>
            <span>Revenue</span>
        </div>

    </div>

    <div class="dashboard-action-grid staff-action-grid">

        <a class="dashboard-action-card" href="<?= BASE_URL ?>/staff/customTrips">
            <i class="fa-solid fa-route"></i>
            <strong>Manage Customized Trips</strong>
            
        </a>

        <a class="dashboard-action-card" href="<?= BASE_URL ?>/staff/travelGuides">
            <i class="fa-solid fa-map-location-dot"></i>
            <strong>Manage Travel Guides</strong>
            
        </a>
        <a href="<?= BASE_URL ?>/staff/accommodations" class="dashboard-action-card">
                    <i class="fa-solid fa-hotel"></i>

                    <h3>Manage Accommodations</h3>
        </a>
        <a class="dashboard-action-card" href="<?= BASE_URL ?>/staff/transport">
            <i class="fa-solid fa-van-shuttle"></i>
            <strong>Manage Transport</strong>
            
        </a>

    
    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>