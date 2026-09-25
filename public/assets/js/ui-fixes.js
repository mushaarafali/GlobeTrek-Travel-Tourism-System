document.addEventListener('DOMContentLoaded', function () {
    const menuBtn = document.getElementById('menuBtn');
    const mainNav = document.getElementById('mainNav');

    if (menuBtn && mainNav) {
        menuBtn.addEventListener('click', function () {
            const isOpen = mainNav.classList.toggle('show');
            menuBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            menuBtn.innerHTML = isOpen
                ? '<i class="fa-solid fa-xmark"></i>'
                : '<i class="fa-solid fa-bars"></i>';
        });
    }

    function setupPasswordToggle(input, button) {
        if (!input || !button) return;

        button.addEventListener('click', function () {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.innerHTML = isPassword
                ? '<i class="fa-solid fa-eye-slash"></i>'
                : '<i class="fa-solid fa-eye"></i>';
        });
    }

    setupPasswordToggle(document.getElementById('password'), document.getElementById('togglePassword'));
    setupPasswordToggle(document.getElementById('confirm_password'), document.getElementById('toggleConfirmPassword'));
    setupPasswordToggle(document.getElementById('confirmPassword'), document.getElementById('toggleConfirmPassword'));

    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
        const targetSelector = button.getAttribute('data-toggle-password');
        const input = document.querySelector(targetSelector);
        setupPasswordToggle(input, button);
    });
});
