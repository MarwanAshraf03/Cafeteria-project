<?php
use App\Services\Router;

require_once __DIR__ . '/../app/controllers/userController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';

$userController = new UserController();
$orderController = new OrderController();
$router = new Router();
$router->add('/', 'GET',function()use($orderController){
    $orderController->home();
});
$router->add('/home', 'GET',function()use($orderController){
    $orderController->home();
});
$router->add('/login', 'GET','login.php');
$router->add('/login', 'POST',function()use($userController){
    $userController->login();
});
$router->add('/style', 'GET','style.css');
$router->add('/user/create', 'GET','create-user.php');
$router->add('/user/create', 'POST',function()use($userController){
    $userController->store();
});
$router->add('/orders', 'GET',function()use($orderController){
    $orderController->history();
});
$router->add('/admin/checks', 'GET',function()use($orderController){
    $orderController->adminChecks();
});
$router->add('/orders/confirm', 'POST',function()use($orderController){
    $orderController->store();
});
$router->add('/orders/cancel', 'POST',function()use($orderController){
    $orderController->cancel();
});
$router->add('/logout', 'GET', function() use ($userController) {
    $userController->logout();
});
return $router;

?>