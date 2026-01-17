<?php

class ProductController
{
    public function catalog()
    {
        if (isset($_SESSION['userid'])) {
            require_once '../Model/Product.php';
            $productModel = new Product();
            $products = $productModel->productByDB();
            require_once '/var/www/html/src/Views/catalog.php';
        } else {
            require_once '/var/www/html/src/public/login'; //КЛАСС ИЛИ ФУНКЦИЮ ВЫЗЫВАЙ

        }
    }

    public function add_product_validate($POST_DATA, $SESSION_DATA)
    {
        $errors = [];

        if (!isset($SESSION_DATA['userid'])) {
            require_once '/var/www/html/src/public/login';
        } else {
            $amount = $POST_DATA['amount'];
            if (empty($POST_DATA['product_id']) || empty($POST_DATA['amount'])) {
                $errors['product_id'] = 'Выберите товар и количество';
            } else {
                if (!is_numeric($amount) || $amount <= 0) {
                    $errors['amount'] = 'Количество товаров должно быть корректным числом';
                }
            }
        }
        return $errors;
    }

    public function add_product($POST_DATA, $SESSION_DATA)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errors = $this->add_product_validate($_POST, $_SESSION);
            if (empty($errors)) {

                require_once '../Model/Product.php';
                $productModel = new Product();
                $productModel->validate_product();
                if ($productModel === 0) {
                    $errors['product_id'] = 'Такого товара не существует';
                } else {
                    $productModel->add_productDB();
                }
            }

        }
        $errors = $errors ?? [];
        require_once '/var/www/html/src/Views/add_product.php';
    }

}