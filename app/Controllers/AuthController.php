<?php
namespace App\Controllers;
use App\Models\User;
use App\Helpers\Auth;
use App\Core\View;

class AuthController {
    public function __construct() { Auth::init(); }

    public function showLogin() {
        if (Auth::user()) { header('Location: /'); exit; }
        // We renderen hier zonder de standaard layout voor een schone login pagina
        include __DIR__ . '/../../resources/views/auth/login.php';
    }

    public function login() {
        $user = User::findByEmail($_POST['email']);
        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            header('Location: ' . url('/'));
            exit;
        }
        $error = "Onjuiste gegevens";
        include __DIR__ . '/../../resources/views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: ' . url('/login')); // Veranderd naar url('/login')
    }
}