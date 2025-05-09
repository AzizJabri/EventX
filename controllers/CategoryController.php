<?php
session_start();
require_once '../models/Category.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../views/auth/login.php");
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        if (!empty($_POST['name'])) {
            Category::create($_POST['name']);
            $_SESSION['success'] = "Category added.";
        }
        break;

    case 'update':
        if (!empty($_POST['id']) && !empty($_POST['name'])) {
            Category::update($_POST['id'], $_POST['name']);
            $_SESSION['success'] = "Category updated.";
        }
        break;

    case 'delete':
        if (!empty($_GET['id'])) {
            Category::delete($_GET['id']);
            $_SESSION['success'] = "Category deleted.";
        }
        break;
}

header("Location: ../views/admin/categories");
exit;
