// =========================
// CARD FORMAT (xxxx xxxx xxxx xxxx)
// =========================
const cardInput = document.getElementById('card_number');

if (cardInput) {
    cardInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '').substring(0, 16);
        value = value.replace(/(.{4})/g, '$1 ').trim();
        this.value = value;
    });
}

// =========================
// EXPIRY FORMAT (MM/YY)
// =========================
const expiryInput = document.getElementById('expiry_date'); // FIXED

if (expiryInput) {
    expiryInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '').substring(0, 4);

        if (value.length >= 3) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }

        this.value = value;
    });
}

// =========================
// FORM VALIDATION
// =========================
const form = document.querySelector('form');

if (form) {
    form.addEventListener('submit', function (e) {

        const card = cardInput.value.replace(/\s/g, '');
        const expiry = expiryInput.value;
        const cvv = document.getElementById('cvv').value;

        // CARD MUST BE 16 DIGITS
        if (card.length !== 16) {
            alert('Card number must be 16 digits');
            e.preventDefault();
            return;
        }

        // EXPIRY VALIDATION
        const parts = expiry.split('/');

        if (parts.length !== 2) {
            alert('Invalid expiry format');
            e.preventDefault();
            return;
        }

        const month = parseInt(parts[0]);
        const year = parseInt('20' + parts[1]);

        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = now.getMonth() + 1;

        // MONTH CHECK (01–12)
        if (month < 1 || month > 12) {
            alert('Month must be between 01 and 12');
            e.preventDefault();
            return;
        }

        // EXPIRY CHECK
        if (year < currentYear || (year === currentYear && month < currentMonth)) {
            alert('Card expired');
            e.preventDefault();
            return;
        }

        // CVV CHECK
        if (cvv.length < 3 || cvv.length > 4) {
            alert('Invalid CVV');
            e.preventDefault();
            return;
        }

    });
}