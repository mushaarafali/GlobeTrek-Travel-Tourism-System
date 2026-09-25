<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="booking-page">
<div class="booking-container">

<form class="booking-form-card" method="post" action="<?= BASE_URL ?>/booking/store">

    <h1>Traveler Information</h1>

    <input type="hidden" name="package_id" value="<?= e($package['package_id']) ?>">

    <label>Full Name</label>
    <input
        type="text"
        name="full_name"
        value="<?= e($_SESSION['user']['name'] ?? '') ?>"
        required
    >

    <label>Email</label>
    <input
        type="email"
        name="email"
        value="<?= e($_SESSION['user']['email'] ?? '') ?>"
        required
    >

    <label>Phone Number</label>
    <input
        type="tel"
        name="phone"
        id="phone"
        required
        maxlength="15"
        placeholder="0771234567 or +94771234567"
    >

    <label>Travel Date</label>
    <input
        type="date"
        name="travel_date"
        required
        min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
    >

    <label>No of Travelers</label>
    <input
        type="number"
        name="persons"
        min="1"
        value="1"
        required
    >

    <label>Special Notes</label>
    <textarea name="custom_note"></textarea>

    <div class="booking-extra-grid">

<!-- ACCOMMODATION -->
<div class="booking-extra-card">

    <h4>Select Accommodation</h4>

    <?php if (!empty($accommodations)): ?>

        <select
            name="accommodation_id"
            class="premium-select"
            required
        >

            <option value="" disabled selected>
                Choose Accommodation
            </option>

            <?php foreach ($accommodations as $a): ?>

                <option value="<?= e($a['id']) ?>">

                    <?= e($a['hotel_name']) ?>

                    -

                    <?= e($a['room_type']) ?>

                    -

                    Max <?= e($a['max_people']) ?> Persons

                    -

                    LKR <?= number_format((float)($a['room_price'] ?? 0), 2) ?>

                    / Night

                </option>

            <?php endforeach; ?>

        </select>

    <?php else: ?>

        <div class="guide-charge-box">

            No accommodation options available for this package.

        </div>

    <?php endif; ?>

</div>

        <!-- TRANSPORT -->
        <div class="booking-extra-card">

            <h4>Transport Option</h4>

            <select
                name="transport_id"
                class="premium-select"
                required
            >

                <option value="" disabled selected>
                    Choose transport
                </option>

                <?php foreach (($transports ?? []) as $t): ?>

                    <option value="<?= e($t['id']) ?>">

                        <?= e($t['vehicle_type'] ?? 'Vehicle') ?>

                        |

                        <?= e($t['vehicle_no'] ?? '') ?>

                        (<?= e($t['seats'] ?? 0) ?> Seats)

                        -

                        LKR <?= number_format((float)($t['price_per_day'] ?? 0)) ?>

                        / day

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>

    <br>

    <button class="btn primary">
        Proceed to Payment
    </button>

</form>


<div class="package-summary-card">

<h2>Selected Package</h2>

<img src="<?= BASE_URL ?>/assets/images/<?= e($package['image']) ?>">

<h3><?= e($package['title']) ?></h3>

<p><?= e($package['description']) ?></p>

<p><strong>Destination:</strong> <?= e($package['destination']) ?></p>
<p><strong>Days:</strong> <?= e($package['days']) ?></p>

<div class="included-box">


    <div class="guide-section">

        <div class="included-title">
            <i class="fa-solid fa-user-group"></i>
            <strong>Need a Travel Guide?</strong>
        </div>

        <p>
            Professional local travel guides are available for your journey.
        </p>

        <div class="guide-charge-box">
            <i class="fa-solid fa-circle-info"></i>
            Additional charges apply for travel guide services.
        </div>

        <a href="tel:0754444789" class="guide-hotline-btn">
            <i class="fa-solid fa-phone"></i>
            Hotline : 075 4444 789
        </a>

    </div>

</div>
<div class="total-box">
    Package Price: LKR <?= number_format((float)$package['price']) ?>
</div>

</div>

</div>
</section>
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