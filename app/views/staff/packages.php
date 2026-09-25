<?php require __DIR__ . '/../layouts/head.php'; ?>

<?php
$roleBase = 'staff';

$packageAction = BASE_URL . '/staff/savePackage';
$editBase      = BASE_URL . '/staff/editPackage/';
?>

<section class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Package Control</span>
            <h1>Manage Packages</h1>
            <p>Add and edit travel packages.</p>
        </div>
    </div>

    <form class="admin-form-card package-form-grid"
          method="post"
          enctype="multipart/form-data"
          action="<?= e($packageAction) ?>"
          autocomplete="off">

        <div class="form-group">
            <label>Package Title</label>
            <input type="text" name="title" placeholder="Sigiriya Heritage Escape" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label>Destination</label>
            <input type="text" name="destination" placeholder="Sigiriya, Dambulla" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" placeholder="Culture / Beach / Nature" autocomplete="off" required>
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
            <label>Image File Name Optional</label>
            <input type="text" name="image" placeholder="default-package.jpg" autocomplete="off">
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
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Destination</th>
                    <th>Category</th>
                    <th>Days</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $p): ?>
                        <tr>
                            <td><?= e($p['title'] ?? '') ?></td>
                            <td><?= e($p['destination'] ?? '') ?></td>
                            <td><?= e($p['category'] ?? '') ?></td>
                            <td><?= e($p['days'] ?? '') ?></td>
                            <td>LKR <?= number_format((float)($p['price'] ?? 0)) ?></td>
                            <td class="table-actions">
                                <a
                                    href="<?= $editBase . e($p['id'] ?? '') ?>"
                                    class="btn small">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            No packages found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>