<?php
session_start();
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Review.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $venue_id = $_POST['venue_id'];

    $existingBookings = Booking::all();
    $booking_id = null;

    foreach ($existingBookings as $b) {
        if (
            $b['user_id'] == $user_id &&
            $b['venue_id'] == $venue_id &&
            ($b['status'] === 'approved' || $b['status'] === 'pending')
        ) {
            $booking_id = $b['id'];
            break;
        }
    }


    if (!$booking_id) {
        $_SESSION['error'] = "You must have a booking to leave a review.";
    } else {
        $existingReviews = Review::all();
        $alreadyReviewed = false;

        foreach ($existingReviews as $r) {
            if ($r['user_id'] == $user_id && $r['booking_id'] == $booking_id) {
                $alreadyReviewed = true;
                break;
            }
        }

        if ($alreadyReviewed) {
            $_SESSION['error'] = "You have already reviewed this booking.";
        } else {
            $review = new Review($booking_id, $user_id, $rating, $comment);
            $review->save();
            $_SESSION['success'] = "Review added successfully.";
        }
    }
} elseif ($action === 'update') {
    $review = Review::find($_POST['id']);
    if ($review) {
        if ($review['user_id'] == $_SESSION['user']['id']) {
            Review::update($_POST['id'], $_POST['rating'], $_POST['comment']);
            $_SESSION['success'] = "Review updated successfully.";
        } else {
            $_SESSION['error'] = "You can only edit your own reviews.";
        }
    } else {
        $_SESSION['error'] = "Review not found.";
    }
} elseif ($action === 'delete') {
    $review = Review::find($_POST['id']);
    if ($review) {
        if ($review['user_id'] == $_SESSION['user']['id']) {
            Review::delete($_POST['id']);
            $_SESSION['success'] = "Review deleted successfully.";
        } else {
            $_SESSION['error'] = "You can only delete your own reviews.";
        }
    } else {
        $_SESSION['error'] = "Review not found.";
    }
}

header("Location: ../views/client/venues/show.php?id=" . $_POST['venue_id']);
exit;
?>