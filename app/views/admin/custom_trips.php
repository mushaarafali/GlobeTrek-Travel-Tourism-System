<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="dashboard-section">

    <div class="page-head">
        <h1>Customized Trip Requests</h1>
        <p>Review, approve/reject, and reply to customer customized travel requests</p>
    </div>

    <div class="table-card">

        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Days</th>
                    <th>Persons</th>
                    <th>Budget</th>
                    <th>Status</th>
                    <th>Reply</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($trips)): ?>

                    <?php foreach ($trips as $trip): ?>

                        <?php
                            $status = strtolower($trip['status'] ?? 'pending');
                            $alreadyReplied = !empty($trip['staff_reply']);
                        ?>

                        <tr>
                            <td><?= e($trip['name'] ?? 'Customer') ?></td>
                            <td><?= e($trip['email'] ?? '-') ?></td>
                            <td><?= e($trip['destination'] ?? '-') ?></td>
                            <td><?= !empty($trip['travel_date']) ? date('M d, Y', strtotime($trip['travel_date'])) : '-' ?></td>
                            <td><?= e($trip['days'] ?? '-') ?></td>
                            <td><?= e($trip['persons'] ?? '-') ?></td>

                            <td>
                                <strong>LKR <?= number_format((float)($trip['budget'] ?? 0), 2) ?></strong>
                            </td>

                            <td>
                                <span class="status <?= e($status) ?>">
                                    <?= e($trip['status'] ?? 'Pending') ?>
                                </span>
                            </td>

                            <td>
                                <?= $alreadyReplied ? e($trip['staff_reply']) : 'No reply yet' ?>
                            </td>

                            <td>
                                <?php if ($status === 'pending' && !$alreadyReplied): ?>

                                    <form 
                                        action="<?= BASE_URL ?>/<?= $_SESSION['user']['role'] ?>/replyCustomTrip/<?= e($trip['id']) ?>" 
                                        method="POST"
                                        class="reply-form"
                                    >
                                        <select name="status" required>
                                            <option value="Pending" selected>Pending</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>

                                        <textarea 
                                            name="reply"
                                            placeholder="Write reply..."
                                            required
                                        ></textarea>

                                        <button type="submit">
                                            Send Reply
                                        </button>
                                    </form>

                                <?php elseif ($status === 'approved'): ?>

                                    <span class="trip-approved-badge">Approved Already</span>

                                <?php elseif ($status === 'rejected'): ?>

                                    <span class="trip-rejected-badge">Rejected Already</span>

                                <?php elseif ($alreadyReplied): ?>

                                    <span class="trip-locked-badge">Already Replied</span>

                                <?php else: ?>

                                    <span class="trip-locked-badge">No Action</span>

                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="10" class="empty-table-text">
                            No customized trip requests found.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>
        </table>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>