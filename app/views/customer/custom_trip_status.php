<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="admin-page">
    <div class="admin-page-header">
        <div>
            <span>Customer Dashboard</span>
            <h1>Customized Trip Status</h1>
            <p>View admin or staff replies for your customized trip requests.</p>
        </div>
    </div>

    <div class="table-card admin-table-card">
        <table class="table">
            <tr>
                <th>Destination</th>
                <th>Days</th>
                <th>Budget</th>
                <th>Status</th>
                <th>Admin / Staff Reply</th>
                <th>Created Date</th>
            </tr>

            <?php if (!empty($trips)): ?>
                <?php foreach ($trips as $trip): ?>
                    <tr>
                        <td><?= htmlspecialchars($trip['destination'] ?? '') ?></td>
                        <td><?= htmlspecialchars($trip['days'] ?? '') ?></td>
                        <td>Rs. <?= number_format((float)($trip['budget'] ?? 0), 2) ?></td>
                        <td>
                            <span class="status-badge">
                                <?= htmlspecialchars($trip['status'] ?? 'Pending') ?>
                            </span>
                        </td>
                        <td><?= nl2br(htmlspecialchars($trip['reply'] ?? 'No reply yet')) ?></td>
                        <td><?= htmlspecialchars($trip['created_at'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">No customized trip requests found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>