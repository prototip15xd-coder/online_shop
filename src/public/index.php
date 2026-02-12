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
$app->addRoute('/registration', 'GET', \Controllers\UserController::class , 'getRegistration');
$app->addRoute('/registration', 'POST', \Controllers\UserController::class , 'registration');
$app->addRoute('/login', 'GET', \Controllers\UserController::class , 'getLogin');
$app->addRoute('/login', 'POST', \Controllers\UserController::class , 'login');
$app->addRoute('/catalog', 'GET', \Controllers\ProductController::class , 'catalog');
$app->addRoute('/catalog', 'POST', \Controllers\ProductController::class, 'add_product');
$app->addRoute('/profile', 'GET', \Controllers\UserController::class , 'profile');
$app->addRoute('/profile', 'POST', \Controllers\UserController::class , 'profile');
$app->addRoute('/profile-edit', 'GET', \Controllers\UserController::class , 'profileEdit');
$app->addRoute('/profile-edit', 'POST', \Controllers\UserController::class , 'profileEdit');
$app->addRoute('/cart', 'GET', \Controllers\CartController::class , 'cart');
$app->addRoute('/logout', 'GET', \Controllers\UserController::class , 'logout');
$app->addRoute('/create-order', 'GET', \Controllers\OrderController::class , 'getCheckoutForm');
$app->addRoute('/create-order', 'POST', \Controllers\OrderController::class , 'handleCheckoutOrder');
$app->addRoute('/orders', 'GET', \Controllers\OrderController::class , 'getAllOrders');
$app->addRoute('/order', 'GET', \Controllers\OrderController::class , 'getOrderByOrderID');
$app->Run();
