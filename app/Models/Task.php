<?php
namespace App\Models;

class Task {
    public static function getToday($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM tasks WHERE user_id = ? AND (due_date = CURDATE() OR due_date IS NULL) AND is_completed = 0");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO tasks (user_id, title, project_id, due_date) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['user_id'], $data['title'], $data['project_id'], $data['due_date']]);
    }

    public static function toggle($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = ?");
        return $stmt->execute([$id]);
    }
}