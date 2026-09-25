<?php require __DIR__ . '/../layouts/head.php'; ?>

<?php
$isStaffArea = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'staff';
$statusBase = $isStaffArea ? BASE_URL . '/staff/status/' : BASE_URL . '/admin/status/';
?>

<section class="admin-page">
    <div class="admin-page-header">
        <div>
            <span>Booking Control</span>
            <h1>Manage Bookings</h1>
            <p>Review bookings, confirm customer travel plans, and send confirmation emails.</p>
        </div>
    </div>

    <div class="table-card admin-table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Package</th>
                    <th>Transport</th>
                    <th>Travel Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $b): ?>

                        <?php $status = strtolower($b['status'] ?? 'pending'); ?>

                        <tr>
                            <td><?= e($b['user_name'] ?? 'Unknown') ?></td>
                            <td><?= e($b['user_email'] ?? 'No email') ?></td>
                            <td><?= e($b['package_title'] ?? 'No package') ?></td>


                            <td>
                                <?= !empty($b['transport_name']) 
                                    ? e($b['transport_name']) 
                                    : 'Not selected' 
                                ?>
                            </td>

                            <td><?= e($b['travel_date'] ?? '-') ?></td>

                            <td>
                                LKR <?= number_format((float)($b['total_amount'] ?? 0), 2) ?>
                            </td>

                            <td>
                                <span class="status-badge <?= e($status) ?>">
                                    <?= e(ucfirst($b['status'] ?? 'Pending')) ?>
                                </span>
                            </td>

                            <td class="table-actions">
                                <?php if ($status === 'pending'): ?>

                                    <a
                                        class="table-link action-confirm"
                                        href="<?= $statusBase . e($b['id']) ?>/confirmed"
                                        onclick="return confirm('Confirm this booking?');"
                                    >
                                        Confirm + Email
                                    </a>

                                    <a
                                        class="table-link danger action-cancel"
                                        href="<?= $statusBase . e($b['id']) ?>/cancelled"
                                        onclick="return confirm('Cancel this booking?');"
                                    >
                                        Cancel
                                    </a>

                                <?php elseif ($status === 'confirmed' || $status === 'approved'): ?>

                                    <span class="booking-locked-badge confirmed-lock">
                                        Already Confirmed
                                    </span>

                                <?php elseif ($status === 'cancelled' || $status === 'canceled'): ?>

                                    <span class="booking-locked-badge cancelled-lock">
                                        Already Cancelled
                                    </span>

                                <?php elseif ($status === 'completed'): ?>

                                    <span class="booking-locked-badge confirmed-lock">
                                        Completed
                                    </span>

                                <?php else: ?>

                                    <span class="booking-locked-badge">
                                        No Action
                                    </span>

                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            No bookings found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>