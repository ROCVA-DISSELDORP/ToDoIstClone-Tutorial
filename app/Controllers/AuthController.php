<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Check of gebruiker is ingelogd
function checkLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Registreren
function register($name, $email, $password) {
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $hashedPassword]);
    } catch (Exception $e) {
        return false; // Email waarschijnlijk al in gebruik
    }
}

// Inloggen
function login($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        return true;
    }
    return false;
}

// Uitloggen
function logout() {
    session_destroy();
    header("Location: login.php");
    exit;
}