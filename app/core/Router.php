<?php

class Router
{
    private $routes = [];

    public function add($route, $controller, $action)
    {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch()
    {
        $url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        foreach ($this->routes as $route => $details) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);
                $params = $matches;

                $controllerName = $details['controller'];
                $action = $details['action'];

                $controller = new $controllerName();
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // Handle 404 Not Found
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
    }
}
