<?php
session_start();
require_once __DIR__ . '/../models/User.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../views/auth/login.php");
    exit;
}

$action = $_POST['action'] ?? '';
if ($action === 'update') {
    $id = $_POST['id'];
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
    ];
    
    User::update($id, $data['name'], $data['email'], $data['role']);

    // If the updated user is the currently logged-in user
    if ($_SESSION['user']['id'] == $id) {
        session_destroy();
        header("Location: ../views/auth/login.php");
        exit;
    }
    
    $_SESSION['success'] = "User updated successfully.";
    header("Location: ../views/admin/users");
    exit;
} elseif ($action === 'delete') {
    $id = $_POST['id'];
    User::delete($id);
    $_SESSION['success'] = "User deleted successfully.";
    header("Location: ../views/admin/users");
    exit;
} else {
    $_SESSION['error'] = "Invalid action.";
    header("Location: ../views/admin/users");
    exit;
}
?>
