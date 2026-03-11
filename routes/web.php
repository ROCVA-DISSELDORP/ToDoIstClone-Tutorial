<?php
// Home
$router->get('/', 'TaskController@index');

// Taken
$router->post('/tasks/create', 'TaskController@store');
$router->post('/tasks/toggle', 'TaskController@toggle');

// Projecten (nu met dynamische ID!)
$router->get('/projects/{id}', 'ProjectController@show');

// Auth
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');