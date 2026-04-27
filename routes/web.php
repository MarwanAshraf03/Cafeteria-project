<?php
use App\Services\Router;

require_once __DIR__ . '/../app/controllers/userController.php';

$userController = new UserController();
$router = new Router();
$router->add('/', 'GET','home.php');
$router->add('/home', 'GET','home.php');
$router->add('/login', 'GET','login.php');
$router->add('/login', 'POST',function()use($userController){
    $userController->login();
});
$router->add('/style', 'GET','style.css');
$router->add('/user/create', 'GET','create-user.php');
$router->add('/user/create', 'POST',function()use($userController){
    $userController->store();
});
$router->add('/logout', 'GET', function() use ($userController) {
    $userController->logout();
});
return $router;

?>