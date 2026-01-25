<?php


class CartController
{
    public function cart()
    {
        if (isset($_SESSION['userid'])) {
            $cartModel = new Cart();
            $result = $cartModel->cartByDB();
            require_once '/var/www/html/src/Views/cart.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }

    }

}