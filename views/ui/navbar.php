<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}   

$user = $_SESSION['user'] ?? null;
$activePage = str_replace(['/views/admin/', '/views/client/', '.php', 'index', '/'], '', $_SERVER['PHP_SELF']);
$isLoggedIn = $user !== null;
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
<link rel="shortcut icon" href="/assets/images/favicon.png" type="image/x-icon">
<style>
  .navbar-brand {
      font-weight: bold;
      font-size: 2rem;
    }
  .navbar-nav .nav-link.active {
      font-weight: bold;
      color: #0d6efd !important;
  }
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="/">
      <img src="/assets/images/logo.png" alt="VenueX Logo" width="40" height="40" class="d-inline-block align-text-top me-2" style="background-color: #000; border-radius: 25%;">
      EventX
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <?php if ($isLoggedIn): ?>
          <?php if ($user['role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link <?= $activePage === '' ? 'active' : '' ?>" href="/views/admin/">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link <?= $activePage === 'categories' ? 'active' : '' ?>" href="/views/admin/categories">Categories</a></li>
            <li class="nav-item"><a class="nav-link <?= $activePage === 'bookings' ? 'active' : '' ?>" href="/views/admin/bookings">Bookings</a></li>
            <li class="nav-item"><a class="nav-link <?= $activePage === 'venues' ? 'active' : '' ?>" href="/views/admin/venues">Venues</a></li>
            <li class="nav-item"><a class="nav-link <?= $activePage === 'users' ? 'active' : '' ?>" href="/views/admin/users">Users</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link <?= $activePage === '' ? 'active' : '' ?>" href="/views/client/">Home</a></li>
            <li class="nav-item"><a class="nav-link <?= $activePage === 'venues' ? 'active' : '' ?>" href="/views/client/venues">Explore Venues</a></li>
            <li class="nav-item"><a class="nav-link <?= $activePage === 'bookings' ? 'active' : '' ?>" href="/views/client/bookings">My Bookings</a></li>
            <li class="nav-item"><a class="nav-link <?= $activePage === 'reviews' ? 'active' : '' ?>" href="/views/client/reviews">My Reviews</a></li>
          <?php endif; ?>

          <li class="nav-item d-flex align-items-center ms-3">
            <span class="nav-link text-dark">Hello, <?= htmlspecialchars($user['name']) ?></span>
          </li>
          <li class="nav-item ms-2">
            <a class="btn btn-outline-danger" href="/views/auth/logout.php">
              <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
          </li>

        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
          <li class="nav-item ms-2">
            <a class="btn btn-primary" href="/views/auth/login.php">Explore Venues</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
