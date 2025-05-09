
<?php
session_start();
require_once '../../ui/navbar.php';
require_once __DIR__ . '/../../../models/Venue.php';
require_once __DIR__ . '/../../../models/Category.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

$venues = Venue::all();
$categories = Category::all();
?>

<div class="container mt-5">
  <h2 class="mb-4">Available Venues</h2>

  <div class="row row-cols-1 row-cols-md-3 g-4">
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
  </div>
</div>

<?php require_once __DIR__ . '/../../ui/footer.php'; ?>


