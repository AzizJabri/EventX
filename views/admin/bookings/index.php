<?php
session_start();
require_once __DIR__ . '/../../../models/Booking.php';
require_once __DIR__ . '/../../../models/Venue.php';
require_once __DIR__ . '/../../../models/User.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$bookings = Booking::all();
$venues = Venue::all();
$users = User::all();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php require_once '../../ui/navbar.php'; ?>
  <div class="container mt-5">
    <div class="card mb-4">
    
      <div class="card-header">Add New Booking</div>
      <div class="card-body">
        <form action="/controllers/BookingController.php" method="POST" class="row g-3">
          <input type="hidden" name="action" value="add">
          
          <div class="col-md-4">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-select" required>
              <option value="" disabled selected>Choose a user</option>
              <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div class="col-md-4">
            <label for="venue_id" class="form-label">Venue</label>
            <select name="venue_id" id="venue_id" class="form-select" required>
              <option value="" disabled selected>Choose a venue</option>
              <?php foreach ($venues as $venue): ?>
                <option value="<?= $venue['id'] ?>"><?= htmlspecialchars($venue['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary"> <i class="fa-solid fa-plus"></i> Create Booking</button>
          </div>
        </form>
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
      </div>
    </div>
    <h2>Manage Bookings</h2>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>User</th>
          <th>Venue</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $booking): ?>
          <tr>
            <td>
              <?php
                $userModel = new User();
                $user = $userModel->find($booking['user_id']);
                echo htmlspecialchars($user['name']);
              ?>
            </td>
            <td>
              <?php
                $venue = Venue::find($booking['venue_id']);
              ?>
              <a href="/views/admin/venues/edit.php?id=<?= $venue['id'] ?>">
                <?= htmlspecialchars($venue['name']) ?>
              </a>
            </td>
            <td><?= htmlspecialchars($booking['date']) ?></td>
            <td class="<?= $booking['status'] === 'approved' ? 'text-success' : ($booking['status'] === 'rejected' ? 'text-danger' : 'text-warning') ?>">
              <?= htmlspecialchars(ucfirst($booking['status'])) ?>
            </td>
            <td>
              <?php if ($booking['status'] === 'pending'): ?>
                <form method="POST" action="/controllers/BookingController.php" class="d-inline">
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                  <input type="hidden" name="status" value="approved">
                  <button class="btn btn-success btn-sm"> <i class="fa-solid fa-check"></i> Approve</button>
                </form>
                <form method="POST" action="/controllers/BookingController.php" class="d-inline">
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                  <input type="hidden" name="status" value="rejected">
                  <button class="btn btn-warning btn-sm"> <i class="fa-solid fa-x"></i> Reject</button>
                </form>
              <?php endif; ?>
              <?php if ($booking['status'] !== 'pending'): ?>
                <form method="POST" action="/controllers/BookingController.php" class="d-inline">
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                  <input type="hidden" name="status" value="pending">
                  <button class="btn btn-secondary btn-sm"> <i class="fa-solid fa-backward"></i> Revert</button>
                </form>
              <?php endif; ?>

              <form method="GET" action="/controllers/BookingController.php" class="d-inline">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this booking?')"> <i class="fa-solid fa-trash"></i> Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</body>
</html>
