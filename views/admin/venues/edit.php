<?php
session_start();
require_once '../../../models/Venue.php';
require_once '../../../models/Category.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: ./");
    exit;
}

$venue = Venue::find($_GET['id']);
$categories = Category::all();

if (!$venue) {
    $_SESSION['error'] = "Venue not found.";
    header("Location: ./");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Venue</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <?php require_once '../../ui/navbar.php'; ?>
  <h2>Edit Venue</h2>

  <form method="POST" action="/controllers/VenueController.php" class="row g-3" enctype="multipart/form-data">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="id" value="<?= $venue['id'] ?>">

    <div class="col-md-6">
      <label for="name" class="form-label">Name</label>
      <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($venue['name']) ?>" required>
    </div>

    <div class="col-md-6">
      <label for="location" class="form-label">Location</label>
      <input type="text" name="location" id="location" class="form-control" value="<?= htmlspecialchars($venue['location']) ?>" required>
    </div>

    <div class="col-md-3">
      <label for="capacity" class="form-label">Capacity</label>
      <input type="number" name="capacity" id="capacity" class="form-control" value="<?= $venue['capacity'] ?>" required>
    </div>

    <div class="col-md-3">
      <label for="price" class="form-label">Price (DT)</label>
      <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= $venue['price'] ?>" required>
    </div>

    <div class="col-md-3">
      <label for="category" class="form-label">Category</label>
      <select name="category_id" id="category" class="form-select" required>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $venue['category_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <label for="image" class="form-label">Image</label>
      <input type="file" name="image" id="image" class="form-control" >
    </div>

    <div class="col-12 d-flex justify-content-between">
      <a href="./" class="btn btn-secondary">Back</a>
      <button type="submit" class="btn btn-primary">Update Venue</button>
    </div>
  </form>
</div>
</body>
</html>
