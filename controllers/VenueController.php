<?php
session_start();
require_once __DIR__ . '/../models/Venue.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../views/auth/login.php");
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$uploadDir = __DIR__ . '/../uploads/venues/';

if ($action === 'add') {
    $venue = new Venue($_POST['name'], $_POST['location'], $_POST['capacity'], $_POST['price'], $_POST['category_id'], null);
    $venueId = $venue->save(); // You must return the inserted ID from `save()`
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newFilename = $venueId . '.' . $extension;
        $targetFile = $uploadDir . $newFilename;

        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        // Update venue with image name
        Venue::updateImage($venueId, $newFilename);
    }

    $_SESSION['success'] = "Venue added successfully.";

} elseif ($action === 'delete') {
    $venue = Venue::find($_GET['id']);
    if ($venue && !empty($venue['image'])) {
        $imagePath = $uploadDir . $venue['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    Venue::delete($_GET['id']);
    $_SESSION['success'] = "Venue deleted.";

} elseif ($action === 'update') {
    $venue = Venue::find($_POST['id']);
    $uploadDir = "../uploads/venues/";  

    $newImageName = $venue['image_url'] ?? null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Delete old image if it exists and there is a new image being uploaded
        if (!empty($venue['image_url']) && file_exists($uploadDir . $venue['image_url'])) {
            unlink($uploadDir . $venue['image_url']);
        }

        // Get the file extension and create the new image name using the venue ID
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newImageName = $_POST['id'] . '.' . $extension;
        $targetFile = $uploadDir . $newImageName;

        // Move the uploaded file to the target directory
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    }

    // Update the venue record with the new image name (or keep the old one if no new image was uploaded)
    Venue::update(
        $_POST['id'], 
        $_POST['name'], 
        $_POST['location'], 
        $_POST['capacity'], 
        $_POST['price'], 
        $_POST['category_id'], 
        $newImageName
    );

    $_SESSION['success'] = "Venue updated.";

}

header("Location: ../views/admin/venues");
exit;
