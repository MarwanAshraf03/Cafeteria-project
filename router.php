<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$base = __DIR__;

// Serve static files directly (css, js, images, etc.)
if ($uri !== '/' && file_exists($base . '/public' . $uri)) {
    return false;
}

// Serve storage files directly (product images, user images, etc.)
if ($uri !== '/' && file_exists($base . $uri)) {
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png', 'gif' => 'image/gif',
        'webp' => 'image/webp', 'svg' => 'image/svg+xml',
    ];
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
        readfile($base . $uri);
        exit;
    }
}

// All other requests go through index.php
require_once $base . '/index.php';
