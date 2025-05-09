<?php
session_start();

require_once '../../../models/Venue.php';
require_once '../../../models/Category.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$venues = Venue::all();
$categories = Category::all();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Venues</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php require_once '../../ui/navbar.php'; ?>

<div class="container mt-5">
  <h2>Venue Management</h2>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <form method="POST" action="/controllers/VenueController.php" class="row g-3 mb-4" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <div class="col-md-2">
      <input type="text" name="name" class="form-control" placeholder="Venue Name" required>
    </div>
    <div class="col-md-2">
      <input type="text" name="location" class="form-control" placeholder="Location" required>
    </div>
    <div class="col-md-2">
      <input type="number" name="capacity" class="form-control" placeholder="Capacity" required>
    </div>
    <div class="col-md-2">
      <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
    </div>
    <div class="col-md-2">
      <select name="category_id" class="form-control" required>
        <option value="">Category</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <input type="file" name="image" class="form-control" placeholder="Image" required>
    </div>
    <div class="col-md-12 text-end">
      <button type="submit" class="btn btn-success"><i class="fa-solid fa-plus"></i> Add Venue</button>
    </div>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Location</th>
        <th>Capacity</th>
        <th>Price</th>
        <th>Category</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($venues as $venue): ?>
        <tr>
          <td>
              <img src="/uploads/venues/<?= !empty($venue['image_url']) ? htmlspecialchars($venue['image_url']) : 'default.jpg' ?>" alt="<?= htmlspecialchars($venue['name']) ?>" class="img-thumbnail" style="width: 100px; height: auto;">
          </td>
          <td><?= htmlspecialchars($venue['name']) ?></td>
          <td><?= htmlspecialchars($venue['location']) ?></td>
          <td><?= $venue['capacity'] ?></td>
          <td><?= $venue['price'] ?> DT</td>
          <td><?= Category::find($venue['category_id'])['name'] ?></td>
          <td>
            <a href="edit.php?id=<?= $venue['id'] ?>" class="btn btn-sm btn-warning">
              <i class="fa-solid fa-pencil"></i> Edit
            </a>
            <a href="/controllers/VenueController.php?action=delete&id=<?= $venue['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
            <i class="fa-solid fa-trash"></i> Delete
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
