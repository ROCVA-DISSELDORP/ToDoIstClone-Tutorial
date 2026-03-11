<?php

namespace App\Helpers;

class Auth
{
    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
    }
    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }
    public static function check()
    {
        if (!self::user()) {
            header('Location: ' . url('/login')); // Gebruik de url() helper
            exit;
        }
    }
}
