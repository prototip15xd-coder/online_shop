<?php

namespace Controllers;
use Model\Product;

class ProductController
{
    private Product $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }
    public function catalog()
    {
        if (isset($_SESSION['userid'])) {
            $products = $this->productModel->productByDB();
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
                $this->productModel->validate_product();
                if ($this->productModel === 0) {
                    $errors['product_id'] = 'Такого товара не существует';
                } else {
                    $this->productModel->add_productDB();
                }
            }

        }
        $errors = $errors ?? [];
        $this->catalog();
    }

}