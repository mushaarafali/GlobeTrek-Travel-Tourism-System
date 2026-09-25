<?php require __DIR__ . '/../layouts/head.php'; ?>
<?php $roleBase = ($_SESSION['user']['role'] ?? 'admin') === 'staff' ? 'staff' : 'admin'; ?>
<section class="admin-page">
    <div class="admin-page-header"><div><span>Edit Vehicle</span><h1>Update Transport</h1></div></div>
    <form class="admin-form-card package-form-grid" method="post" action="<?= BASE_URL ?>/<?= $roleBase ?>/updateTransport/<?= e($item['id']) ?>">
        <div class="form-group"><label>Vehicle Type</label><input name="vehicle_type" value="<?= e($item['vehicle_type']) ?>" required></div>
        <div class="form-group"><label>Vehicle No</label><input name="vehicle_no" value="<?= e($item['vehicle_no']) ?>"></div>
        <div class="form-group"><label>Seats</label><input type="number" name="seats" min="1" value="<?= e($item['seats']) ?>"></div>
        <div class="form-group"><label>Price Per Day</label><input type="number" name="price_per_day" min="0" step="0.01" value="<?= e($item['price_per_day']) ?>" required></div>
        <div class="form-group"><label>Driver Name</label><input name="driver_name" value="<?= e($item['driver_name']) ?>"></div>
        <div class="form-group"><label>Driver Phone</label><input name="driver_phone" value="<?= e($item['driver_phone']) ?>"></div>
        <div class="form-group"><label>Status</label><select name="status"><?php foreach(['Available','On Trip','Maintenance'] as $s): ?><option <?= ($item['status']===$s?'selected':'') ?>><?= e($s) ?></option><?php endforeach; ?></select></div>
        <div class="form-group full-span"><button class="btn primary">Update Transport</button> <a class="btn ghost" href="<?= BASE_URL ?>/<?= $roleBase ?>/transport">Cancel</a></div>
    </form>
</section>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
