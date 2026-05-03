<?php
use App\Services\Router;

require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';

require_once __DIR__ . '/../app/middlewares/LoggedInMiddleware.php';
require_once __DIR__ . '/../app/middlewares/RoleMiddleware.php';

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


$router->add_middleware('/user/create', 'GET', $LoggedInMiddleware);
$router->add_middleware('/user/create', 'GET', $AdminMiddleware);
$router->add('/user/create', 'GET', function () use ($userController) {
    $userController->createUserForm();
});

$router->add_middleware('/user/create', 'POST', $LoggedInMiddleware);
$router->add_middleware('/user/create', 'POST', $AdminMiddleware);
$router->add('/user/create', 'POST', function () use ($userController) {
    $userController->store();
});

$router->add_middleware('/orders', 'GET', $LoggedInMiddleware);
$router->add('/orders', 'GET', function () use ($orderController) {
    $orderController->history();
});

$router->add_middleware('/admin/checks', 'GET', $LoggedInMiddleware);
$router->add_middleware('/admin/checks', 'GET', $AdminMiddleware);
$router->add('/admin/checks', 'GET', function () use ($orderController) {
    $orderController->adminChecks();
});

$router->add_middleware('/admin/orders', 'GET', $LoggedInMiddleware);
$router->add_middleware('/admin/orders', 'GET', $AdminMiddleware);
$router->add('/admin/orders', 'GET', function () use ($orderController) {
    $orderController->adminOrders();
});

$router->add_middleware('/admin/orders/deliver', 'POST', $LoggedInMiddleware);
$router->add_middleware('/admin/orders/deliver', 'POST', $AdminMiddleware);
$router->add('/admin/orders/deliver', 'POST', function () use ($orderController) {
    $orderController->deliver();
});

$router->add_middleware('/orders/confirm', 'POST', $LoggedInMiddleware);
$router->add('/orders/confirm', 'POST', function () use ($orderController) {
    $orderController->store();
});

$router->add_middleware('/orders/cancel', 'POST', $LoggedInMiddleware);
$router->add('/orders/cancel', 'POST', function () use ($orderController) {
    $orderController->cancel();
});

$router->add_middleware('/logout', 'GET', $LoggedInMiddleware);
$router->add('/logout', 'GET', function () use ($userController) {
    $userController->logout();
});

//------------------------------ADMIN USERS------------------------------

$router->add_middleware('/admin/users', 'GET', $LoggedInMiddleware);
$router->add_middleware('/admin/users', 'GET', $AdminMiddleware);
$router->add('/admin/users', 'GET', function () use ($userController) {
    $userController->listUsers();
});

$router->add_middleware('/admin/users', 'PUT', $LoggedInMiddleware);
$router->add_middleware('/admin/users', 'PUT', $AdminMiddleware);
$router->add('/admin/users', 'PUT', function () use ($userController) {
    $userController->updateUser();
});

$router->add_middleware('/admin/users/edit', 'GET', $LoggedInMiddleware);
$router->add_middleware('/admin/users/edit', 'GET', $AdminMiddleware);
$router->add('/admin/users/edit', 'GET', function () use ($userController) {
    $userController->editUser();
});

$router->add_middleware('/admin/users/delete', 'POST', $LoggedInMiddleware);
$router->add_middleware('/admin/users/delete', 'POST', $AdminMiddleware);
$router->add('/admin/users/delete', 'POST', function () use ($userController) {
    $userController->deleteUser();
});

//------------------------------PRODUCTS------------------------------
// get all products
$router->add('/products', 'GET', function () use ($productController) {
    $productController->home();
});
// delete product

$router->add_middleware('/products', 'DELETE', $LoggedInMiddleware);
$router->add_middleware('/products', 'DELETE', $AdminMiddleware);
$router->add('/products', 'DELETE', function () use ($productController) {
    $productController->destroy();
});
// update product

$router->add_middleware('/products', 'PUT', $LoggedInMiddleware);
$router->add_middleware('/products', 'PUT', $AdminMiddleware);
$router->add('/products', 'PUT', function () use ($productController) {
    $productController->update();
});
// create a product

$router->add_middleware('/products', 'POST', $LoggedInMiddleware);
$router->add_middleware('/products', 'POST', $AdminMiddleware);
$router->add('/products', 'POST', function () use ($productController) {
    $productController->create();
});
// get the add product page

$router->add_middleware('/products/create', 'GET', $LoggedInMiddleware);
$router->add_middleware('/products/create', 'GET', $AdminMiddleware);
$router->add('/products/create', 'GET', function () use ($productController) {
    $productController->add();
});
// get the update product page

$router->add_middleware('/products/edit', 'GET', $LoggedInMiddleware);
$router->add_middleware('/products/edit', 'GET', $AdminMiddleware);
$router->add('/products/edit', 'GET', function () use ($productController) {
    $productController->edit();
});
// make product available or unavailable

$router->add_middleware('/products/availability', 'POST', $LoggedInMiddleware);
$router->add_middleware('/products/availability', 'POST', $AdminMiddleware);
$router->add('/products/availability', 'POST', function () use ($productController) {
    $productController->availability();
});
return $router;

?>