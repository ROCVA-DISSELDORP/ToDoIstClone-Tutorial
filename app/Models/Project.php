<?php
namespace App\Models;

class Project {
    public static function all($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM projects WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function find($id, $userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public static function getTasks($projectId, $userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM tasks WHERE project_id = ? AND user_id = ? ORDER BY is_completed ASC, due_date ASC");
        $stmt->execute([$projectId, $userId]);
        return $stmt->fetchAll();
    }
}