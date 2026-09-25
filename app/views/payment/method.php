<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="payment-hero">
    <h1>Select Payment Method</h1>
    <p>Choose a payment option to confirm your booking.</p>
</section>

<section class="payment-method-section">

    <div class="booking-summary-card">
        <h2>Booking Summary</h2>

        <p><strong>Package:</strong> <?= e($booking['package_title']) ?></p>
        <p><strong>Travel Date:</strong> <?= e($booking['travel_date']) ?></p>
        <p><strong>Persons:</strong> <?= e($booking['persons']) ?></p>

        <div class="payment-total-box">
            LKR <?= number_format($booking['total_amount'], 2) ?>
        </div>
    </div>

    <div class="payment-options-card">
        <h2>Payment Options</h2>

        <a class="payment-option" href="<?= BASE_URL ?>/payment/card/<?= e($booking['id']) ?>">
            <i class="fa-solid fa-credit-card"></i>
            <div>
                <strong>Pay by Card</strong>
                
            </div>
        </a>

        <a class="payment-option" href="<?= BASE_URL ?>/payment/cash/<?= e($booking['id']) ?>">
            <i class="fa-solid fa-hand-holding-dollar"></i>
            <div>
                <strong>Direct Payment</strong>
                
            </div>
        </a>
    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>