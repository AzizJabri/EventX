<?php
session_start();
require_once '../../../models/Review.php';
require_once '../../../models/Venue.php';
require_once '../../../models/Booking.php';
require_once '../../ui/navbar.php';

if (!isset($_SESSION['user'])) {
    header("Location: /views/auth/login.php");
    exit;
}

$review_id = $_GET['id'] ?? null;
$review = Review::find($review_id);

if (!$review || $review['user_id'] != $_SESSION['user']['id']) {
    $_SESSION['error'] = "Review not found or access denied.";
    header("Location: /views/client/reviews/");
    exit;
}
$venue = Venue::find(Booking::find($review['booking_id'])['venue_id']);
?>
<title>Edit Review - Venue Booking</title>
<style>
    .star-rating {
        display: inline-flex;
        direction: rtl;
        font-size: 1.5rem;
    }
    .star-rating input[type="radio"] {
        display: none;
    }
    .star-rating label {
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }
    .star-rating input[type="radio"]:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f5b301;
    }
</style>
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow" style="max-width: 500px; width: 100%;">
        <div class="card-body">
            <h2 class="card-title mb-4 text-center d-flex justify-content-between align-items-center">Edit Review for <?= htmlspecialchars($venue['name']) ?>
            <form action="/controllers/ReviewController.php" method="post" class="d-inline m-0 my-2">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $review['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm p-2" onclick="return confirm('Are you sure you want to delete this review?');">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
            </h2>
            <form action="/controllers/ReviewController.php" method="post">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $review['id'] ?>">
                <input type="hidden" name="venue_id" value="<?= $venue['id'] ?>">
                <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= ($review['rating'] == $i) ? 'checked' : '' ?> required>
                            <label for="star<?= $i ?>"><i class="fa fa-star"></i></label>
                        <?php endfor ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Your Comment</label>
                    <textarea name="comment" id="comment" rows="4" class="form-control" required><?= htmlspecialchars($review['comment']) ?></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="/views/client/reviews/" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back</a>

                    <a href="/views/client/venues/show.php?id=<?= $venue['id'] ?>" class="btn btn-info btn-sm">
                        <i class="fa-solid fa-eye"></i>
                    View Venue</a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-pencil"></i>
                        Update Review</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">