<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - VenueX</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/x-icon">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .register-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .register-card {
      max-width: 960px;
      border: 1px solid #dee2e6;
      border-radius: 1rem;
      overflow: hidden;
      background-color: #ffffff;
      box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,0.03);
    }

    .register-image {
      background: url('https://via.placeholder.com/600x500') no-repeat center center;
      background-size: cover;
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
  <div class="register-wrapper">
    <div class="row register-card w-100">
      
      <!-- Form -->
      <div class="col-md-6 form-section">
        <h2 class="text-center">Create Your Account</h2>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="/controllers/AuthController.php">
          <input type="hidden" name="action" value="register">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-success" type="submit">Register</button>
          </div>
          <div class="text-center mt-3">
            <span>Already have an account? <a href="login.php">Login</a></span>
          </div>
        </form>
      </div>

      <!-- Image -->
      <div class="col-md-6 register-image d-none d-md-block">
        <div class="d-flex align-items-center justify-content-center" style="height: 100%; background-color: #f8f9fa;">
          <img src="/assets/images/register.jpg" alt="Register Image" class="img-fluid">
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  document.querySelector('form').addEventListener('submit', function (e) {
    const nameInput = this.querySelector('input[name="name"]');
    const emailInput = this.querySelector('input[name="email"]');
    const passwordInput = this.querySelector('input[name="password"]');

    let hasError = false;

    this.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    [nameInput, emailInput, passwordInput].forEach(input => input.classList.remove('is-invalid'));

  
    const name = nameInput.value.trim();
    if (name.length < 3) {
      showError(nameInput, "Name must be at least 3 characters.");
      hasError = true;
    }

  
    const email = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      showError(emailInput, "Please enter a valid email address.");
      hasError = true;
    }

    
    const password = passwordInput.value.trim();
    if (password.length < 6) {
      showError(passwordInput, "Password must be at least 6 characters.");
      hasError = true;
    }

    if (hasError) {
      e.preventDefault();
    }

    function showError(input, message) {
      input.classList.add('is-invalid');
      const feedback = document.createElement('div');
      feedback.className = 'invalid-feedback';
      feedback.textContent = message;
      input.parentNode.appendChild(feedback);
    }
  });
</script>

</body>
</html>
