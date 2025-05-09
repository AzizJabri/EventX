<?php
require_once __DIR__ . '/../models/Booking.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $user_id = $_POST['user_id'];
    $venue_id = $_POST['venue_id'];
    $date = $_POST['date'];

    $existingBookings = Booking::all();
    $conflictMessage = null;

    foreach ($existingBookings as $b) {
        if ($b['venue_id'] == $venue_id && $b['date'] === $date) {
            if ($b['status'] === 'approved') {
                $conflictMessage = "This venue is already approved for booking on that date.";
                break;
            } elseif ($b['status'] === 'pending' && $b['user_id'] == $user_id) {
                $conflictMessage = "You already have a pending booking for this venue on that date.";
                break;
            }
        }
    }

    if ($conflictMessage) {
        $_SESSION['error'] = $conflictMessage;
    } else {
        $booking = new Booking($user_id, $venue_id, $date);
        $booking->save();
        $_SESSION['success'] = "Booking request submitted successfully.";
    }

    if ($_SESSION['user']['role'] === 'admin') {
        header("Location: ../views/admin/bookings");
    } else {
        header("Location: ../views/client/bookings");
    }
    exit;
} elseif ($action === 'update') {
    Booking::update($_POST['id'], $_POST['status']);
    $_SESSION['success'] = "Booking updated.";
} elseif ($action === 'delete') {
    Booking::delete($_GET['id']);
    $_SESSION['success'] = "Booking deleted.";
}

if ($_SESSION['user']['role'] === 'admin') {
    header("Location: ../views/admin/bookings");
} else {
    header("Location: ../views/client");
}
exit;
?>