<?php
require_once("core/globals.php");
ob_start();
?>
  <div class="mb-4">
    <h3 style="font-family:'Noto Serif'">Add New User</h3>
    <p class="text-muted">Register a new guest or staff member</p>
  </div>
  <div class="card card-custom p-4 mb-4">

    <form class="row g-4" action="<?php echo base_path('user/create'); ?>" method="POST">
      <div class="col-12">
        <label class="form-label">Profile Picture</label>
        <div class="d-flex align-items-center gap-4">
          <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width:120px;height:120px;">
            📷
          </div>
          <div>
            <button class="btn btn-outline-secondary btn-sm">Choose File</button>
            <div class="text-muted small">Max 2MB</div>
          </div>
        </div>
      </div>

      <!-- Fields -->
      <div class="col-md-6">
        <label class="form-label">Full Name</label>
        <input class="form-control" type="text" name="name">
      </div>

      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email">
      </div>

      <div class="col-md-6">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password">
      </div>

      <div class="col-md-6">
        <label class="form-label">Confirm Password</label>
        <input class="form-control" type="password" name="confirm_password">
      </div>

      <div class="col-md-6">
        <label class="form-label">Room</label>
        <input class="form-control" type="text" name="room">
      </div>

      <div class="col-md-6">
        <label class="form-label">Role</label>
        <select class="form-select" name="role">
          <option value="guest">Guest</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="col-12 text-end">
        <button class="btn btn-outline-secondary me-2">Cancel</button>
        <button class="btn btn-success">Add User</button>
      </div>
    </form>
  </div>
<?php
    $content = ob_get_clean();
    $title = "Add User";
    $activePage = "create-user";
    require __DIR__ . '/../layouts/main-layout.php';
?>