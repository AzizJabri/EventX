<?php
session_start();
require_once '../../ui/navbar.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

$venue_id = $_GET['venue_id'] ?? null;
?>
<title>Leave a Review - Venue Booking</title>
<div class="container mt-5">
  <h2>Leave a Review</h2>
  <form action="/controllers/ReviewController.php" method="POST">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
    <input type="hidden" name="venue_id" value="<?= $venue_id ?>">

    <div class="mb-3">
      <label for="content" class="form-label">Your Review</label>
      <textarea name="content" class="form-control" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit Review</button>
  </form>
</div>
