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

function getUserName(){
    $user = getUser();
    return $user['name'] ?? null;
}

// Haalt een gebruiker op uit de database.
// - Zonder $userId: gebruikt de huidige sessie ($_SESSION['user_id']).
// - Met $userId: ophaal op basis van die id.
function getUser($userId = null) {
    global $pdo;

    if ($userId === null) {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        $userId = $_SESSION['user_id'];
    }

    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    $user = $stmt->fetch();
    return $user ?: null;
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