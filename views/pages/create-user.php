<?php
require_once(__DIR__ . '/../../core/globals.php');
ob_start();
?>
<div class="mb-4">
  <h3 style="font-family:'Noto Serif'">Add New User</h3>
  <p class="text-muted">Register a new guest or staff member</p>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $error): ?>
        <li><?php echo htmlspecialchars($error); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card card-custom p-4 mb-4">
  <form id="createUserForm" class="row g-4"
    action="<?php echo base_path('user/create'); ?>"
    method="POST" enctype="multipart/form-data" novalidate>

    <!-- Profile Picture -->
    <div class="col-12">
      <label class="form-label">Profile Picture</label>
      <div class="d-flex align-items-center gap-4">
        <img id="avatarPreview" src="" alt="Preview"
          class="rounded-circle border shadow-sm d-none"
          style="width:120px;height:120px;object-fit:cover;">
        <div id="avatarPlaceholder"
          class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
          style="width:120px;height:120px;font-size:2.5rem;">📷</div>
        <div>
          <input type="file" id="profileImageInput" name="profile_image" accept="image/*"
            class="form-control form-control-sm" style="max-width:260px;">
          <div class="text-muted small mt-1">Max 2MB · JPG, PNG, GIF, WebP · Optional</div>
        </div>
      </div>
    </div>

    <!-- Full Name -->
    <div class="col-md-6">
      <label class="form-label">Full Name <span class="text-danger">*</span></label>
      <input class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
        type="text" name="name"
        value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>"
        required minlength="2" placeholder="e.g. John Smith">
      <div class="invalid-feedback">
        <?php echo $errors['name'] ?? 'Name is required and must be at least 2 characters.'; ?>
      </div>
    </div>

    <!-- Email -->
    <div class="col-md-6">
      <label class="form-label">Email <span class="text-danger">*</span></label>
      <input class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
        type="email" name="email"
        value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
        required placeholder="e.g. john@example.com">
      <div class="invalid-feedback">
        <?php echo $errors['email'] ?? 'Please enter a valid email address.'; ?>
      </div>
    </div>

    <!-- Password -->
    <div class="col-md-6">
      <label class="form-label">Password <span class="text-danger">*</span></label>
      <input class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
        type="password" id="password" name="password"
        required minlength="6" placeholder="Min. 6 characters">
      <div class="invalid-feedback">
        <?php echo $errors['password'] ?? 'Password must be at least 6 characters.'; ?>
      </div>
    </div>

    <!-- Confirm Password -->
    <div class="col-md-6">
      <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
      <input class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
        type="password" id="confirmPassword" name="confirm_password"
        required placeholder="Re-enter password">
      <div class="invalid-feedback">
        <?php echo $errors['confirm_password'] ?? 'Passwords do not match.'; ?>
      </div>
    </div>

    <!-- Room -->
    <div class="col-md-6">
      <label class="form-label">Room <span class="text-danger">*</span></label>
      <select class="form-select <?php echo isset($errors['room']) ? 'is-invalid' : ''; ?>"
        name="room" required>
        <option value="">-- Select Room --</option>
        <?php foreach ($rooms as $room): ?>
          <option value="<?php echo htmlspecialchars($room['name']); ?>"
            <?php echo ($old['room'] ?? '') === $room['name'] ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($room['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback">
        <?php echo $errors['room'] ?? 'Please select a room.'; ?>
      </div>
    </div>

    <!-- Role -->
    <div class="col-md-6">
      <label class="form-label">Role <span class="text-danger">*</span></label>
      <select class="form-select <?php echo isset($errors['role']) ? 'is-invalid' : ''; ?>"
        name="role" required>
        <option value="USER" <?php echo ($old['role'] ?? 'USER') === 'USER' ? 'selected' : ''; ?>>User</option>
        <option value="ADMIN" <?php echo ($old['role'] ?? '') === 'ADMIN' ? 'selected' : ''; ?>>Admin</option>
      </select>
      <div class="invalid-feedback">
        <?php echo $errors['role'] ?? 'Please select a role.'; ?>
      </div>
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

  profileImageInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
      this.classList.add('is-invalid');
      this.value = '';
      avatarPreview.classList.add('d-none');
      avatarPlaceholder.classList.remove('d-none');
      return;
    }
    this.classList.remove('is-invalid');
    const reader = new FileReader();
    reader.onload = function (e) {
      avatarPreview.src = e.target.result;
      avatarPreview.classList.remove('d-none');
      avatarPlaceholder.classList.add('d-none');
    };
    reader.readAsDataURL(file);
  });

  form.addEventListener('submit', function (e) {
    let valid = true;
    if (!form.checkValidity()) valid = false;

    if (confirmInput.value !== passwordInput.value) {
      confirmInput.classList.add('is-invalid');
      valid = false;
    } else {
      confirmInput.classList.remove('is-invalid');
    }

    if (!valid) { e.preventDefault(); e.stopPropagation(); }
    form.classList.add('was-validated');
  });

  confirmInput.addEventListener('input', function () {
    this.classList.toggle('is-invalid', this.value !== passwordInput.value);
    this.classList.toggle('is-valid', this.value === passwordInput.value && this.value !== '');
  });

  passwordInput.addEventListener('input', function () {
    if (confirmInput.value !== '') {
      confirmInput.classList.toggle('is-invalid', confirmInput.value !== this.value);
      confirmInput.classList.toggle('is-valid', confirmInput.value === this.value);
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
