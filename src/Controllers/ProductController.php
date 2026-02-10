<?php

namespace Controllers;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;

class ProductController
{
    private Product $productModel;
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;


    public function __construct() {
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProduct();
    }
    public function catalog()
    {
        if (isset($_SESSION['userid'])) {
            $products = $this->productModel->productByDB();
            $productsAmount = [];
            foreach ($products as $product) {
                $product_id = $product->getId();
                $user_product = $this->userProductModel->userProductByDB($product_id);
                $amount = $user_product->getAmount();
                $product->setAmount($amount);
            }
            require_once '/var/www/html/src/Views/catalog.php';
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
        if (!isset($_SESSION['userid'])) {
            require_once '/var/www/html/src/Views/login';
        } else {
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
            $action = $_POST['action'] ?? $_POST['what'] ?? '';
            print_r($action);
            if ($action === 'plus') {
                $this->productModel->add_productDB();
            } else if ($action === 'minus' || $action === 'remove') {
                $this->productModel->delete_productDB();
            }
            $products = $this->catalog();
            require_once '/var/www/html/src/Views/catalog.php';
        } else {
            return $errors;
        }

    }
}