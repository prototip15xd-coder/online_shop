<?php

use Controllers\UserController;

$autoload = function(string $className) {
    $className = str_replace('\\', '/', $className);
    $path = "../$className.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};

spl_autoload_register($autoload);

$app = new \Core\App();
$app->get('/registration', \Controllers\UserController::class , 'getRegistration');
$app->post('/registration', \Controllers\UserController::class , 'registration');
$app->get('/login', \Controllers\UserController::class , 'getLogin');
$app->post('/login', \Controllers\UserController::class , 'login');
$app->get('/catalog', \Controllers\ProductController::class , 'catalog');
$app->post('/catalog', \Controllers\ProductController::class, 'catalog');
$app->get('/profile', \Controllers\UserController::class , 'profile');
$app->post('/profile', \Controllers\UserController::class , 'profile');
$app->get('/profile-edit', \Controllers\UserController::class , 'profileEdit');
$app->post('/profile-edit', \Controllers\UserController::class , 'profileEdit');
$app->get('/cart', \Controllers\CartController::class , 'cart');
$app->get('/logout', \Controllers\UserController::class , 'logout');
$app->get('/create-order', \Controllers\OrderController::class , 'getCheckoutForm');
$app->post('/create-order', \Controllers\OrderController::class , 'handleCheckoutOrder');
$app->get('/orders', \Controllers\OrderController::class , 'getAllOrders');
$app->get('/order', \Controllers\OrderController::class , 'getOrderByOrderID');
$app->post('/product', \Controllers\ProductController::class , 'product');
$app->Run();
