<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="customer-premium-dashboard">

    <!-- HERO -->
    <div class="dashboard-hero">

        <span class="dashboard-mini-title">
            Customer Portal
        </span>

        <h1>
            Customer Dashboard
        </h1>

        <p>
            Welcome back,
            <?= e($_SESSION['user']['name'] ?? 'Traveler') ?>.
            Manage your bookings, inquiries, and customized trip requests.
        </p>

    </div>

    <!-- TOP BUTTON -->
    <div class="dashboard-top-action">

        <a
            href="<?= BASE_URL ?>/package/index"
            class="book-trip-btn"
        >
            Book New Trip
        </a>

    </div>

    <!-- STATUS CARDS -->
    <div class="customer-dashboard-grid">

        <a
            class="dashboard-action-card premium-card"
            href="<?= BASE_URL ?>/customer/customTripStatus"
        >

            <div class="dashboard-card-icon trip-icon">
                <i class="fa-solid fa-route"></i>
            </div>

            <div class="dashboard-card-content">
                <h3>Customized Trip Status</h3>
            </div>

        </a>

        <a
            class="dashboard-action-card premium-card"
            href="<?= BASE_URL ?>/customer/inquiryStatus"
        >

            <div class="dashboard-card-icon inquiry-icon">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>

            <div class="dashboard-card-content">
                <h3>Inquiry Status</h3>
            </div>

        </a>

    </div>

    <!-- BOOKINGS -->
    <?php if (!empty($bookings)): ?>

        <div class="booking-list-premium">

            <?php foreach ($bookings as $booking): ?>

                <?php

                    $rawStatus = strtolower(trim($booking['status'] ?? 'pending'));

                    if (
                        $rawStatus === 'confirmed' ||
                        $rawStatus === 'approved'
                    ) {

                        $displayStatus = 'Approved';
                        $statusClass = 'status-approved';

                    } elseif (
                        $rawStatus === 'cancelled' ||
                        $rawStatus === 'canceled'
                    ) {

                        $displayStatus = 'Cancelled';
                        $statusClass = 'status-cancelled';

                    } elseif ($rawStatus === 'paid') {

                        $displayStatus = 'Paid';
                        $statusClass = 'status-paid';

                    } else {

                        $displayStatus = 'Pending';
                        $statusClass = 'status-pending';
                    }

                ?>

                <div class="booking-item-premium">

                    <!-- LEFT -->
                    <div class="booking-left">

                        <h3>
                            <?= e($booking['package_title'] ?? 'Travel Package') ?>
                        </h3>

                        <div class="booking-meta-grid">

                            <p>
                                <strong>Date:</strong>
                                <?= e($booking['travel_date'] ?? 'N/A') ?>
                            </p>

                            <p>
                                <strong>Persons:</strong>
                                <?= e($booking['persons'] ?? 0) ?>
                            </p>

                            <p>
                                <strong>Total:</strong>
                                LKR
                                <?= number_format((float)($booking['total_amount'] ?? 0), 2) ?>
                            </p>

                        </div>

                    </div>

                    <!-- CENTER -->
                    <div class="booking-center-actions">

                        <a
                            class="btn-invoice"
                            href="<?= BASE_URL ?>/payment/invoice/<?= $booking['id'] ?>"
                        >
                            <i class="fa-solid fa-file-invoice"></i>
                            Invoice
                        </a>

                        <?php if ($rawStatus === 'pending'): ?>

                            <a
                                class="btn-cancel"
                                href="<?= BASE_URL ?>/customer/cancelBooking/<?= $booking['id'] ?>"
                                onclick="return confirm('Are you sure you want to cancel this booking?');"
                            >
                                <i class="fa-solid fa-xmark"></i>
                                Cancel
                            </a>

                        <?php endif; ?>

                    </div>

                    <!-- RIGHT -->
                    <div class="booking-right-status">

                        <span class="booking-status-badge <?= $statusClass ?>">
                            <?= $displayStatus ?>
                        </span>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <!-- EMPTY -->
        <div class="empty-booking-box">

            <h3>
                No Bookings Yet
            </h3>

            <p>
                Start exploring amazing destinations and book your first trip.
            </p>

            <a
                href="<?= BASE_URL ?>/package/index"
                class="book-trip-btn"
            >
                Explore Packages
            </a>

        </div>

    <?php endif; ?>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>