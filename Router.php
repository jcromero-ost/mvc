<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }

    private function addRoute($method, $path, $callback) {
        // Convertir {param} en expresiones regulares
        $pathRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $path);
        $pathRegex = '#^' . $pathRegex . '$#';

        $this->routes[$method][] = ['pattern' => $pathRegex, 'callback' => $callback];
    }

    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/index.php', '', $path);

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo "404 - Método no soportado";
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (is_callable($route['callback'])) {
                    call_user_func_array($route['callback'], $params);
                    return;
                } elseif (is_string($route['callback'])) {
                    $this->callController($route['callback'], $params);
                    return;
                }
            }
        }

        http_response_code(404);
        echo "404 - Página no encontrada";
    }

    private function callController($callback, $params = []) {
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

        call_user_func_array([$controller, $method], $params);
    }
}
