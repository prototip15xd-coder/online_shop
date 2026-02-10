<?php

namespace Controllers;

use Model\Cart;
use Model\Product;

class CartController
{
    private Cart $cartModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
    }

    public function cart()
    {
        if (isset($_SESSION['userid'])) {
            $all_products = $this->cartModel->cartbyDB();
            require_once '/var/www/html/src/Views/cart.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }

    }

}