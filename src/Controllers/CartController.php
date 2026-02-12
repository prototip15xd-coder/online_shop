<?php

namespace Controllers;

use Model\Cart;
use Model\Product;
use Service\AuthService;

class CartController extends BaseController
{
    private Cart $cartModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
    }

    public function cart()
    {
        if ($this->authService->check()) {
            $all_products = $this->cartModel->cartbyDB();
            require_once '/var/www/html/src/Views/cart.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }

    }

}