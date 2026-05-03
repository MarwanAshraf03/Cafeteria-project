<?php
if (!isset($activePage)) {
  $activePage = "";
}

$userName = 'Guest';
$userRole = '';
$userImage = '';
if (isset($user)) {
  if (is_array($user)) {
    $userName = $user['name'] ?? 'Guest';
    $userRole = $user['role'] ?? '';
    $userImage = $user['profile_picture_link'] ?? '';
  } elseif (is_object($user)) {
    $userName = $user->name ?? 'Guest';
    $userRole = $user->role ?? '';
    $userImage = $user->profile_picture_link ?? '';
  }
}

$isLoggedIn = isset($user) && $user !== null;
$isAdmin = $userRole !== '' && strtoupper((string)$userRole) === 'ADMIN';
?>
<div class="sidebar d-flex flex-column p-3">
  <div class="d-flex align-items-center mb-4">
    <?php if (!empty($userImage)): ?>
      <img src="<?php echo base_path('storage/user-images/' . htmlspecialchars($userImage)); ?>" alt="Profile" class="rounded-circle me-2" style="width:40px;height:40px;object-fit:cover;">
    <?php else: ?>
      <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2"
        style="width:40px;height:40px;">
        <span class="material-symbols-outlined">restaurant</span>
      </div>
    <?php endif; ?>
    <div>
      <h6 class="mb-0 serif"><?php echo htmlspecialchars($userName); ?></h6>
      <small class="text-muted"><?php echo $userRole !== '' ? htmlspecialchars($userRole) : 'Please login'; ?></small>
    </div>
  </div>
  <ul class="nav nav-pills flex-column mb-auto">

      <li>
        <a href="<?php echo base_path('home'); ?>" class="nav-link d-flex align-items-center <?php if($activePage == 'home') echo 'active'; ?>">
          Home
        </a>
      </li>
    <?php if ($isAdmin): ?>
      <li class="nav-item">
        <a href="<?php echo base_path('products'); ?>" class="nav-link d-flex align-items-center <?php if ($activePage == 'products')
             echo 'active'; ?>">
          Products
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo base_path('admin/orders'); ?>" class="nav-link d-flex align-items-center <?php if ($activePage == 'admin-orders')
             echo 'active'; ?>">
          Orders
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo base_path('admin/users'); ?>" class="nav-link d-flex align-items-center <?php if ($activePage == 'users')
             echo 'active'; ?>">
          Users
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo base_path('user/create'); ?>" class="nav-link d-flex align-items-center <?php if ($activePage == 'create-user')
             echo 'active'; ?>">
          Add New User
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo base_path('admin/checks'); ?>" class="nav-link d-flex align-items-center <?php if($activePage == 'admin-checks') echo 'active'; ?>">
          Checks
        </a>
      </li>
    <?php endif; ?>
    <?php if ($isLoggedIn): ?>
      <li class="nav-item">
        <a href="<?php echo base_path('orders'); ?>" class="nav-link d-flex align-items-center <?php if ($activePage == 'orders')
             echo 'active'; ?>">
          My Orders
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo base_path('logout'); ?>" class="nav-link d-flex align-items-center">
          Logout
        </a>
      </li>
    <?php else: ?>
      <li class="nav-item">
        <a href="<?php echo base_path('login'); ?>" class="nav-link d-flex align-items-center <?php if ($activePage == 'login')
             echo 'active'; ?>">
          Login
        </a>
      </li>
    <?php endif; ?>


  </ul>
</div>