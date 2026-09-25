<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="profile-page">

    <div class="profile-card">

        <div class="profile-header">
            <span>Customer Account</span>
            <h1>My Profile</h1>
            <p>Keep your contact details updated for booking confirmations.</p>
        </div>

        <?php if (!empty($_SESSION['flash']['success'])): ?>
            <div class="alert success">
                <?= $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash']['error'])): ?>
            <div class="alert error">
                <?= $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
            </div>
        <?php endif; ?>

        <form 
            method="post" 
            action="<?= BASE_URL ?>/customer/updateProfile" 
            autocomplete="off"
            class="profile-form"
        >

            <div class="form-group">
                <label>Full Name</label>
                <input 
                    name="name" 
                    value="<?= e($customer['name'] ?? '') ?>" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Email</label>
                <input 
                    type="email" 
                    name="email" 
                    value="<?= e($customer['email'] ?? '') ?>" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input 
                    name="phone" 
                    value="<?= e($customer['phone'] ?? '') ?>"
                    placeholder="07XXXXXXXX"
                >
            </div>

            <button class="btn primary" type="submit">
                Update Profile
            </button>

        </form>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>