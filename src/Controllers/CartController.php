<?php

namespace Controllers;

use Model\Product;
use Model\UserProduct;
use Service\AuthService;
use Service\CartService;

class CartController
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private CartService $cartService;
    private AuthService $authService;

    public function __construct() {
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->cartService = new CartService();
        $this->authService = new AuthService();
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
    public function add_product_validate($action)
    {
        $errors = [];
        $product_id = $_POST["product_id"];
        $objUserProduct = $this->userProductModel->userProductByDB($product_id);
        $amount = $objUserProduct->getAmount();
        if ($this->authService->check()) {
            $res = $this->productModel->validate_product();
            if (!isset($res)) {
                $errors['product_id'] = 'Данный товар не существует или закончился';
            } else {
                if ($action === 'minus' || $action === 'remove') {
                    $amount -= 1;
                    if ($amount < 0) {
                        $errors['amount'] = 'Количество товаров должно быть больше нуля';
                    }
                }
            }
        }
        return $errors;
    }

    public function add_product()
    {
        $errors = $this->add_product_validate($_POST['action']);
        if (empty($errors)) {
            $this->cartService->add_product();
        }
    }

}