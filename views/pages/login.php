<?php
require_once("core/globals.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo app_name ?> Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@600;700&family=Be+Vietnam+Pro:wght@400;600&display=swap" rel="stylesheet">
  <link href="/Cafeteria-project/style" rel="stylesheet">

  <style>
    .login-gradient {
      background: linear-gradient(135deg, rgba(22,52,34,0.03), rgba(147,74,41,0.03));
      min-height: 100vh;
    }

    .card-custom {
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(22,52,34,0.08);
    }

    .logo-circle {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: #c8ebd0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>

<body>

<div class="login-gradient d-flex align-items-center justify-content-center p-3">
  <div class="w-100" style="max-width: 420px;">
    <div class="card card-custom border-0 p-4 p-md-5">
      <div class="text-center mb-4">
        <div class="logo-circle mx-auto mb-3">
          🍽️
        </div>
        <h3 class="fw-bold" style="font-family: 'Noto Serif', serif;">Login</h3>
        <p class="text-muted">Welcome back to <?php echo app_name ?></p>
      </div>
      <form action="/Cafeteria-project/login" method="post">
        <div class="mb-4">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control border-0 border-bottom rounded-0" name="email" placeholder="name@hotel.com" required>
        </div>

        <div class="mb-4">
          <div class="d-flex justify-content-between">
            <label class="form-label">Password</label>
            <a href="#" class="small text-decoration-none text-danger">Forgot?</a>
          </div>
          <input type="password" class="form-control border-0 border-bottom rounded-0" name="password" placeholder="••••••••" required>
        </div>

        <button class="btn btn-success w-100 py-3">
          Sign In →
        </button>

      </form>
      <div class="text-center mt-4 pt-3 border-top">
        <p class="mb-0 text-muted">
          Don't have an account?
          <a href="#" class="fw-semibold text-danger text-decoration-none">Sign up</a>
        </p>
      </div>

    </div>
  </div>
</div>
<?php require_once("views/components/footer.php") ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>