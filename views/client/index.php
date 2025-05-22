<?php
session_start();
require_once __DIR__ . '/../ui/navbar.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user = $_SESSION['user'];
?>
<title>Dashboard - Venue Booking</title>
<div class="container mt-5 min-vh-100">
    <?php require_once __DIR__ . '/../ui/messages.php'; ?>
    <h2>Welcome, <?= htmlspecialchars($user['name']) ?> 👋</h2>
    <p class="lead">Here’s what you can do:</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">View Venues</h5>
                    <p class="card-text">Browse available venues and make a booking.</p>
                    <a href="venues/" class="btn btn-primary">Explore Venues</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">My Bookings</h5>
                    <p class="card-text">Check your upcoming and past bookings.</p>
                    <a href="bookings/" class="btn btn-secondary">View Bookings</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Leave Reviews</h5>
                    <p class="card-text">Leave feedback for venues you've used.</p>
                    <a href="reviews/" class="btn btn-warning">Write a Review</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../ui/footer.php'; ?>