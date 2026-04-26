<?php

require_once __DIR__ . '/app/services/Router.php';

$router = require_once __DIR__ . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_URI']);
