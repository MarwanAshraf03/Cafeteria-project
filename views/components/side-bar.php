<?php
if (!isset($activePage)) {
  $activePage = "";
}

$userName = 'Guest';
$userRole = '';
if (isset($user)) {
  if (is_array($user)) {
    $userName = $user['name'] ?? 'Guest';
    $userRole = $user['role'] ?? '';
  } elseif (is_object($user)) {
    $userName = $user->name ?? 'Guest';
    $userRole = $user->role ?? '';
  }
}

$isLoggedIn = isset($user) && $user !== null;
$isAdmin = $userRole !== '' && strtoupper((string)$userRole) === 'ADMIN';
?>
<div class="sidebar d-flex flex-column p-3">
  <div class="d-flex align-items-center mb-4">
    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2"
      style="width:40px;height:40px;">
      <span class="material-symbols-outlined">restaurant</span>
    </div>
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