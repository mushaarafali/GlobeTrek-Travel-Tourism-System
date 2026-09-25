
<?php require __DIR__ . '/../layouts/head.php';
 ?>

<?php $role = $_SESSION['user']['role']; ?>

<section class="edit-package-page">

    <div class="edit-package-wrapper">

        <!-- LEFT IMAGE PREVIEW -->
        <div class="edit-package-preview">
            <h2>Package Preview</h2>

            <img 
                src="<?= BASE_URL ?>/assets/images/<?= e($package['image']) ?>" alt="<?= e($package['title']) ?>">

            <h3><?= e($package['title']) ?></h3>
            <p><?= e($package['destination']) ?></p>

            <div class="edit-price-box">
                LKR <?= number_format($package['price'], 2) ?>
            </div>
        </div>

        <!-- RIGHT EDIT FORM -->
        <div class="edit-package-form-card">

            <h1>Edit Package</h1>

            <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/<?= $role ?>/updatePackage/<?= e($package['id']) ?>" autocomplete="off">

                <label>Package Title</label>
                <input type="text" name="title" value="<?= e($package['title']) ?>" required>

                <label>Destination</label>
                <input type="text" name="destination" value="<?= e($package['destination']) ?>" required>

                <label>Category</label>
                <input type="text" name="category" value="<?= e($package['category']) ?>" required>

                <label>Days</label>
                <input type="number" name="days" value="<?= e($package['days']) ?>" required>

                <label>Price (LKR)</label>
                <input type="number" name="price" value="<?= e($package['price']) ?>" required>



                <label>Upload New Image</label>
                <input type="file" name="image_file" accept="image/*">

                <label>Current / Custom Image File Name</label>
                <input type="text" name="image" value="<?= e($package['image']) ?>">

                <label>Description</label>
                <textarea name="description" rows="5" required><?= e($package['description']) ?></textarea>

                <button class="btn primary" type="submit">Update Package</button>

            </form>

        </div>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>