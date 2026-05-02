<?php
use App\Services\Router;

require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';

$userController = new UserController();
$orderController = new OrderController();
$productController = new ProductController();

$router = new Router();
$router->add('/', 'GET', function () use ($orderController) {
    $orderController->home();
});
$router->add('/home', 'GET', function () use ($orderController) {
    $orderController->home();
});
$router->add('/login', 'GET', 'login.php');
$router->add('/login', 'POST', function () use ($userController) {
    $userController->login();
});
$router->add('/style', 'GET', 'style.css');
$router->add('/user/create', 'GET', 'create-user.php');
$router->add('/user/create', 'POST', function () use ($userController) {
    $userController->store();
});
$router->add('/orders', 'GET', function () use ($orderController) {
    $orderController->history();
});
$router->add('/admin/checks', 'GET',function()use($orderController){
    $orderController->adminChecks();
});
$router->add('/admin/orders', 'GET', function() use ($orderController) {
    $orderController->adminOrders();
});
$router->add('/admin/orders/deliver', 'POST', function() use ($orderController) {
    $orderController->deliver();
});
$router->add('/orders/confirm', 'POST',function()use($orderController){
    $orderController->store();
});
$router->add('/orders/cancel', 'POST', function () use ($orderController) {
    $orderController->cancel();
});
$router->add('/logout', 'GET', function () use ($userController) {
    $userController->logout();
});

//------------------------------ADMIN USERS------------------------------
$router->add('/admin/users', 'GET', function () use ($userController) {
    $userController->listUsers();
});
$router->add('/admin/users', 'PUT', function () use ($userController) {
    $userController->updateUser();
});
$router->add('/admin/users/edit', 'GET', function () use ($userController) {
    $userController->editUser();
});
$router->add('/admin/users/delete', 'POST', function () use ($userController) {
    $userController->deleteUser();
});

//------------------------------PRODUCTS------------------------------
// get all products
$router->add('/products', 'GET', function () use ($productController) {
    $productController->home();
});
// delete product
$router->add('/products', 'DELETE', function () use ($productController) {
    $productController->destroy();
});
// update product
$router->add('/products', 'PUT', function () use ($productController) {
    $productController->update();
});
// create a product
$router->add('/products', 'POST', function () use ($productController) {
    $productController->create();
});
// get the add product page
$router->add('/products/create', 'GET', function () use ($productController) {
    $productController->add();
});
// get the update product page
$router->add('/products/edit', 'GET', function () use ($productController) {
    $productController->edit();
});
// make product available or unavailable
$router->add('/products/availability', 'POST', function () use ($productController) {
    $productController->availability();
});
return $router;

?>