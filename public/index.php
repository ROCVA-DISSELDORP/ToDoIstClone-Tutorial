<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/functions.php';

$router = new App\Core\Router();
require_once __DIR__ . '/../routes/web.php';

// Pak de volledige URL (bijv: /ToDoIst-Clone/login)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Pak het pad naar de folder waar index.php in staat (bijv: /ToDoIst-Clone/public)
$scriptPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); 
$basePath = str_replace('/public', '', $scriptPath);

// Verwijder de submap uit de URI
if ($basePath !== '' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Zorg dat we altijd eindigen met een propere string (bijv: /login of /)
$uri = '/' . trim($uri, '/');

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

// DEBUG REGEL: Haal dit weg als het werkt!
// die("De router probeert deze route te vinden: " . $uri);

$router->resolve($uri, $method);