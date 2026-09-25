<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="dashboard-hero">
    <h1>Admin Dashboard</h1>
    <p>Welcome back to System Admin.</p>
</section>

<section class="admin-dashboard-panel">

    <div class="admin-dashboard-stats-grid">

        <div class="admin-dashboard-stat-card">
            <strong><?= e($packageCount ?? 0) ?></strong>
            <span>Packages</span>
        </div>

        <div class="dashboard-stat-card">
            <strong><?= e($bookingCount ?? 0) ?></strong>
            <span>Bookings</span>
        </div>

        <div class="admin-dashboard-stat-card">
            <strong><?= e($inquiryCount ?? 0) ?></strong>
            <span>Inquiries</span>
        </div>

        <div class="admin-dashboard-stat-card">
            <strong><?= e($customTripCount ?? 0) ?></strong>
            <span>Custom Trips</span>
        </div>

        <div class="admin-dashboard-stat-card revenue-card">
            <strong>LKR <?= number_format($revenue ?? 0, 2) ?></strong>
            <span>Revenue</span>
        </div>

    </div>

    <div class="admin-dashboard-action-grid admin-action-grid">

        <a class="dashboard-action-card" href="<?= BASE_URL ?>/admin/bookings">
            <i class="fa-solid fa-calendar-check"></i>
            <strong>Manage Bookings</strong>
            
        </a>


        <a class="admin-dashboard-action-card" href="<?= BASE_URL ?>/admin/customTrips">
            <i class="fa-solid fa-route"></i>
            <strong>Manage Customized Trips</strong>
            
        </a>
        <a href="<?= BASE_URL ?>/admin/accommodations" class="admin-dashboard-action-card">
            <i class="fa-solid fa-hotel"></i>

            <h3>Manage Accommodations</h3>
        </a>

        <a class="admin-dashboard-action-card" href="<?= BASE_URL ?>/admin/transport">
            <i class="fa-solid fa-van-shuttle"></i>
            <strong>Manage Transport</strong>
            
        </a>

        <a class="admin-dashboard-action-card" href="<?= BASE_URL ?>/admin/travelGuides">
            <i class="fa-solid fa-map-location-dot"></i>
            <strong>Manage Travel Guides</strong>
            
        </a>


        <a class="admin-dashboard-action-card" href="<?= BASE_URL ?>/admin/prediction">
            <i class="fa-solid fa-chart-column"></i>
            <strong>Sales Prediction View</strong>
            
        </a>




    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>