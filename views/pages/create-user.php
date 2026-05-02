<?php
require_once(__DIR__ . '/../../core/globals.php');
ob_start();
?>
<div class="mb-4">
  <h3 style="font-family:'Noto Serif'">Add New User</h3>
  <p class="text-muted">Register a new guest or staff member</p>
</div>
<div class="card card-custom p-4 mb-4">
  <form id="createUserForm" class="row g-4" action="<?php echo base_path('user/create'); ?>" method="POST"
    enctype="multipart/form-data" novalidate>

    <!-- Profile Picture -->
    <div class="col-12">
      <label class="form-label">Profile Picture</label>
      <div class="d-flex align-items-center gap-4">
        <img id="avatarPreview" src="" alt="Preview"
          class="rounded-circle border shadow-sm d-none"
          style="width:120px;height:120px;object-fit:cover;">
        <div id="avatarPlaceholder" class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
          style="width:120px;height:120px;font-size:2.5rem;">📷</div>
        <div>
          <input type="file" id="profileImageInput" name="profile_image" accept="image/*"
            class="form-control form-control-sm" style="max-width:260px;">
          <div id="imageError" class="text-danger small mt-1 d-none">Image must be under 2MB.</div>
          <div class="text-muted small mt-1">Max 2MB · Optional</div>
        </div>
      </div>
    </div>

    <!-- Full Name -->
    <div class="col-md-6">
      <label class="form-label">Full Name <span class="text-danger">*</span></label>
      <input class="form-control" type="text" id="name" name="name" required minlength="2" placeholder="e.g. John Smith">
      <div class="invalid-feedback">Name is required and must be at least 2 characters.</div>
    </div>

    <!-- Email -->
    <div class="col-md-6">
      <label class="form-label">Email <span class="text-danger">*</span></label>
      <input class="form-control" type="email" id="email" name="email" required placeholder="e.g. john@example.com">
      <div class="invalid-feedback">Please enter a valid email address.</div>
    </div>

    <!-- Password -->
    <div class="col-md-6">
      <label class="form-label">Password <span class="text-danger">*</span></label>
      <input class="form-control" type="password" id="password" name="password" required minlength="6" placeholder="Min. 6 characters">
      <div class="invalid-feedback">Password is required and must be at least 6 characters.</div>
    </div>

    <!-- Confirm Password -->
    <div class="col-md-6">
      <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
      <input class="form-control" type="password" id="confirmPassword" name="confirm_password" required placeholder="Re-enter password">
      <div class="invalid-feedback">Passwords do not match.</div>
    </div>

    <!-- Room -->
    <div class="col-md-6">
      <label class="form-label">Room <span class="text-danger">*</span></label>
      <select class="form-select" id="room" name="room" required>
        <option value="">-- Select Room --</option>
        <?php foreach ($rooms as $room): ?>
          <option value="<?php echo htmlspecialchars($room->name); ?>">
            <?php echo htmlspecialchars($room->name); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback">Please select a room.</div>
    </div>

    <!-- Role -->
    <div class="col-md-6">
      <label class="form-label">Role <span class="text-danger">*</span></label>
      <select class="form-select" name="role" required>
        <option value="USER">User</option>
        <option value="ADMIN">Admin</option>
      </select>
      <div class="invalid-feedback">Please select a role.</div>
    </div>

    <div class="col-12 text-end">
      <a href="<?php echo base_path('admin/users'); ?>" class="btn btn-outline-secondary me-2">Cancel</a>
      <button type="submit" class="btn btn-success">Add User</button>
    </div>
  </form>
</div>

<script>
(function () {
  const form = document.getElementById('createUserForm');
  const passwordInput = document.getElementById('password');
  const confirmInput = document.getElementById('confirmPassword');
  const profileImageInput = document.getElementById('profileImageInput');
  const avatarPreview = document.getElementById('avatarPreview');
  const avatarPlaceholder = document.getElementById('avatarPlaceholder');
  const imageError = document.getElementById('imageError');

  // Live image preview + size check
  profileImageInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
      imageError.classList.remove('d-none');
      this.value = '';
      avatarPreview.classList.add('d-none');
      avatarPlaceholder.classList.remove('d-none');
      return;
    }
    imageError.classList.add('d-none');
    const reader = new FileReader();
    reader.onload = function (e) {
      avatarPreview.src = e.target.result;
      avatarPreview.classList.remove('d-none');
      avatarPlaceholder.classList.add('d-none');
    };
    reader.readAsDataURL(file);
  });

  // Form submit validation
  form.addEventListener('submit', function (e) {
    let valid = true;

    // Bootstrap native validation
    if (!form.checkValidity()) {
      valid = false;
    }

    // Confirm password must match
    if (confirmInput.value !== passwordInput.value) {
      confirmInput.classList.add('is-invalid');
      valid = false;
    } else {
      confirmInput.classList.remove('is-invalid');
    }

    // Image size re-check
    if (profileImageInput.files[0] && profileImageInput.files[0].size > 2 * 1024 * 1024) {
      imageError.classList.remove('d-none');
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
      e.stopPropagation();
    }

    form.classList.add('was-validated');
  });

  // Live confirm password match feedback
  confirmInput.addEventListener('input', function () {
    if (this.value === passwordInput.value) {
      this.classList.remove('is-invalid');
      this.classList.add('is-valid');
    } else {
      this.classList.add('is-invalid');
      this.classList.remove('is-valid');
    }
  });

  // Clear confirm valid state when password changes
  passwordInput.addEventListener('input', function () {
    if (confirmInput.value !== '') {
      if (confirmInput.value === this.value) {
        confirmInput.classList.remove('is-invalid');
        confirmInput.classList.add('is-valid');
      } else {
        confirmInput.classList.add('is-invalid');
        confirmInput.classList.remove('is-valid');
      }
    }
  });
})();
</script>
<?php
$content = ob_get_clean();
$title = "Add User";
$activePage = "create-user";
require __DIR__ . '/../layouts/main-layout.php';
?>
