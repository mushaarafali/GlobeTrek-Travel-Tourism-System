<?php 
require __DIR__ . '/../layouts/head.php'; 
?>

<section class="contact-section">

    <div class="contact-grid">

        <div class="map-card">
            <iframe 
                src="https://www.google.com/maps?q=Negombo%20Sri%20Lanka&output=embed">
            </iframe>
        </div>

        <div class="contact-form-card">

            <h2>Get in to Touch</h2>

            <?php if (isset($success)): ?>
                <div class="alert success">
                    <?= e($success) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert error">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!isLoggedIn()): ?>
                <div class="login-box">
                    <p>Please login before submitting an inquiry.</p>
                </div>
            <?php endif; ?>

            <form 
                method="post" 
                action="<?= BASE_URL ?>/inquiry/store" 
                autocomplete="off"
            >

                <input 
                    type="text" 
                    name="subject" 
                    placeholder="Subject" 
                    required
                >

                <textarea 
                    name="message" 
                    placeholder="Message" 
                    required
                ></textarea>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>

                    <div class="login-box">
                        <p>Admin cannot submit inquiries.</p>
                    </div>

                <?php else: ?>

                    <button class="btn primary" type="submit">
                        Send Message
                    </button>

                <?php endif; ?>

            </form>

        </div>

    </div>

</section>

<?php 
require __DIR__ . '/../layouts/footer.php'; 
?>