<?php
session_start();
require_once __DIR__ . '/../../../models/User.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$userToEdit = (new User())->find($_GET['id']);
print_r($userToEdit);
if (!$userToEdit) {
    $_SESSION['error'] = "User not found.";
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php require_once '../../ui/navbar.php'; ?>
  <div class="container mt-5">
    <h2>Edit User</h2>

    <form method="POST" action="/controllers/UserController.php">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= $userToEdit['id'] ?>">

      <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($userToEdit['name']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userToEdit['email']) ?>" required>
      </div>

      <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
          <option value="client" <?= $userToEdit['role'] === 'client' ? 'selected' : '' ?>>Client</option>
          <option value="admin" <?= $userToEdit['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>

      <button class="btn btn-success"><i class="fa-solid fa-pencil"></i> Update</button>
      <a href="index.php" class="btn btn-secondary"><i class="fa-solid fa-x"></i> Cancel</a>
    </form>
  </div>
</body>
</html>
