<?php
session_start();
require_once __DIR__ . '/../../../models/Venue.php';
require_once __DIR__ . '/../../ui/navbar.php';

if (!isset($_SESSION['user'])) {
    header('Location: /views/auth/login.php');
    exit;
}

$venueId = $_GET['venue_id'] ?? null;
$date = $_GET['date'] ?? null;

if (!$venueId || !$date) {
    die("Invalid request.");
}

$venue = Venue::find($venueId); // Fetch venue details
?>

<div class="container mt-5">
    <h2>Confirm Booking for <?= htmlspecialchars($venue['name']) ?></h2>
    <div class="card p-4 shadow-sm">
        <form action="/controllers/BookingController.php" method="post">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="venue_id" value="<?= $venueId ?>">
            <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>"> 

            <div class="mb-3">
                <label for="date" class="form-label">Select Booking Date</label>
                <input type="date" name="date" id="date" value="<?= htmlspecialchars($date) ?>" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>

            <button type="submit" class="btn btn-primary">Confirm Booking</button>
        </form>
    </div>
</div>

