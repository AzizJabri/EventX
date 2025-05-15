<?php
session_start();

require_once '../../../models/Venue.php';
require_once '../../../models/Category.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$filters = [
    'name' => $_GET['name'] ?? null,
    'category_id' => $_GET['category_id'] ?? null,
    'location' => $_GET['location'] ?? null,
    'capacity' => $_GET['capacity'] ?? null,
    'price' => $_GET['price'] ?? null,
];

$venues = Venue::filter($filters);
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
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <div class="mb-4">
    <h4>Filter Venues</h4>
  </div>
  <form method="GET" class="mb-5" id="filterForm">
    <div class="row g-3">
      <div class="col-md-2">
        <input type="text" name="name" class="form-control" placeholder="Search by name" value="<?= htmlspecialchars($filters['name']) ?>">
      </div>
      <div class="col-md-2">
        <select name="category_id" class="form-select">
          <option value="">All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] == $cat['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <input type="text" name="location" class="form-control" placeholder="Location" value="<?= htmlspecialchars($filters['location']) ?>">
      </div>
      <div class="col-md-2">
        <input type="number" name="capacity" class="form-control" placeholder="Min capacity" min="0" value="<?= htmlspecialchars($filters['capacity']) ?>">
      </div>
      <div class="col-md-2">
        <input type="number" name="price" class="form-control" placeholder="Max price" min="0" value="<?= htmlspecialchars($filters['price']) ?>">
      </div>
      <div class="col-md-<?= isset($_GET['name']) || isset($_GET['category_id']) || isset($_GET['location']) || isset($_GET['capacity']) || isset($_GET['price']) ? '1' : '2' ?> d-flex justify-content-between">
        <?php if (isset($_GET['name']) || isset($_GET['category_id']) || isset($_GET['location']) || isset($_GET['capacity']) || isset($_GET['price'])): ?>
          <a href="index.php" class="d-flex align-items-center btn btn-danger mx-2">
            <i class="fa fa-times px-1"></i>
            Clear</a>
        <?php endif; ?>
        <button type="submit" class="d-flex align-items-center btn btn-primary mx-2">
          <i class="fa fa-filter px-1"></i>
          Filter</button>
      </div>
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
      <?php if (count($venues) === 0): ?>
        <tr>
          <td colspan="7" class="text-center">No venues found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require_once '../../ui/footer.php'; ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');
    const inputs = form.querySelectorAll('input, select');

    inputs.forEach(input => {
      input.addEventListener('change', () => form.submit());

      
      if (input.type === 'text') {
        let timeout;
        input.addEventListener('input', () => {
          clearTimeout(timeout);
          timeout = setTimeout(() => form.submit(), 1000);
        });
      }
    });
  });
</script>

</body>
</html>
