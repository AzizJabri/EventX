<?php
session_start();
require_once '../../../models/Category.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$categories = Category::all();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once '../../ui/navbar.php'; ?>
<div class="container mt-5">
  <h2>Manage Categories</h2>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <form method="POST" action="/controllers/CategoryController.php" class="row g-3 mb-4">
    <input type="hidden" name="action" value="create">
    <div class="col-md-10">
      <input type="text" name="name" class="form-control" placeholder="New category name" required>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-success w-100"><i class="fa-solid fa-plus"></i> Add</button>
    </div>
  </form>

  <table class="table table-bordered">
    <thead>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $category): ?>
      <tr>
        <td><?= $category['id'] ?></td>
        <td><?= htmlspecialchars($category['name']) ?></td>
        <td>
          <form method="POST" action="/controllers/CategoryController.php" class="d-inline">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" class="form-control d-inline w-auto" required>
            <button type="submit" class="btn btn-sm btn-warning">
              <i class="fa-solid fa-pencil"></i> Update
            </button>
          </form>

          <a href="/controllers/CategoryController.php?action=delete&id=<?= $category['id'] ?>" onclick="return confirm('Delete this category?')" class="btn btn-sm btn-danger">
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
