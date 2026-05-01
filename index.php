<?php
session_start();

require_once __DIR__ . '/core/globals.php';
require_once __DIR__ . '/app/services/Auth.php';
require_once __DIR__ . '/app/services/Router.php';
require_once __DIR__ . '/app/services/Database.php';
require_once __DIR__ . '/app/services/DatabaseInitializer.php';
require_once __DIR__ . '/app/models/user.php';
require_once __DIR__ . '/app/controllers/userController.php';

use App\Services\Auth;

DatabaseInitializer::ensureSetup();

$router = require_once __DIR__ . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_URI']);

?>
