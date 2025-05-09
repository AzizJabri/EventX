<?php
session_start();

// Redirect if not admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Import models
require_once '../../models/User.php';
require_once '../../models/Venue.php';
require_once '../../models/Booking.php';
require_once '../../models/Review.php';

// Get counts
$userCount = count(User::all());
$venueCount = count(Venue::all());
$bookingCount = count(Booking::all());
$reviewCount = count(Review::all());
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Eventix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .dashboard-card {
      border-radius: 12px;
      transition: all 0.3s ease-in-out;
    }
    .dashboard-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-light">
  <?php require_once __DIR__ . '/../ui/navbar.php'; ?>
  <div class="container mt-5">
    <h1 class="mb-4">Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
      <div class="col-md-3">
        <div class="bg-primary text-white p-4 dashboard-card">
          <h4>Users</h4>
          <p class="fs-3"><?= $userCount ?></p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-secondary text-white p-4 dashboard-card">
          <h4>Venues</h4>
          <p class="fs-3"><?= $venueCount ?></p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-warning text-dark p-4 dashboard-card">
          <h4>Bookings</h4>
          <p class="fs-3"><?= $bookingCount ?></p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-info text-white p-4 dashboard-card">
          <h4>Reviews</h4>
          <p class="fs-3"><?= $reviewCount ?></p>
        </div>
      </div>
    </div>

  <!-- divider -->
   <div class="mb-4"></div>

    <!-- Recent Activities -->
    <h2 class="mb-4">Recent Activities</h2>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Venue</th>
              <th>Activity</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <!-- Example data, replace with actual data from your database -->
            <?php foreach (Booking::recentActivities(5) as $activity): ?>
              <tr>
                <td><?= htmlspecialchars(Venue::find($activity['venue_id'])['name'] ?? 'Unknown Venue') ?></td>
                <td><?= htmlspecialchars($activity['status']) ?></td>
                <td><?= htmlspecialchars($activity['date']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
