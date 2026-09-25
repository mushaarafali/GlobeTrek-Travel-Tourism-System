<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="booking-success-page">

    <h1>Booking Submitted<br>Successfully</h1>

    <p>
        Your travel request has been successfully submitted. </br>
        Our staff will review and approve it, and you will receive a confirmation via email. </br>
        Thank you for choosing us.
        

    </p>

    <div class="booking-confirm-card">
        <h2>Booking Confirmation</h2>

        <p><strong>Package:</strong> <?= e($booking['package_title']) ?></p>
        <p><strong>Travel Date:</strong> <?= e($booking['travel_date']) ?></p>
        <p><strong>Travelers:</strong> <?= e($booking['persons']) ?></p>
        <p><strong>Amount:</strong> LKR <?= number_format($booking['total_amount'], 2) ?></p>
        <p><strong>Payment Method:</strong> <?= e($booking['payment_method'] ?? 'Pending') ?></p>
        <p><strong>Payment Status:</strong> <?= ($booking['status'] === 'Paid') ? 'Paid' : 'Pending' ?></p>
        <p><strong>Booking Status:</strong> <?= e($booking['status']) ?></p>
    </div>

    <div class="success-actions">
        <a class="btn primary" href="<?= BASE_URL ?>/customer/dashboard">Go to Dashboard</a>
        <a class="btn light" href="<?= BASE_URL ?>/package/index">Book Another Tour</a>
    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>