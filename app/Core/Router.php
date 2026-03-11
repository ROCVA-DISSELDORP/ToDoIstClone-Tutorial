<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($uri, $controller)
    {
        $this->add('GET', $uri, $controller);
    }
    public function post($uri, $controller)
    {
        $this->add('POST', $uri, $controller);
    }

    protected function add($method, $uri, $controller)
    {
        // Zet /projects/{id} om naar een regex: ^/projects/([0-9]+)$
        $route = preg_replace('/\{[a-zA-Z0-9]+\}/', '([0-9]+)', $uri);
        $this->routes[] = [
            'method' => $method,
            'uri' => '#^' . $route . '$#',
            'controller' => $controller
        ];
    }

    public function resolve($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && preg_match($route['uri'], $uri, $matches)) {
                array_shift($matches); // Verwijder de volledige match, hou alleen de ID over

                [$class, $action] = explode('@', $route['controller']);
                $class = "App\\Controllers\\" . $class;
                $instance = new $class();

                // Roep de functie aan en geef de matches (zoals ID) mee als argumenten
                return call_user_func_array([$instance, $action], $matches);
            }
        }
        http_response_code(404);
        echo "404 - Pagina niet gevonden: " . htmlspecialchars($uri);
    }
}
