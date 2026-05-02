<?php
require_once(__DIR__ . '/../../../core/globals.php');
ob_start();
?>
<div class="mb-4">
  <h3 style="font-family:'Noto Serif'">Edit User</h3>
  <p class="text-muted">Update user information and role.</p>
</div>
<div class="card card-custom p-4 mb-4">
  <form class="row g-4" action="<?php echo base_path('admin/users?id=' . $editUser->id); ?>" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">

    <div class="col-12">
      <label class="form-label">Profile Picture</label>
      <div class="d-flex align-items-center gap-4">
        <?php if (!empty($editUser->profile_picture_link)): ?>
          <img src="<?php echo base_path('storage/user-images/' . htmlspecialchars($editUser->profile_picture_link)); ?>"
            class="rounded-circle border shadow-sm"
            style="width:120px;height:120px;object-fit:cover;"
            alt="Profile picture">
        <?php else: ?>
          <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
            style="width:120px;height:120px;font-size:2.5rem;">
            👤
          </div>
        <?php endif; ?>
        <div>
          <input type="file" name="profile_image" accept="image/*" class="form-control form-control-sm" style="max-width:260px;">
          <input type="hidden" name="profile_picture_link" value="<?php echo htmlspecialchars($editUser->profile_picture_link ?? ''); ?>">
          <div class="text-muted small mt-1">Max 2MB · Leave empty to keep current image</div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Full Name</label>
      <input class="form-control" type="text" name="name"
        value="<?php echo htmlspecialchars($editUser->name); ?>" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input class="form-control" type="email" name="email"
        value="<?php echo htmlspecialchars($editUser->email); ?>" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">New Password <span class="text-muted small">(leave blank to keep current)</span></label>
      <input class="form-control" type="password" name="password" placeholder="••••••••">
    </div>

    <div class="col-md-6">
      <label class="form-label">Room</label>
      <input class="form-control" type="text" name="room"
        value="<?php echo htmlspecialchars($editUser->room ?? ''); ?>">
    </div>

    <div class="col-md-6">
      <label class="form-label">Role</label>
      <select class="form-select" name="role">
        <option value="USER" <?php echo strtoupper($editUser->role) === 'USER' ? 'selected' : ''; ?>>User</option>
        <option value="ADMIN" <?php echo strtoupper($editUser->role) === 'ADMIN' ? 'selected' : ''; ?>>Admin</option>
      </select>
    </div>

    <div class="col-12 text-end">
      <a href="<?php echo base_path('admin/users'); ?>" class="btn btn-outline-secondary me-2">Cancel</a>
      <button class="btn btn-success">Save Changes</button>
    </div>
  </form>
</div>
<?php
$content = ob_get_clean();
$title = "Edit User";
$activePage = "users";
require __DIR__ . '/../../layouts/main-layout.php';
?>
