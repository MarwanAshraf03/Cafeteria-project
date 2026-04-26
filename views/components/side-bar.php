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
      <h6 class="mb-0 serif">Hotel Cafeteria</h6>
      <small class="text-muted">Administrator</small>
    </div>
  </div>
  <ul class="nav nav-pills flex-column mb-auto">

      <li>
        <a href="/Cafeteria-project/" class="nav-link d-flex align-items-center <?php if($activePage == 'home') echo 'active'; ?>">
          Home
        </a>
      </li>
    <li class="nav-item">
      <a href="/Cafeteria-project/user/create" class="nav-link d-flex align-items-center <?php if($activePage == 'create-user') echo 'active'; ?>">
        Add New User
      </a>
    </li>


  </ul>
</div>