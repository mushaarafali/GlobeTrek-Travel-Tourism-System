<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="admin-page">
    <div class="admin-page-header">
        <div>
            <span>Customer Control</span>
            <h1>Manage Customers</h1>
            <p>View, edit and delete customers.</p>
        </div>
    </div>

    <div class="table-card admin-table-card">
        <h2>Registered Customers</h2>

        <table class="table">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created</th>
                <th>Action</th>
            </tr>

            <?php foreach($customers as $c): ?>
                <tr>
                    <td><?= e($c['name']) ?></td>
                    <td><?= e($c['email']) ?></td>
                    <td><?= e($c['phone'] ?? '-') ?></td>
                    <td><?= e($c['created_at']) ?></td>

                    <td>
                        <!-- EDIT -->
                        <a class="btn small"
                           href="<?= BASE_URL ?>/staff/editCustomer/<?= $c['id'] ?>">
                            Edit
                        </a>

                        <!-- DELETE -->
                        <a class="btn small danger"
                           href="<?= BASE_URL ?>/staff/deleteCustomer/<?= $c['id'] ?>"
                           onclick="return confirm('Delete this customer?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>