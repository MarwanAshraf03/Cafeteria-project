<?php

namespace App\Services;

class Router {
    private $routes = [];
    public function add($uri, $handler) {
        $this->routes[$uri] = $handler;
    }
    public function dispatch($requestUri) {
        $uri = parse_url($requestUri, PHP_URL_PATH);
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');
        if (isset($this->routes[$uri])) {
            $handler = $this->routes[$uri];
            
            if (is_callable($handler)) {
                return $handler();
            }
            if (is_string($handler)) {
                $viewPath = __DIR__ . '/../../views/pages/' . $handler;
                if (file_exists($viewPath)) {
                    include $viewPath;
                    return;
                }
            }
        }
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "The page you requested was not found: " . htmlspecialchars($uri);
    }
}
