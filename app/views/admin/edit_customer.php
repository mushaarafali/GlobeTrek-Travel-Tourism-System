<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="admin-page">
    <div class="admin-card">
        <div class="admin-card-head">
            <span class="admin-badge">Admin Panel</span>
            <h1>Edit Customer</h1>
            <p>Update customer profile information securely.</p>
        </div>

        <form method="POST" action="<?= BASE_URL ?>/admin/updateCustomer/<?= e($customer['id'] ?? '') ?>" autocomplete="off">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= e($customer['name'] ?? '') ?>" required>

            <label>Email Address</label>
            <input type="email" name="email" value="<?= e($customer['email'] ?? '') ?>" required>

            <label>Phone Number</label>
            <input type="text" name="phone" value="<?= e($customer['phone'] ?? '') ?>" placeholder="Enter phone number">

            <div class="form-actions">
                <button type="submit" class="btn primary">Update Customer</button>
                <a href="<?= BASE_URL ?>/admin/customers" class="btn secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
