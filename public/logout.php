<?php
require_once __DIR__ . '/../app/Controllers/AuthController.php';

// AuthController::logout() doet session_destroy() en redirect naar login.php.
logout();
exit;

