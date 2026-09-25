<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="dashboard-section">
    <div class="page-head">
        <h1>Manage Inquiries</h1>
        <p>Reply to customer inquiries. One inquiry can be replied only once.</p>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Reply</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($inquiries)): ?>
                    <?php foreach ($inquiries as $inquiry): ?>
                        <tr>
                            <td><?= e($inquiry['name'] ?? 'Customer') ?></td>
                            <td><?= e($inquiry['email'] ?? '-') ?></td>
                            <td><?= e($inquiry['subject'] ?? '-') ?></td>
                            <td><?= e($inquiry['message'] ?? '-') ?></td>

                            <td>
                                <?= !empty($inquiry['reply']) ? e($inquiry['reply']) : 'No reply yet' ?>
                            </td>

                            <td>
                                <?php if (empty($inquiry['reply'])): ?>
                                    <form
                                        method="POST"
                                        action="<?= BASE_URL ?>/<?= $_SESSION['user']['role'] ?>/replyInquiry/<?= e($inquiry['id']) ?>"
                                        class="reply-form"
                                    >
                                        <textarea name="reply" placeholder="Write reply..." required></textarea>

                                        <button type="submit">
                                            Send Reply
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="trip-locked-badge">
                                        Already Replied
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-table-text">
                            No inquiries found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>