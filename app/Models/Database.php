<?php
namespace App\Models;
use PDO;

class Database {
    private static $instance = null;
    public static function getConnection() {
        if (!self::$instance) {
            $c = require __DIR__ . '/../../config/database.php';
            self::$instance = new PDO("mysql:host={$c['host']};dbname={$c['db']}", $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$instance;
    }
}