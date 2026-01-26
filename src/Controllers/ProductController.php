<?php

namespace Controllers;
use Model\Product;

class ProductController
{
    public function catalog()
    {
        if (isset($_SESSION['userid'])) {
            $productModel = new Product();
            $products = $productModel->productByDB();
            require_once '/var/www/html/src/Views/catalog.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';

        }
    }

    public function add_product_validate()
    {
        $errors = [];

        if (!isset($_SESSION['userid'])) {
            require_once '/var/www/html/src/Views/login';
        } else {
            $amount = $_POST['amount'];
            if (empty($_POST['product_id']) || empty($_POST['amount'])) {
                $errors['product_id'] = 'Выберите товар и количество';
            } else {
                if (!is_numeric($amount) || $amount <= 0) {
                    $errors['amount'] = 'Количество товаров должно быть корректным числом';
                }
            }
        }
        return $errors;
    }

    public function add_product()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errors = $this->add_product_validate();
            if (empty($errors)) {
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
        $this->catalog();
    }

}