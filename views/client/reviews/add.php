<?php
require_once '../../../models/Booking.php';
require_once '../../../models/Venue.php';
require_once '../../ui/navbar.php';

$venue_id = $_GET['venue_id'] ?? null;
$venue = Venue::find($venue_id); // Fetch venue details

?>
<title>Add Review - Venue Booking</title>
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
<div class="container mt-5">
    <h2>Leave a Review for <?= htmlspecialchars($venue['name']) ?></h2>

    <form action="/controllers/ReviewController.php" method="post">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="venue_id" value="<?= $venue_id ?>">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Rating</label>
            <div class="star-rating">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                    <label for="star<?= $i ?>"><i class="fa fa-star"></i></label>
                <?php endfor ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Your Comment</label>
            <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">Submit Review</button>
    </form>
</div>
