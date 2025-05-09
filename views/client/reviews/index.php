<?php
require_once __DIR__ . '/../../../models/Review.php';
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../models/Venue.php';
require_once __DIR__ . '/../../../models/Booking.php';
require_once __DIR__ . '/../../ui/navbar.php';


$userId = $_SESSION['user']['id'];
$reviews = Review::forUser($userId);
?>

<div class="container mt-5">
    <h2>Your Reviews</h2>

    <?php if (empty($reviews)): ?>
        <p>You haven't submitted any reviews yet.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Venue Name :<?= htmlspecialchars(Venue::find(Booking::find($review['booking_id'])['venue_id'])['name']) ?></h5>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="fa fa-star<?= $i <= $review['rating'] ? ' text-warning' : '' ?>"></span>
                    <?php endfor ?>
                    <p class="card-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                    <p class="text-muted">Date: <?= htmlspecialchars($review['created_at']) ?></p>
                </div>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>
