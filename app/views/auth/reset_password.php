<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="auth-page">
    <div class="auth-card">
        <span class="auth-badge">Reset Password</span>
        <h1>Verify OTP</h1>
        <p class="muted">Enter the OTP and create a new password.</p>

        <?php if(isset($error)): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/resetPassword" method="POST" autocomplete="off">
            <label>Email Address</label>
            <input type="email" name="email" value="<?= e($email ?? '') ?>" placeholder="Enter email address" autocomplete="off" required>

            <label>OTP Code</label>
            <input type="text" name="otp" maxlength="6" pattern="[0-9]{6}" placeholder="Enter 6 digit OTP" autocomplete="off" required>

            <label>New Password</label>
            <div class="password-field">
                <input type="password" name="password" id="resetPassword" placeholder="Enter new password" autocomplete="new-password" required>
                <button type="button" class="toggle-password" data-target="resetPassword">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            <label>Confirm Password</label>
            <div class="password-field">
                <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm password" autocomplete="new-password" required>
                <button type="button" class="toggle-password" data-target="confirmPassword">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            <button class="btn primary full" type="submit">Reset Password</button>
        </form>

        <p class="auth-link">
            <a href="<?= BASE_URL ?>/auth/forgotPassword">Resend OTP</a>
        </p>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
