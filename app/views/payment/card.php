<?php require __DIR__ . '/../layouts/head.php'; ?>

<?php

$persons = (int)($booking['persons'] ?? 1);

$days = (int)($booking['days'] ?? 1);
$days = max($days, 1);

$nights = max($days - 1, 0);

$packagePrice = (float)($booking['price'] ?? $booking['package_price'] ?? 0);

$roomPrice = (float)($booking['room_price'] ?? $booking['price_per_night'] ?? 0);

$transportPrice = (float)($booking['price_per_day'] ?? 0);

$packageTotal = $packagePrice * $persons;

$accommodationTotal = $roomPrice * $persons * $nights;

$transportTotal = $transportPrice * $days;

$grandTotal = $packageTotal + $accommodationTotal + $transportTotal;

?>

<section class="payment-hero">

    <div class="payment-badge">
        Secure Online Payment
    </div>

    <h1>Card Payment</h1>

    <p>
        Complete your booking securely using your debit or credit card.<br>
        GlobeTrek Adventures ensures fast, encrypted, and reliable payment processing.
    </p>

</section>

<section class="payment-card-section">

    <div class="payment-form-card">
        <h2>Payment Details</h2>

        <?php if(isset($error)): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/payment/store" autocomplete="off">
            <input type="hidden" name="booking_id" value="<?= e($booking['id']) ?>">

            <label>Card Number</label>
            <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>

            <label>Card Holder Name</label>
            <input type="text" name="card_holder" placeholder="John Doe" required>

            <div class="payment-row">
                <div>
                    <label>Expiry Date</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                </div>

                <div>
                    <label>CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
                </div>
            </div>

            <button class="payment-btn" type="submit">Pay Now</button>
        </form>
    </div>

    <div class="payment-summary-card">
        <h2>Order Summary</h2>

        <p><strong>Package:</strong> <?= e($booking['package_title'] ?? $booking['title'] ?? 'Selected Package') ?></p>
        <p><strong>Travel Date:</strong> <?= e($booking['travel_date'] ?? '') ?></p>
        <p><strong>Travelers:</strong> <?= e($persons) ?></p>
        <p><strong>Days:</strong> <?= e($days) ?> | <strong>Nights:</strong> <?= e($nights) ?></p>

        <hr>

        <div class="summary-line">
            <span>Package Total</span>
            <strong>
                LKR <?= number_format($packagePrice, 2) ?> × <?= e($persons) ?> persons
                = LKR <?= number_format($packageTotal, 2) ?>
            </strong>
        </div>
        <div class="summary-line">
    <span>Accommodation Total</span>
    <strong>
        <?php if (($accommodationTotal ?? 0) > 0): ?>
            LKR <?= number_format($roomPrice, 2) ?>
            × <?= e($persons) ?> persons
            × <?= e($nights) ?> nights
            =
            LKR <?= number_format($accommodationTotal, 2) ?>
        <?php else: ?>
            Not selected
        <?php endif; ?>
    </strong>
</div>


        <div class="summary-line">
            <span>Transport Total</span>
            <strong>
                <?php if ($transportTotal > 0): ?>
                    LKR <?= number_format($transportPrice, 2) ?> × <?= e($days) ?> days
                    = LKR <?= number_format($transportTotal, 2) ?>
                <?php else: ?>
                    Not selected
                <?php endif; ?>
            </strong>
        </div>

        <hr>

        <div class="summary-total">
    Grand Total: LKR <?= number_format((float)($grandTotal ?? 0), 2) ?>
</div>
    </div>

</section>

<script src="<?= BASE_URL ?>/assets/js/payment.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>