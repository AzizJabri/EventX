<?php
session_start();


if (isset($_SESSION['user'])) {
    header("Location: /views/user/");
    exit;
} elseif (isset($_SESSION['admin'])) {
    header("Location: /views/admin/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - VenueX</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .login-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .login-card {
      max-width: 960px;
      border: 1px solid #dee2e6;
      border-radius: 1rem;
      overflow: hidden;
      background-color: #ffffff;
      box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,0.03);
    }


    .form-section {
      padding: 3rem;
    }

    .form-section h2 {
      font-weight: 600;
      margin-bottom: 2rem;
    }

    .form-section a {
      text-decoration: none;
    }

    .form-section a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <?php require_once __DIR__ . '/../ui/navbar.php'; ?>
  <div class="login-wrapper">
    <div class="row login-card w-100">
      
      <!-- Form -->
      <div class="col-md-6 form-section">
        <h2 class="text-center">Welcome Back</h2>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form method="POST" action="/controllers/AuthController.php">
          <input type="hidden" name="action" value="login">
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary" type="submit">Login</button>
          </div>
          <div class="text-center mt-3">
            <span>Don’t have an account? <a href="register.php">Sign up</a></span>
          </div>
        </form>
      </div>

      <!-- Image -->
      <div class="col-md-6 login-image d-none d-md-block">
        <div class="d-flex align-items-center justify-content-center" style="height: 100%; background-color: #f8f9fa;">
          <img src="/assets/images/login.jpg" alt="Login Image" class="img-fluid">
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
