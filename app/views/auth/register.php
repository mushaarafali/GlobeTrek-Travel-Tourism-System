<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="auth-page">
    <div class="auth-card">

        <span class="auth-badge">Create Account</span>

        <h1>Register</h1>

        <p class="muted">
            Join GlobeTrek and start planning your trip.
        </p>

        <?php if (isset($error)): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form 
            id="registerForm"
            action="<?= BASE_URL ?>/auth/register"
            method="POST"
            autocomplete="off"
        >

            <!-- NAME -->
            <label>Full Name</label>

            <input
                type="text"
                name="name"
                placeholder="Enter full name"
                required
                autocomplete="off"
            >

            <!-- EMAIL -->
            <label>Email</label>

            <input
                type="email"
                name="email"
                id="email"
                placeholder="Enter email address"
                required
                autocomplete="off"
            >

            <small
                id="emailError"
                style="color:#dc2626; display:none; font-weight:700;"
            >
                Email already exists
            </small>

            <!-- PHONE -->
            <label>Phone Number</label>

            <input type="tel" name="phone" id="phone" required maxlength="15" placeholder="0771234567 or +94771234567">

            <!-- PASSWORD -->
            <label>Password</label>

            <div class="password-wrap">

                <input
                    type="password"
                    name="password"
                    id="registerPassword"
                    placeholder="Minimum 8 characters, 1 uppercase, 1 symbol"
                    required
                    minlength="8"
                    pattern="^(?=.*[A-Z])(?=.*[\W_]).{8,}$"
                    title="Password must be at least 8 characters, include 1 uppercase letter and 1 symbol"
                    autocomplete="new-password"
                >

                <button
                    type="button"
                    class="password-toggle-btn"
                    id="registerPasswordToggle"
                    aria-label="Toggle password"
                >
                    <i class="fa-solid fa-eye"></i>
                </button>

            </div>

            <small
                id="passwordError"
                style="color:#dc2626; display:none; font-weight:700;"
            >
                Password must be 8+ characters, include 1 capital letter & 1 symbol
            </small>

            <!-- SUBMIT -->
            <button class="btn primary full" type="submit">
                Register
            </button>

        </form>

        <p class="auth-link">
            Already have an account?
            <a href="<?= BASE_URL ?>/auth/login">
                Login here
            </a>
        </p>

    </div>
</section>

<style>
.password-wrap {
    position: relative;
    width: 100%;
}

.password-wrap input {
    width: 100%;
    padding-right: 60px !important;
}

.password-toggle-btn {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);

    width: 40px;
    height: 40px;

    border: none;
    outline: none;

    border-radius: 10px;

    background: transparent;

    color: #475569;

    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    transition: 0.3s ease;
}

.password-toggle-btn:hover {
    color: #0f172a;
    background: rgba(15, 23, 42, 0.06);
}

.password-toggle-btn i {
    font-size: 16px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const registerForm = document.getElementById('registerForm');

    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');

    const passwordInput = document.getElementById('registerPassword');
    const passwordError = document.getElementById('passwordError');

    const passwordToggle = document.getElementById('registerPasswordToggle');

    let emailExists = false;

    /* ===============================
       EMAIL CHECK
    =============================== */
    emailInput.addEventListener('blur', function () {

        const email = emailInput.value.trim();

        if (email.length < 5) {
            emailExists = false;
            emailError.style.display = 'none';
            return;
        }

        fetch('<?= BASE_URL ?>/auth/checkEmail?email=' + encodeURIComponent(email))
            .then(response => response.json())
            .then(data => {

                emailExists = data.exists;

                if (emailExists) {

                    emailError.style.display = 'block';

                    emailInput.style.borderColor = '#dc2626';

                } else {

                    emailError.style.display = 'none';

                    emailInput.style.borderColor = '#16a34a';
                }
            })
            .catch(() => {
                emailExists = false;
            });
    });

    /* ===============================
       PASSWORD VALIDATION
    =============================== */
    passwordInput.addEventListener('input', function () {

        const password = passwordInput.value;

        const regex = /^(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

        if (!regex.test(password)) {

            passwordError.style.display = 'block';

            passwordInput.style.borderColor = '#dc2626';

        } else {

            passwordError.style.display = 'none';

            passwordInput.style.borderColor = '#16a34a';
        }
    });

    /* ===============================
       PASSWORD TOGGLE
    =============================== */
    passwordToggle.addEventListener('click', function () {

        const icon = passwordToggle.querySelector('i');

        if (passwordInput.type === 'password') {

            passwordInput.type = 'text';

            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');

        } else {

            passwordInput.type = 'password';

            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    /* ===============================
       FORM SUBMIT
    =============================== */
    registerForm.addEventListener('submit', function (e) {

        const password = passwordInput.value;

        const regex = /^(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

        if (emailExists) {

            e.preventDefault();

            emailError.style.display = 'block';

            emailInput.focus();

            return;
        }

        if (!regex.test(password)) {

            e.preventDefault();

            passwordError.style.display = 'block';

            passwordInput.focus();
        }
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');

    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9+]/g, '');

            if ((this.value.match(/\+/g) || []).length > 1) {
                this.value = this.value.replace(/\+/g, '');
            }

            if (this.value.indexOf('+') > 0) {
                this.value = this.value.replace(/\+/g, '');
            }

            if (this.value.startsWith('0')) {
                this.value = this.value.slice(0, 10);
            } else if (this.value.startsWith('+')) {
                this.value = this.value.slice(0, 15);
            }
        });

        phoneInput.addEventListener('input', function () {
            const sriLankaPattern = /^0[0-9]{9}$/;
            const internationalPattern = /^\+[1-9][0-9]{7,14}$/;

            if (
                sriLankaPattern.test(this.value) ||
                internationalPattern.test(this.value)
            ) {
                this.setCustomValidity('');
            } else {
                this.setCustomValidity('Enter valid Sri Lankan or international phone number');
            }
        });
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>