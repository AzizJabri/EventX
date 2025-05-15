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
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($reviews as $review): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                Venue Name: <?= htmlspecialchars(Venue::find(Booking::find($review['booking_id'])['venue_id'])['name']) ?>
                                <a href="/views/client/reviews/edit.php?id=<?= urlencode($review['id']) ?>" class="btn btn-sm btn-outline-primary float-end" title="Edit Review">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <form action="/controllers/ReviewController.php" method="post" class="float-end me-2">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($review['id']) ?>">
                                    <input type="hidden" name="venue_id" value="<?= htmlspecialchars(Venue::find(Booking::find($review['booking_id'])['venue_id'])['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Review">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </h5>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="fa fa-star<?= $i <= $review['rating'] ? ' text-warning' : '' ?>"></span>
                            <?php endfor ?>
                            <p class="card-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <small class="text-muted">Date: <?= htmlspecialchars($review['created_at']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>
