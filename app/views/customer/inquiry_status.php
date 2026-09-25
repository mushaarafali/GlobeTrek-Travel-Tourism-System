<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="admin-page">
    <div class="admin-page-header">
        <div>
            <span>Customer Dashboard</span>
            <h1>Inquiries Status</h1>
            <p>View admin or staff replies for your submitted inquiries.</p>
        </div>
    </div>

    <div class="table-card admin-table-card">
        <table class="table">
            <tr>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>Admin / Staff Reply</th>
                <th>Created Date</th>
            </tr>

            <?php if (!empty($inquiries)): ?>
                <?php foreach ($inquiries as $inq): ?>
                    <tr>
                        <td><?= htmlspecialchars($inq['subject'] ?? '') ?></td>
                        <td><?= nl2br(htmlspecialchars($inq['message'] ?? '')) ?></td>
                        <td>
                            <span class="status-badge">
                                <?= htmlspecialchars($inq['status'] ?? 'Pending') ?>
                            </span>
                        </td>
                        <td><?= nl2br(htmlspecialchars($inq['reply'] ?? 'No reply yet')) ?></td>
                        <td><?= htmlspecialchars($inq['created_at'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No inquiries found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>