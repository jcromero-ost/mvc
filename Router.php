<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Soporte opcional para URLs con /index.php al inicio
        $path = str_replace('/index.php', '', $path);

        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            http_response_code(404);
            echo "404 - Página no encontrada";
            return;
        }

        if (is_callable($callback)) {
            call_user_func($callback);
        } elseif (is_string($callback)) {
            $this->callController($callback);
        }
    }

    private function callController($callback) {
        [$controllerName, $method] = explode('@', $callback);
        $filePath = "controllers/$controllerName.php";

        if (!file_exists($filePath)) {
            http_response_code(500);
            echo "Error: El controlador $controllerName no existe.";
            exit;
        }

        require_once $filePath;
        $controller = new $controllerName;

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "Error: El método $method no existe en $controllerName.";
            exit;
        }

        call_user_func([$controller, $method]);
    }
}
