<?php require __DIR__ . '/../layouts/head.php'; ?>
<section class="admin-page">
    <div class="admin-page-header"><div><span>Customer Portal</span><h1>My Booking History</h1><p>Track booking status, print invoices, or cancel eligible bookings.</p></div></div>
    <div class="table-card admin-table-card">
        <table class="table">
            <tr><th>Package</th><th>Transport</th><th>Date</th><th>Persons</th><th>Total</th><th>Status</th><th>Actions</th></tr>
            <?php if (!empty($bookings)): foreach ($bookings as $b): ?>
                <tr>
                    <td><?= e($b['package_title']) ?></td>
                    <td><?= !empty($b['transport_vehicle_type']) ? e($b['transport_vehicle_type']) : 'Not selected' ?></td>
                    <td><?= e($b['travel_date']) ?></td>
                    <td><?= e($b['persons']) ?></td>
                    <td>LKR <?= number_format($b['total_amount']) ?></td>
                    <td><span class="status-badge <?= strtolower(e($b['status'])) ?>"><?= e($b['status']) ?></span></td>
                    <td>
                        <a class="table-link" href="<?= BASE_URL ?>/payment/invoice/<?= e($b['id']) ?>">Invoice</a>
                        <?php if (in_array($b['status'], ['Pending','Confirmed'], true)): ?>
                            <a class="table-link danger" href="<?= BASE_URL ?>/booking/cancel/<?= e($b['id']) ?>" onclick="return confirm('Cancel this booking?')">Cancel</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="8">No bookings found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
