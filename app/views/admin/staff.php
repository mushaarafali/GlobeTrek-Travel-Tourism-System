<?php $staff = $staff ?? []; ?>
<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="admin-page">
    <div class="admin-page-header">
        <div>
            <span>Admin Control</span>
            <h1>Manage Staff</h1>
            <p>Create, review, and remove travel staff accounts securely.</p>
        </div>
    </div>

    <div class="admin-two-column">
        <form class="admin-form-card" method="post" action="<?= BASE_URL ?>/admin/saveStaff" autocomplete="off">
            <h2>Add New Staff</h2>

            <div class="form-group">
                <label>Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    placeholder="Enter staff name" 
                    autocomplete="off" 
                    required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Enter staff email" 
                    autocomplete="off" 
                    required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-field">
                    <input 
                        type="password" 
                        name="password" 
                        id="staffPassword" 
                        placeholder="Minimum 6 characters" 
                        autocomplete="new-password" 
                        required>
                    <button type="button" class="toggle-password" data-target="staffPassword">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button class="btn primary full" type="submit">Create Staff</button>
        </form>

        <div class="table-card admin-table-card">
            <h2>Current Staff Accounts</h2>

            <table class="table">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>

                <?php foreach($staff as $member): ?>
                    <tr>
                        <td><?= e($member['name']) ?></td>
                        <td><?= e($member['email']) ?></td>
                        <td><?= e(date('Y-m-d', strtotime($member['created_at']))) ?></td>
                        <td class="table-actions">

                            <a class="action-edit"
                            href="<?= BASE_URL ?>/admin/editStaff/<?= e($member['id']) ?>">
                                Edit
                            </a>

                            <a class="action-delete"
                            href="<?= BASE_URL ?>/admin/deleteStaff/<?= e($member['id']) ?>"
                            onclick="return confirm('Delete this staff account?');">
                                Delete
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
