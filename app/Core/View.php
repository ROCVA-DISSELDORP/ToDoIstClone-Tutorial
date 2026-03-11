<?php
namespace App\Core;

class View {
    public static function render($path, $data = []) {
        extract($data);
        ob_start();
        include __DIR__ . "/../../resources/views/$path.php";
        $content = ob_get_clean();
        include __DIR__ . "/../../resources/views/layouts/main.php";
    }
}