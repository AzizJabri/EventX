<?php
session_start();
require_once __DIR__ . '/../../../models/Booking.php';
require_once __DIR__ . '/../../ui/navbar.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header('Location: /views/auth/login.php');
    exit;
}

$bookings = Booking::forUser($_SESSION['user']['id']);
?>

<div class="container mt-5">
<?php require_once __DIR__ . '/../../ui/messages.php'; ?>
    <h2>Your Bookings</h2>

    <?php if (empty($bookings)): ?>
        <p>You haven’t made any bookings yet.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Venue</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['venue_name']) ?></td>
                        <td><?= htmlspecialchars($booking['location']) ?></td>
                        <td><?= htmlspecialchars($booking['date']) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $booking['status'] === 'approved' ? 'success' : (
                                $booking['status'] === 'pending' ? 'warning' : 'danger'
                            ) ?>">
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</div>
