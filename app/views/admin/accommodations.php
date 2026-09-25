<?php require __DIR__ . '/../layouts/head.php'; ?>

<?php
$saveAction = BASE_URL . '/admin/saveAccommodation';
$editBase   = BASE_URL . '/admin/editAccommodation/';

?>

<section class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Stay Management</span>
            <h1>Manage Accommodations</h1>
            <p>Add and edit hotel and room options for travel packages.</p>
        </div>
    </div>

    <form class="admin-form-card package-form-grid"
          method="post"
          action="<?= $saveAction ?>"
          autocomplete="off">

        <div class="form-group">
            <label>Select Package</label>

            <select name="package_id" required>
                <option value="">Choose Package</option>

                <?php foreach (($packages ?? []) as $p): ?>
                    <option value="<?= e($p['package_id']) ?>">
                        <?= e($p['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Hotel / Resort Name</label>
            <input type="text" name="hotel_name" required>
        </div>

        <div class="form-group">
            <label>Room Type</label>

            <select name="room_type" required>
                <option value="">Choose Room Type</option>
                <option>Single Room</option>
                <option>Double Room</option>
                <option>Family Room</option>
                <option>VIP Suite</option>
            </select>
        </div>

        <div class="form-group">
            <label>Room Price</label>
            <input type="number" name="room_price" min="0" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Maximum People</label>
            <input type="number" name="max_people" min="1" required>
        </div>


        <div class="form-group">
            <label>Status</label>

            <select name="status">
                <option>Available</option>
                <option>Unavailable</option>
            </select>
        </div>

        <div class="form-group full-span">
            <label>Description</label>

            <textarea
                name="description"
                rows="4"
                placeholder="Room description..."
            ></textarea>
        </div>

        <div class="form-group full-span">
            <button class="btn primary" type="submit">
                Add Accommodation
            </button>
        </div>

    </form>

    <div class="table-card admin-table-card">

        <h2>Available Accommodations</h2>

        <table class="table">

            <thead>
                <tr>
                    <th>Package</th>
                    <th>Hotel</th>
                    <th>Room Type</th>
                    <th>Max People</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($items)): ?>

                    <?php foreach ($items as $i): ?>

                        <tr>

                            <td>
                                <?= e($i['package_title'] ?? '') ?>
                            </td>

                            <td>
                                <?= e($i['hotel_name'] ?? '') ?>
                            </td>

                            <td>
                                <?= e($i['room_type'] ?? '') ?>
                            </td>

                            <td>
                                <?= e($i['max_people'] ?? '') ?>
                            </td>

                            <td>
                                LKR <?= number_format((float)($i['room_price'] ?? 0)) ?>
                            </td>

                            <td>
                                <?= e($i['status'] ?? '') ?>
                            </td>

                            <td class="table-actions">

                                <a
                                    href="<?= $editBase . e($i['id']) ?>"
                                    class="btn small"
                                >
                                    Edit
                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/deleteAccommodation/<?= e($i['id']) ?>"
                                    class="btn small danger"
                                    onclick="return confirm('Delete this accommodation?');"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" style="text-align:center;">
                            No accommodation records found.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>