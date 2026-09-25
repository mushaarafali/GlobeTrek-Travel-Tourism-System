<?php require __DIR__ . '/../layouts/head.php'; ?>
<?php
$roleBase = ($_SESSION['user']['role'] ?? 'admin') === 'staff' ? 'staff' : 'admin';
$saveAction = BASE_URL . '/' . $roleBase . '/saveTransport';
$editBase = BASE_URL . '/' . $roleBase . '/editTransport/';
$deleteBase = BASE_URL . '/' . $roleBase . '/deleteTransport/';
?>
<section class="admin-page">
    <div class="admin-page-header"><div><span>Travel Operations</span><h1>Transport Management</h1><p>Add, edit, and delete vehicle options used in customer bookings.</p></div></div>
    <form class="admin-form-card package-form-grid" method="post" action="<?= $saveAction ?>" autocomplete="off">
        <div class="form-group"><label>Vehicle Type</label><input name="vehicle_type" placeholder="Van / Car / Bus" required></div>
        <div class="form-group"><label>Vehicle No</label><input name="vehicle_no"></div>

        <div class="form-group"><label>Seats</label><input type="number" name="seats" min="1"></div>
        <div class="form-group"><label>Price Per Day</label><input type="number" name="price_per_day" min="0" step="0.01" required></div>
        <div class="form-group"><label>Driver Name</label><input name="driver_name"></div>
        <div class="form-group"><label>Driver Phone</label><input name="driver_phone"></div>
        <div class="form-group"><label>Status</label><select name="status"><option>Available</option><option>On Trip</option><option>Maintenance</option></select></div>
        <div class="form-group"><button class="btn primary">Add Transport</button></div>
    </form>
    <div class="table-card admin-table-card"><h2>Transport Fleet</h2><table class="table"><tr><th>Vehicle</th><th>No</th><th>Seats</th><th>Price</th><th>Driver</th><th>Status</th><th>Action</th></tr>
        <?php foreach(($items ?? []) as $i): ?><tr><td><?= e($i['vehicle_type']) ?></td><td><?= e($i['vehicle_no']) ?></td><td><?= e($i['seats']) ?></td><td>LKR <?= number_format($i['price_per_day']) ?></td><td><?= e($i['driver_name']) ?> / <?= e($i['driver_phone']) ?></td><td><?= e($i['status']) ?></td><td><a class="table-link" href="<?= $editBase . e($i['id']) ?>">Edit</a> 
            </td></tr><?php endforeach; ?>
    </table></div>
</section>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
