<?php
require_once __DIR__ . '/../../config/database.php';

// Alle projecten van de user ophalen
function getAllProjects($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY name ASC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// Eén specifiek project ophalen (voor bewerken)
function getProjectById($projectId, $userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
    $stmt->execute([$projectId, $userId]);
    return $stmt->fetch();
}

// Nieuw project maken
function createProject($userId, $name, $color = '#808080') {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO projects (user_id, name, color) VALUES (?, ?, ?)");
    return $stmt->execute([$userId, $name, $color]);
}

// Project bewerken
function updateProject($projectId, $userId, $name, $color) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE projects SET name = ?, color = ? WHERE id = ? AND user_id = ?");
    return $stmt->execute([$name, $color, $projectId, $userId]);
}

// Project verwijderen
function deleteProject($projectId, $userId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
    return $stmt->execute([$projectId, $userId]);
}