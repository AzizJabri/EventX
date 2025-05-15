<?php
session_start();
require_once '../../ui/navbar.php';
require_once __DIR__ . '/../../../models/Venue.php';
require_once __DIR__ . '/../../../models/Category.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

$categories = Category::all();

// Apply filters from GET parameters
$filters = [
    'name' => $_GET['name'] ?? null,
    'category_id' => $_GET['category_id'] ?? null,
    'location' => $_GET['location'] ?? null,
    'capacity' => $_GET['capacity'] ?? null,
    'price' => $_GET['price'] ?? null,
];
$venues = Venue::filter($filters);
?>

<div class="container mt-5">
  <h2 class="mb-4">Available Venues</h2>

  <!-- Filter Form -->
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

  <!-- Venue Cards -->
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if (count($venues) === 0): ?>
      <div class="col">
        <div class="alert alert-info w-100">No venues found matching your criteria.</div>
      </div>
    <?php else: ?>
      <?php foreach ($venues as $venue): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="/uploads/venues/<?= !empty($venue['image_url']) ? htmlspecialchars($venue['image_url']) : 'default.jpg' ?>" alt="<?= htmlspecialchars($venue['name']) ?>" style="height: 200px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($venue['name']) ?></h5>
              <h6 class="text-muted mb-2"><?= htmlspecialchars(Category::find($venue['category_id'])['name']) ?></h6>
              <p class="card-text">
                <strong>Location:</strong> <?= htmlspecialchars($venue['location']) ?><br>
                <strong>Capacity:</strong> <?= (int)$venue['capacity'] ?> guests<br>
                <strong>Price:</strong> <?= number_format($venue['price'], 2) ?> TND
              </p>
              <a href="show.php?id=<?= $venue['id'] ?>" class="btn btn-outline-primary w-100">View Details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');
    const inputs = form.querySelectorAll('input, select');

    inputs.forEach(input => {
      input.addEventListener('change', () => form.submit());

      // For text fields like name/location: submit on typing stop (debounce)
      if (input.type === 'text') {
        let timeout;
        input.addEventListener('input', () => {
          clearTimeout(timeout);
          timeout = setTimeout(() => form.submit(), 500); // 500ms delay
        });
      }
    });
  });
</script>


<?php require_once __DIR__ . '/../../ui/footer.php'; ?>
