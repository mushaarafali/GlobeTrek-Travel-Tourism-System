<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="auth-page">
    <div class="auth-card">
        <span class="auth-badge">OTP Recovery</span>
        <h1>Forgot Password</h1>
        <p class="muted">Enter your registered email. A 6 digit OTP will be sent.</p>

        <?php if(isset($error)): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/forgotPassword" method="POST" autocomplete="off">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter registered email" autocomplete="off" required>

            <button class="btn primary full" type="submit">Send OTP</button>
        </form>

        <p class="auth-link">
            Remember password?
            <a href="<?= BASE_URL ?>/auth/login">Back to login</a>
        </p>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
