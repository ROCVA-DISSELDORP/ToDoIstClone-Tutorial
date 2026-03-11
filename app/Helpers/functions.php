<?php
// app/Helpers/functions.php

function url($path = '') {
    // Haal het pad naar de huidige map op (bijv. /ToDoIst-Clone/public)
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = str_replace('/public/index.php', '', $scriptName);
    
    // Plak het gevraagde pad erachter
    return rtrim($basePath, '/') . '/' . ltrim($path, '/');
}