<?php require __DIR__ . '/../layouts/head.php'; ?>

<?php
$roleBase = $_SESSION['user']['role'] ?? 'staff';
$updateAction = BASE_URL . '/' . $roleBase . '/updateTravelGuide/' . $guide['id'];
?>

<section class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Edit Guide</span>
            <h1>Edit Travel Guide</h1>
            <p>Update destination guide details.</p>
        </div>
    </div>

    <form 
        class="admin-form-card package-form-grid"
        method="post"
        enctype="multipart/form-data"
        action="<?= $updateAction ?>"
    >

        <div class="form-group">
            <label>Guide Title</label>
            <input name="title" value="<?= e($guide['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Location</label>
            <input name="location" value="<?= e($guide['location'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <input name="category" value="<?= e($guide['category'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Best Time</label>
            <input name="best_time" value="<?= e($guide['best_time'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Current Image Name</label>
            <input name="image" value="<?= e($guide['image'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Change Image</label>
            <input type="file" name="image_file" accept="image/*">
        </div>

        <div class="form-group full-span">
            <label>Description</label>
            <textarea name="description" rows="6" required><?= e($guide['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group full-span">
            <button class="btn primary" type="submit">
                Update Travel Guide
            </button>

            <a class="btn secondary" href="<?= BASE_URL ?>/<?= $roleBase ?>/travelGuides">
                Back
            </a>
        </div>

    </form>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>