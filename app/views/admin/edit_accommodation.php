<?php require __DIR__ . '/../layouts/head.php'; ?>

<?php
$role = $_SESSION['user']['role'] ?? 'admin';

if (empty($item) || !is_array($item)) {
    echo '<section class="admin-page"><div class="admin-page-header">';
    echo '<h1>Accommodation Not Found</h1>';
    echo '<p>Invalid accommodation ID or missing data.</p>';
    echo '<a class="btn primary" href="' . BASE_URL . '/' . e($role) . '/accommodations">Back</a>';
    echo '</div></section>';
    require __DIR__ . '/../layouts/footer.php';
    exit;
}

$updateAction = BASE_URL . '/' . e($role) . '/updateAccommodation/' . e($item['id']);
?>

<section class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Stay Management</span>
            <h1>Edit Accommodation</h1>
            <p>Update hotel, room type, price, and availability.</p>
        </div>
    </div>

    <form class="admin-form-card package-form-grid"
          method="post"
          action="<?= $updateAction ?>"
          autocomplete="off">

        <div class="form-group">
            <label>Select Package</label>

            <select name="package_id" required>
                <option value="">Choose Package</option>

                <?php foreach (($packages ?? []) as $p): ?>
                    <option value="<?= e($p['package_id']) ?>"
                        <?= ((int)$p['package_id'] === (int)$item['package_id']) ? 'selected' : '' ?>>
                        <?= e($p['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Hotel / Resort Name</label>
            <input
                type="text"
                name="hotel_name"
                value="<?= e($item['hotel_name'] ?? '') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Room Type</label>

            <select name="room_type" required>
                <?php
                $roomTypes = ['Single Room', 'Double Room', 'Family Room', 'VIP Suite'];
                foreach ($roomTypes as $type):
                ?>
                    <option value="<?= e($type) ?>"
                        <?= (($item['room_type'] ?? '') === $type) ? 'selected' : '' ?>>
                        <?= e($type) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Room Price</label>
            <input
                type="number"
                name="room_price"
                min="0"
                step="0.01"
                value="<?= e($item['room_price'] ?? 0) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Maximum People</label>
            <input
                type="number"
                name="max_people"
                min="1"
                value="<?= e($item['max_people'] ?? 1) ?>"
                required
            >
        </div>



        <div class="form-group">
            <label>Status</label>

            <select name="status">
                <option value="Available" <?= (($item['status'] ?? '') === 'Available') ? 'selected' : '' ?>>
                    Available
                </option>

                <option value="Unavailable" <?= (($item['status'] ?? '') === 'Unavailable') ? 'selected' : '' ?>>
                    Unavailable
                </option>
            </select>
        </div>

        <div class="form-group full-span">
            <label>Description</label>

            <textarea name="description" rows="4"><?= e($item['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group full-span">
            <button class="btn primary" type="submit">
                Update Accommodation
            </button>

            <a class="btn reset" href="<?= BASE_URL ?>/<?= e($role) ?>/accommodations">
                Cancel
            </a>
        </div>

    </form>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>