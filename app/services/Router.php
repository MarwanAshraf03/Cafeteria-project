<?php

namespace App\Services;

class Router
{
    private $routes = [];
    private $routes_middleware = [];
    public function add($uri, $method, $handler)
    {
        $this->routes[$uri][$method] = $handler;
    }

    public function add_middleware($uri, $method, $handler)
    {
        $this->routes_middleware[$uri][$method][$handler["key"]] = $handler;
        // $this->routes_middleware[$uri][$method] = $handler;
    }
    public function dispatch($requestUri)
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $normalizedBasePath = strtolower($basePath);
        $normalizedUri = strtolower($uri);
        if ($basePath !== '/' && strpos($normalizedUri, $normalizedBasePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $result = $this->check_middleware($uri, $method);
        if (!$result['success']) {
            header("HTTP/1.0 401 Unauthorized");
            echo "<h1>401 Unauthorized</h1>";
            echo "You are unauthorized to access this page: " . htmlspecialchars($uri);
            echo "<pre>";
            echo "" . $result['errors'] . "\n";
            echo "<pre>";
            // exit;
            // header("Location: " . Auth::check() ? base_path("login") : base_path("login"));
            exit;
        }
        if (isset($this->routes[$uri][$method])) {
            $handler = $this->routes[$uri][$method];

            if (is_callable($handler)) {
                return $handler();
            }
            if (is_string($handler)) {
                if ($handler == 'style.css') {
                    header('Content-Type: text/css');
                    $viewPath = __DIR__ . '/../../public/css/' . $handler;
                } else {
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
    private function check_middleware($uri, $method)
    {
        $success = true;
        $errors = [];

        if (isset($this->routes_middleware[$uri][$method])) {
            $handlers = $this->routes_middleware[$uri][$method];

            foreach ($handlers as $handler) {
                if (!(bool) $handler["handler"]()) {
                    $success = false;
                    $errors[] = $handler["error_message"];
                }
            }
            /*
            ["success"=> true or false, "errors" => $errors]
            */
            $errors = implode(", ", $errors);
            // if (is_callable($handler["handler"])) {
            //     return (bool) $handler["handler"]();
            // }

            // if (!$success) {
            //     return ["success" => $success, "errors" => $errors];
            // }
        }
        // } else {
        //     // return true;
        return ["success" => $success, "errors" => $errors];

        // }
    }
}
