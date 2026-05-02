<?php
ob_start();
?>
<div class="d-flex flex-column gap-4">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h3 class="mb-1" style="font-family:'Noto Serif'">All Users</h3>
      <p class="text-muted mb-0 small">Manage registered users and their roles.</p>
    </div>
    <a href="<?php echo base_path('user/create'); ?>" class="btn btn-success px-4 py-2 shadow-sm fw-semibold">
      + Add User
    </a>
  </div>

  <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($successMessage); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-muted">
          <tr>
            <th class="fw-semibold px-4 py-3">#</th>
            <th class="fw-semibold py-3">Name</th>
            <th class="fw-semibold py-3">Email</th>
            <th class="fw-semibold py-3">Room</th>
            <th class="fw-semibold py-3">Role</th>
            <th class="fw-semibold px-4 py-3 text-end">Actions</th>
          </tr>
        </thead>
        <tbody class="border-top-0">
          <?php if (empty($users)): ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-5">No users found.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($users as $u): ?>
              <tr>
                <td class="px-4 text-muted small"><?php echo htmlspecialchars($u['id']); ?></td>
                <td class="fw-medium text-dark"><?php echo htmlspecialchars($u['name']); ?></td>
                <td class="text-muted"><?php echo htmlspecialchars($u['email']); ?></td>
                <td class="text-muted"><?php echo htmlspecialchars($u['room'] ?? '—'); ?></td>
                <td>
                  <span class="badge rounded-pill <?php echo strtoupper($u['role']) === 'ADMIN' ? 'bg-success' : 'bg-secondary'; ?> bg-opacity-10 text-<?php echo strtoupper($u['role']) === 'ADMIN' ? 'success' : 'secondary'; ?> border border-<?php echo strtoupper($u['role']) === 'ADMIN' ? 'success' : 'secondary'; ?> fw-semibold small px-3 py-1">
                    <?php echo htmlspecialchars(ucfirst(strtolower($u['role']))); ?>
                  </span>
                </td>
                <td class="px-4 text-end">
                  <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo base_path('admin/users/edit?id=' . $u['id']); ?>"
                      class="btn btn-sm btn-outline-primary">
                      EDIT
                    </a>
                    <form action="<?php echo base_path('admin/users/delete?id=' . $u['id']); ?>" method="POST" class="m-0">
                      <button type="submit" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Are you sure you want to delete this user?')">
                        DELETE
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php
$content = ob_get_clean();
$title = "All Users";
$activePage = "users";
require __DIR__ . '/../../layouts/main-layout.php';
?>
