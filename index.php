<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
?>

<?php
require_once __DIR__ . '/models/Venue.php';


// Fetch popular venues for the homepage
$popularVenues = Venue::getTopRatedVenues(3); // Fetch top 3 popular venues
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>VenueX - Book Venues Effortlessly</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/x-icon">
</head>
<body>
  <?php require_once __DIR__ . '/views/ui/navbar.php'; ?>

  <!-- Hero -->
  <section class="hero text-center">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 text-lg-start mb-4 mb-lg-0">
          <h1 class="display-5 fw-bold">Book Venues Effortlessly</h1>
          <p class="lead">
            Find and reserve the perfect venue for your event in just a few clicks.
          Whether it's a wedding, conference, or party, we have you covered.
          </p>
          <a href="/views/client/venues" class="btn btn-primary btn-lg">Start Booking</a>
        </div>
        <div class="col-lg-5">
          <img src="/assets/images/hero.jpg" alt="Event booking" class="hero-img">
        </div>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section id="features" class="py-5 features">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold">Why EventX?</h2>
        <p class="text-muted">Built with modern tech for a seamless experience.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="feature-card text-center">
            <i class="fas fa-calendar-check mb-3"></i>
            <h5>Instant Booking</h5>
            <p class="text-muted">Reserve venues in real-time without waiting.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card text-center">
            <i class="fas fa-map-marked-alt mb-3"></i>
            <h5>Verified Locations</h5>
            <p class="text-muted">All venues are verified and reviewed for quality.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card text-center">
            <i class="fas fa-lock mb-3"></i>
            <h5>Secure Payments</h5>
            <p class="text-muted">Your transactions are protected with end-to-end encryption.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery -->
  <section id="gallery" class="py-5 bg-light gallery">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Venue Gallery</h2>
      <p class="text-muted">Discover the beauty of our top venues.</p>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($popularVenues as $venue): ?>
        <div class="col">
          <div class="card shadow-sm border-0 rounded-3">
            <img src="/uploads/venues/<?= !empty($venue['image_url']) ? htmlspecialchars($venue['image_url']) : 'default.jpg' ?>" 
                 alt="<?= htmlspecialchars($venue['name']) ?>" class="card-img-top rounded-top">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($venue['name']) ?> | <?= htmlspecialchars($venue['location']) ?></h5>
              <p class="text-muted"><?= htmlspecialchars($venue['capacity']) ?> people</p>
              <p class="text-muted"><?= number_format($venue['price'], 2) ?> TND per hour</p>
              <a href="venue.php?id=<?= htmlspecialchars($venue['id']) ?>" class="btn btn-primary w-100">View Details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


  <!-- FAQ -->
  <section id="faq" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold">Frequently Asked Questions</h2>
        <p class="text-muted">Have questions? We’ve got answers.</p>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="faq mb-4">
            <h6>How do I book a venue?</h6>
            <p class="text-muted">Browse venues, select your dates, and confirm your booking in minutes.</p>
          </div>
          <div class="faq mb-4">
            <h6>Can I cancel or reschedule?</h6>
            <p class="text-muted">Yes, depending on the venue's cancellation policy.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="faq mb-4">
            <h6>Are there any hidden fees?</h6>
            <p class="text-muted">No hidden fees. All charges are shown upfront.</p>
          </div>
          <div class="faq mb-4">
            <h6>Is my payment information safe?</h6>
            <p class="text-muted">Absolutely. We use secure payment gateways and encryption.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
        <?php require_once __DIR__ . '/views/ui/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
