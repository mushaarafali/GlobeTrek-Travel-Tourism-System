<?php require __DIR__ . '/../layouts/head.php'; ?>
<?php $roleBase = ($_SESSION['user']['role'] ?? 'admin'); ?>
<section class="admin-page">
    <div class="admin-page-header">
        <div>
            <span>Business Intelligence</span>
            <h1>Sales & Customer Reports</h1>
            <p>Revenue, booking, package, payment and prediction summary.</p>
        </div>
        <button class="btn primary" onclick="window.print()">Print / Save PDF</button>
    </div>

    <div class="dashboard-stats-grid">
        <div class="dashboard-stat-card"><strong>LKR <?= number_format($revenue['total_revenue'] ?? 0, 2) ?></strong><span>Total Revenue</span></div>
        <div class="dashboard-stat-card"><strong><?= e($revenue['total_bookings'] ?? 0) ?></strong><span>Total Bookings</span></div>
        <div class="dashboard-stat-card"><strong>LKR <?= number_format($revenue['average_order'] ?? 0, 2) ?></strong><span>Average Booking Value</span></div>
        <div class="dashboard-stat-card revenue-card"><strong>LKR <?= number_format($prediction['predictedRevenue'] ?? 0, 2) ?></strong><span>Next Month Prediction</span></div>
    </div>

    <div class="report-grid">
        <div class="table-card admin-table-card">
            <h2>Booking Summary</h2>
            <table class="table"><tr><th>Status</th><th>Bookings</th><th>Revenue</th></tr>
                <?php foreach (($statuses ?? []) as $row): ?>
                <tr><td><?= e($row['status']) ?></td><td><?= e($row['total']) ?></td><td>LKR <?= number_format($row['revenue']) ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="table-card admin-table-card">
            <h2>Payment Summary</h2>
            <table class="table"><tr><th>Status</th><th>Payments</th><th>Amount</th></tr>
                <?php if (empty($payments)): ?><tr><td colspan="3">Limited staff view or no payment records.</td></tr><?php endif; ?>
                <?php foreach (($payments ?? []) as $row): ?>
                <tr><td><?= e($row['status']) ?></td><td><?= e($row['total']) ?></td><td>LKR <?= number_format($row['amount']) ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <div class="table-card admin-table-card">
        <h2>Package Performance</h2>
        <table class="table"><tr><th>Package</th><th>Destination</th><th>Bookings</th><th>Revenue</th></tr>
            <?php foreach (($packages ?? []) as $row): ?>
            <tr><td><?= e($row['title']) ?></td><td><?= e($row['destination']) ?></td><td><?= e($row['bookings']) ?></td><td>LKR <?= number_format($row['revenue']) ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php if (!empty($customers)): ?>
    <div class="table-card admin-table-card">
        <h2>Customer Details</h2>
        <table class="table"><tr><th>Name</th><th>Email</th><th>Phone</th><th>Registered</th></tr>
            <?php foreach ($customers as $c): ?>
            <tr><td><?= e($c['name']) ?></td><td><?= e($c['email']) ?></td><td><?= e($c['phone']) ?></td><td><?= e($c['created_at']) ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
