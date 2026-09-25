
<?php require __DIR__ . '/../layouts/head.php'; ?>

<?php
$isStaffArea = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'staff';

$roleBase = $isStaffArea ? 'staff' : 'admin';

$packageAction = BASE_URL . '/' . $roleBase . '/savePackage';
$editBase = BASE_URL . '/' . $roleBase . '/editPackage/';
$deleteBase = BASE_URL . '/' . $roleBase . '/deletePackage/';
?>

<section class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Package Control</span>
            <h1>Manage Packages</h1>
            <p>Add, edit, and delete travel packages.</p>
        </div>
    </div>

    <form class="admin-form-card package-form-grid" method="post" enctype="multipart/form-data" action="<?= $packageAction ?>" autocomplete="off">

        <div class="form-group">
            <label>Package Title</label>
            <input name="title" placeholder="Sigiriya Heritage Escape" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label>Destination</label>
            <input name="destination" placeholder="Sigiriya, Dambulla" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <input name="category" placeholder="Culture / Beach / Nature" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label>Days</label>
            <input type="number" name="days" min="1" placeholder="3" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" name="price" min="1" step="0.01" placeholder="42000" autocomplete="off" required>
        </div>



        <div class="form-group">
            <label>Package Image Upload</label>
            <input type="file" name="image_file" accept="image/*">
        </div>

        <div class="form-group">
            <label>Image File Name (optional)</label>
            <input name="image" placeholder="default-package.jpg" autocomplete="off">
        </div>

        <div class="form-group full-span">
            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Write package description..." required></textarea>
        </div>

        <div class="form-group full-span">
            <button class="btn primary" type="submit">Add Package</button>
        </div>

    </form>

    <div class="table-card admin-table-card">
        <h2>Available Packages</h2>

        <table class="table">
            <tr>
                <th>Title</th>
                <th>Destination</th>
                <th>Category</th>
                <th>Days</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php foreach($packages as $p): ?>
                <tr>
                    <td><?= e($p['title']) ?></td>
                    <td><?= e($p['destination']) ?></td>
                    <td><?= e($p['category']) ?></td>
                    <td><?= e($p['days']) ?></td>
                    <td>LKR <?= number_format($p['price']) ?></td>
                    <td>
                        <a 
                            href="<?= $editBase . e($p['package_id']) ?>" 
                            class="btn small">
                            Edit
                        </a>

                        <a 
                            href="<?= $deleteBase . e($p['package_id']) ?>" 
                            class="btn small danger"
                            onclick="return confirm('Are you sure you want to delete this package?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>