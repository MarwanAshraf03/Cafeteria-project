<?php

use App\Services\Router;

$router = new Router();
$router->add('/', 'home.php');
$router->add('/home', 'home.php');
$router->add('/login', 'login.php');
$router->add('/style', 'style.css');
$router->add('/user/create', 'create-user.php');
return $router;

?>