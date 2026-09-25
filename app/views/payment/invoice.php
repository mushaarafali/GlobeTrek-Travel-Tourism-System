<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="invoice-page">

    <div class="invoice-actions no-print">
        <a class="btn secondary" href="<?= BASE_URL ?>/customer/dashboard">Back</a>
        <button class="btn primary" onclick="window.print()">Print Invoice</button>
    </div>

    <div class="invoice-card">

        <div class="invoice-header">
            <div>
                <h1>GlobeTrek Adventures</h1>
                <p>Premium Sri Lankan Travel Planning Platform</p>
            </div>

            <div class="invoice-meta">
                <h2>Invoice</h2>
                <p><strong>#GT-<?= str_pad($booking['id'], 5, '0', STR_PAD_LEFT) ?></strong></p>
                <p><?= date('Y-m-d') ?></p>
            </div>
        </div>

        <div class="invoice-section-grid">
            <div class="invoice-box">
                <h3>Customer</h3>
                <p><?= e($booking['user_name'] ?? '') ?></p>
                <p><?= e($booking['user_email'] ?? '') ?></p>
            </div>

            <div class="invoice-box">
                <h3>Trip</h3>
                <p><?= e($booking['package_title'] ?? '') ?></p>
                <p><?= e($booking['destination'] ?? '') ?> • <?= e($booking['travel_date'] ?? '') ?></p>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Persons</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?= e($booking['package_title'] ?? '') ?></td>
                    <td><?= e($booking['persons'] ?? 0) ?></td>
                    <td><?= e($booking['payment_method'] ?? 'Pending') ?></td>
                    <td><?= e($booking['status'] ?? 'Pending') ?></td>
                    <td>LKR <?= number_format((float)($booking['total_amount'] ?? 0), 2) ?></td>
                </tr>

                <tr class="invoice-total-row">
                    <td colspan="4">Grand Total</td>
                    <td>LKR <?= number_format((float)($booking['total_amount'] ?? 0), 2) ?></td>
                </tr>
            </tbody>
        </table>

        <?php if (!empty($payment)): ?>
            <div class="invoice-note">
                Card payment recorded using card ending <?= e($payment['card_last4'] ?? '') ?>.
            </div>
        <?php endif; ?>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>