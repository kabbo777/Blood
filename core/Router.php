<?php
class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($requestUri, $requestMethod) {
        $parsedUrl = parse_url($requestUri, PHP_URL_PATH);
        
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && $scriptName !== '\\') {
            $parsedUrl = substr($parsedUrl, strlen($scriptName));
        }

        $path = '/' . trim($parsedUrl, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($requestMethod) && $route['path'] === $path) {
                list($controllerName, $actionName) = explode('@', $route['handler']);
                
                $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controller = new $controllerName();
                    $controller->$actionName();
                    return;
                }
            }
        }

        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1><p>No route matched URL: " . htmlspecialchars($path) . "</p>";
    }
}