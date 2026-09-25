<?php require __DIR__ . '/../layouts/head.php'; ?>

<?php
$isStaffArea = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'staff';
$roleBase = $isStaffArea ? 'staff' : 'admin';

$saveAction = BASE_URL . '/' . $roleBase . '/saveTravelGuide';
$editBase = BASE_URL . '/' . $roleBase . '/editTravelGuide/';
$deleteBase = BASE_URL . '/' . $roleBase . '/deleteTravelGuide/';
?>

<section class="admin-page">

    <div class="admin-page-header">
        <div>
            <span>Destination Knowledge</span>
            <h1>Manage Travel Guides</h1>
            <p>Add Sri Lankan destination guides for customers to read before booking.</p>
        </div>
    </div>

    <form 
        class="admin-form-card package-form-grid"
        method="post"
        enctype="multipart/form-data"
        action="<?= $saveAction ?>"
        autocomplete="off"
    >

        <div class="form-group">
            <label>Guide Title</label>
            <input name="title" required>
        </div>

        <div class="form-group">
            <label>Location</label>
            <input name="location" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <input name="category" required>
        </div>

        <div class="form-group">
            <label>Best Time</label>
            <input name="best_time" placeholder="Example: All Season">
        </div>

        <div class="form-group">
            <label>Guide Image</label>
            <input type="file" name="image_file" accept="image/*">
        </div>

        <div class="form-group">
            <label>Image Name</label>
            <input name="image" placeholder="example.jpg">
        </div>

        <div class="form-group full-span">
            <label>Description</label>
            <textarea name="description" rows="5" required></textarea>
        </div>

        <div class="form-group full-span">
            <button class="btn primary" type="submit">
                Add Travel Guide
            </button>
        </div>

    </form>

    <div class="table-card admin-table-card">

        <h2>Travel Guide List</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Best Time</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($guides)): ?>

                    <?php foreach ($guides as $g): ?>
                        <tr>
                            <td><?= e($g['title'] ?? '') ?></td>
                            <td><?= e($g['location'] ?? '') ?></td>
                            <td><?= e($g['category'] ?? '') ?></td>
                            <td><?= e($g['best_time'] ?? 'N/A') ?></td>

                            <td class="table-actions">

                                <a 
                                    class="action-edit"
                                    href="<?= $editBase . e($g['id']) ?>"
                                >
                                    Edit
                                </a>

                                <a 
                                    class="action-delete"
                                    href="<?= $deleteBase . e($g['id']) ?>"
                                    onclick="return confirm('Delete this guide?')"
                                >
                                    Delete
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="empty-table-text">
                            No travel guides found.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>
        </table>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>