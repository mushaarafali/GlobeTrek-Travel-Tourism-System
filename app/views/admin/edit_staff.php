<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="admin-page">
    <div class="admin-card">

        <div class="admin-card-head">
            <span class="admin-badge">Staff Panel</span>
            <h1>Edit Staff</h1>
            <p>Update staff account details.</p>
        </div>

        <form method="POST" action="<?= BASE_URL ?>/admin/updateStaff/<?= e($staff['id']) ?>">

            <label>Full Name</label>
            <input type="text" name="name" value="<?= e($staff['name']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= e($staff['email']) ?>" required>

            <div class="form-actions">
                <button class="btn primary">Update Staff</button>
                <a href="<?= BASE_URL ?>/admin/staff" class="btn secondary">Cancel</a>
            </div>

        </form>

    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>