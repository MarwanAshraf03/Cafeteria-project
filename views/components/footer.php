<?php
require_once(__DIR__ . '/../../core/globals.php');
?>
<footer class="bg-light border-top py-4 mt-auto">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center text-center gap-3">
    
    <div class="fw-bold text-success"><?php echo app_name ?></div>

    <div class="d-flex gap-3 flex-wrap justify-content-center">
      <a href="#" class="text-muted small text-uppercase text-decoration-none">Privacy Policy</a>
      <a href="#" class="text-muted small text-uppercase text-decoration-none">Terms</a>
    </div>

    <div class="small text-success">
      © <?php echo date("Y") ?> <?php echo app_name ?>
    </div>

  </div>
</footer>