<?php
session_start();
require_once '../../ui/navbar.php';
require_once __DIR__ . '/../../../models/Venue.php';
require_once __DIR__ . '/../../../models/Review.php';
require_once __DIR__ . '/../../../models/Booking.php';
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../models/Category.php';

if (!isset($_SESSION['user'])) {
  header('Location: ../auth/login.php');
  exit;
}

$id = $_GET['id'] ?? null;
$venue = Venue::find($id);
if (!$venue) {
  header('Location: /views/client/venues/index.php');
  exit;
}
$reviews = Review::forVenue($id);
$canReview = Booking::userCanReview($_SESSION['user']['id'], $id);
$user = new User();
?>
<title><?= htmlspecialchars($venue['name']) ?> - Venue Booking</title>

<div class="container mt-5">
  <?php require_once __DIR__ . '/../../ui/messages.php'; ?>
  <div class="row">
  <div class="col-md-8">
  <img src="/uploads/venues/<?= !empty($venue['image_url']) ? htmlspecialchars($venue['image_url']) : 'default.jpg' ?>" alt="<?= htmlspecialchars($venue['name']) ?>" class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;">


    <h2><?= htmlspecialchars($venue['name']) ?></h2>
    <p class="text-muted"><?= htmlspecialchars($venue['location']) ?></p>

    <ul class="list-group mb-4">
    <li class="list-group-item"><strong>Capacity:</strong> <?= htmlspecialchars($venue['capacity']) ?> people</li>
    <li class="list-group-item"><strong>Price:</strong> <?= htmlspecialchars(number_format($venue['price'], 2)) ?> TND</li>
    <li class="list-group-item"><strong>Category:</strong> <?= htmlspecialchars(Category::find($venue['category_id'])['name']) ?></li>
    <li class="list-group-item"><strong>Rating:</strong>
      <?php
      $averageRating = Review::averageRating($venue['id']);
      for ($i = 1; $i <= 5; $i++):
        ?>
        <span class="fa fa-star<?= $i <= $averageRating ? ' text-warning' : '' ?>"></span>
      <?php endfor ?>
      (<?= htmlspecialchars(number_format($averageRating, 1)) ?>)
    </ul>

    <a href="/views/client/bookings/add.php?venue_id=<?= $venue['id'] ?>&&date=<?= date('Y-m-d') ?>" class="btn btn-primary">Book Now</a>
    <a href="/views/client/venues/index.php" class="btn btn-secondary ms-2">Back to Venues</a>

    <?php if ($canReview): ?>
    <a href="/views/client/reviews/add.php?venue_id=<?= $venue['id'] ?>" class="btn btn-warning ms-2">Leave a Review</a>
    <?php endif ?>
  </div>

  <div class="col-md-4">
    <h4>Reviews</h4>
    <?php if (count($reviews) === 0): ?>
    <p class="text-muted">No reviews yet.</p>
    <?php else: ?>
    <?php foreach ($reviews as $review): ?>
      <div class="border p-3 mb-2 rounded position-relative">
      <?php if ($review['user_id'] == $_SESSION['user']['id']): ?>
        <div class="position-absolute top-0 end-0 mt-2 me-2">
        <a href="/views/client/reviews/edit.php?id=<?= $review['id'] ?>" class="text-primary me-2" title="Edit">
          <i class="fa fa-edit"></i>
        </a>
        <form action="/controllers/ReviewController.php" method="post" class="d-inline">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $review['id'] ?>">
          <input type="hidden" name="venue_id" value="<?= $venue['id'] ?>">
          <button type="submit" class="btn btn-link text-danger p-0" title="Delete" onclick="return confirm('Are you sure you want to delete this review?');">
            <i class="fa fa-trash"></i>
          </button>
        </form>
        </div>
      <?php endif; ?>
      <strong><?= htmlspecialchars($user->find($review['user_id'])['name']) ?></strong> <span class="text-muted">(<?= htmlspecialchars($review['created_at']) ?>)</span>
      <div class="rating mb-2">
        <?php for ($i = 1; $i <= 5; $i++): ?>
        <span class="fa fa-star<?= $i <= $review['rating'] ? ' text-warning' : '' ?>"></span>
        <?php endfor ?>
      </div>
      <p><?= htmlspecialchars($review['comment']) ?></p>
      </div>
    <?php endforeach ?>
    <?php endif ?>
  </div>
  </div>
</div>
 <?php require_once __DIR__ . '/../../ui/footer.php'; ?>
