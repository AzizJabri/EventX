<?php

require_once __DIR__ . '/../models/User.php';
session_start();

$action = $_POST['action'] ?? '';

$userModel = new User();

if ($action === 'register') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (strlen($name) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $_SESSION['error'] = "Invalid input.";
        header("Location: /views/auth/register.php");
        exit;
    }

    $userModel->setName($name);
    $userModel->setEmail($email);
    $userModel->setPassword($password);
    $userModel->setRole('client');

    if ($userModel->findByEmail($email)) {
        $_SESSION['error'] = "Email already exists.";
        header("Location: /views/auth/register.php");
        exit;
    }

    if ($userModel->save()) {
        $_SESSION['success'] = "Account created successfully.";
        header("Location: /views/auth/login.php");
    } else {
        $_SESSION['error'] = "Account creation failed.";
        header("Location: /views/auth/register.php");
    }

    exit;
}

if ($action === 'login') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        $_SESSION['error'] = "Invalid credentials.";
        header("Location: /views/auth/login.php");
        exit;
    }

    if ($userModel->login($email, $password)) {
        $_SESSION['user'] = [
            'id' => $userModel->getId(),
            'name' => $userModel->getName(),
            'email' => $userModel->getEmail(),
            'role' => $userModel->getRole()
        ];
        if ($userModel->getRole() === 'admin') {
            header("Location: /views/admin/");
        } else {
            header("Location: /views/client/");
        }
        exit;
    } else {
        $_SESSION['error'] = "Incorrect email or password.";
        header("Location: /views/auth/login.php");
        exit;
    }
}
