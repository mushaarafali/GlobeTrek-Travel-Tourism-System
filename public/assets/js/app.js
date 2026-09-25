const menuBtn = document.getElementById('menuBtn');
const mainNav = document.getElementById('mainNav');

if (menuBtn && mainNav) {
    menuBtn.addEventListener('click', () => {
        mainNav.classList.toggle('show');
        menuBtn.classList.toggle('active');
    });
}

document.querySelectorAll('.toggle-password').forEach((button) => {
    button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = button.querySelector('i');

        if (!input) {
            return;
        }

        if (input.type === 'password') {
            input.type = 'text';

            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            input.type = 'password';

            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    });
});
