<?php


class CartController
{
    public function cart()
    {
        if (isset($_SESSION['userid'])) {
            require_once '../Model/Cart.php';
            $cartModel = new Cart();
            $result = $cartModel->cartByDB();
            require_once '/var/www/html/src/Views/cart.php';
        } else {
            //require_once '/var/www/html/src/Controllers/UserControllers.php';   ????
            //$userController = new UserController();
            //$userController->login($_POST);

            require_once '/var/www/html/src/Views/login.php';   ///КЛАСС ИЛИ ФУНКЦИЮ ЗОВИ или я страницу вызываю? или пхп код?...
        }

    }

}