<?php
if(!isset($activePage)){
    $activePage = "";
  }
?>
<div class="sidebar d-flex flex-column p-3">
  <div class="d-flex align-items-center mb-4">
    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px;">
      <span class="material-symbols-outlined">restaurant</span>
    </div>
    <div>
      <h6 class="mb-0 serif"><?php echo $user ? $user->name : 'Guest'; ?></h6>
      <small class="text-muted"><?php echo $user ? $user->role : 'Please login'; ?></small>
    </div>
  </div>
  <ul class="nav nav-pills flex-column mb-auto">

      <li>
        <a href="<?php echo base_path(''); ?>" class="nav-link d-flex align-items-center <?php if($activePage == 'home') echo 'active'; ?>">
          Home
        </a>
      </li>
    <li class="nav-item">
      <a href="<?php echo base_path('user/create'); ?>" class="nav-link d-flex align-items-center <?php if($activePage == 'create-user') echo 'active'; ?>">
        Add New User
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo base_path('orders'); ?>" class="nav-link d-flex align-items-center <?php if($activePage == 'orders') echo 'active'; ?>">
        My Orders
      </a>
    </li>
    <?php if ($user): ?>
    <li class="nav-item">
      <a href="<?php echo base_path('logout'); ?>" class="nav-link d-flex align-items-center">
        Logout
      </a>
    </li>
    <?php else: ?>
    <li class="nav-item">
      <a href="<?php echo base_path('login'); ?>" class="nav-link d-flex align-items-center <?php if($activePage == 'login') echo 'active'; ?>">
        Login
      </a>
    </li>
    <?php endif; ?>


  </ul>
</div>