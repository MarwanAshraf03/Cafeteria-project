<?php

namespace App\Services;

class Router {
    private $routes = [];
    public function add($uri, $method, $handler) {
        $this->routes[$uri][$method] = $handler;
    }
    public function dispatch($requestUri) {
        $uri = parse_url($requestUri, PHP_URL_PATH);
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $normalizedBasePath = strtolower($basePath);
        $normalizedUri = strtolower($uri);
        if ($basePath !== '/' && strpos($normalizedUri, $normalizedBasePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$uri][$method])) {
            $handler = $this->routes[$uri][$method];
            
            if (is_callable($handler)) {
                return $handler();
            }
            if (is_string($handler)) {
                if ($handler == 'style.css') {
                    header('Content-Type: text/css');
                    $viewPath = __DIR__ . '/../../public/css/' . $handler;
                }else {
                    $viewPath = __DIR__ . '/../../views/pages/' . $handler;
                }
                if (file_exists($viewPath)) {
                    $user = \App\Services\Auth::user();
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
