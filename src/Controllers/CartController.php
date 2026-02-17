<?php

namespace Controllers;

use Model\Product;
use Model\UserProduct;

class CartController
{
    private UserProduct $userProductModel;
    private Product $productModel;

    public function __construct() {
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
    }
//    public function cart() /// старая версия
//    {
//        if (isset($_SESSION['userid'])) {
//            $all_products = $this->cartModel->cartbyDB();
//            require_once '/var/www/html/src/Views/cart.php';
//        } else {
//            require_once '/var/www/html/src/Views/login.php';
//        }
//
//    }

    public function cart() /// новая где нет совмещения двух таблиц!
    {
        if (isset($_SESSION['userid'])) {
            $user_products= $this->userProductModel->userProducts($_SESSION['userid']);  //здесь храниться массив объектов
            $all_products = [];
            foreach ($user_products as $user_product) {
                $product_id = $user_product->getProductId();
                $product = $this->productModel->productByproductId($product_id);/////должен быть объект продукта
                $product_amount = $user_product->getAmount(); /// просто количество
                $product->amount = $product_amount;
                $all_products[] = $product;
            }
            require_once '/var/www/html/src/Views/cart.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }

    }

}