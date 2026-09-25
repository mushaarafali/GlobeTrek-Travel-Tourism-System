<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="auth-page">
    <div class="auth-card">
        <span class="auth-badge"> Welcome </span>
        <h1>Login</h1>
       
        <?php if(isset($error)): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/login" method="POST" autocomplete="off">
            <label>Email Address</label>
            <input type="email" name="email" id="email" placeholder="Enter email address" autocomplete="off" required>

            <label>Password</label>
            <div class="password-field">
                <input type="password" name="password" id="loginPassword" placeholder="Enter password" autocomplete="new-password" required>
                <button type="button" class="toggle-password" data-target="loginPassword">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        <p class="auth-link">
            <a href="<?= BASE_URL ?>/auth/forgotPassword">Forgot password?</a>
         </p>
            <button class="btn primary full" type="submit">Login</button>
        </form>

       

        <p class="auth-link">
            Don’t have an account?
            <a href="<?= BASE_URL ?>/auth/register">Register here</a>
        </p>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
