<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="admin-page">
    <div class="admin-page-header">
        <div>
            <span>Customer Control</span>
            <h1>Registered Customers</h1>
            <p>View registered customer information.</p>
        </div>
    </div>

    <div class="table-card admin-table-card">
        <h2>Customer List</h2>

        <table class="table">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created</th>
            </tr>

            <?php foreach($customers as $c): ?>
                <tr>
                    <td><?= e($c['name']) ?></td>
                    <td><?= e($c['email']) ?></td>
                    <td><?= e($c['phone'] ?? '-') ?></td>
                    <td><?= e($c['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>