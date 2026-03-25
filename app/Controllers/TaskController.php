<?php
require_once __DIR__ . '/../../config/database.php';

// Taken ophalen (Inbox, Project, of Vandaag)
function getTasks($userId, $projectId = null, $onlyToday = false) {
    global $pdo;
    $query = "SELECT t.*, p.name as project_name, p.color as project_color 
              FROM tasks t 
              LEFT JOIN projects p ON t.project_id = p.id 
              WHERE t.user_id = ?";
    $params = [$userId];

    if ($projectId === 'inbox') {
        $query .= " AND t.project_id IS NULL";
    } elseif ($projectId) {
        $query .= " AND t.project_id = ?";
        $params[] = $projectId;
    }

    if ($onlyToday) {
        $query .= " AND t.due_date = CURDATE()";
    }

    $query .= " ORDER BY t.is_completed ASC, t.due_date ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Nieuwe taak toevoegen
function createTask($userId, $title, $description, $dueDate, $projectId = null) {
    global $pdo;
    // Zet lege string om naar NULL voor de database
    $projectId = !empty($projectId) ? $projectId : null;
    
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, project_id, title, description, due_date) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$userId, $projectId, $title, $description, $dueDate]);
}

// Status wisselen (Voltooid / Niet voltooid)
function toggleTaskStatus($taskId, $userId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = ? AND user_id = ?");
    return $stmt->execute([$taskId, $userId]);
}

// Taak bewerken
function updateTask($taskId, $userId, $title, $description, $dueDate, $projectId) {
    global $pdo;
    $projectId = !empty($projectId) ? $projectId : null;
    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, due_date = ?, project_id = ? WHERE id = ? AND user_id = ?");
    return $stmt->execute([$title, $description, $dueDate, $projectId, $taskId, $userId]);
}

// Taak verwijderen
function deleteTask($taskId, $userId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    return $stmt->execute([$taskId, $userId]);
}