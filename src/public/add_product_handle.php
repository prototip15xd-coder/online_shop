<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function add_prod($POST_DATA) {
        $errors = [];
        session_start();

        if (!isset($_SESSION['userid'])) {
            header("Location: /login");
        } else {
            $product_id = $POST_DATA['product_id'];
            $amount = $POST_DATA['amount'];
            $user_id = $_SESSION['userid'];
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
            if (empty($POST_DATA['product_id']) || empty($POST_DATA['amount'])) {
                $errors['product_id'] = 'Выберите товар и количество';
            } else {
                $stms = $pdo->prepare("SELECT id FROM products WHERE id = :product_id");
                $stms->execute(['product_id' => $product_id]);
                if ($stms->rowCount() === 0) {
                    $errors['product_id'] = 'Такого товара не существует';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
                    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'amount' => $amount]); # НИЧЕ НЕ ДОБАВИЛОСЬ НУ ПИЗДЕЦ
                    }
                }
            }
            return $errors;
    }

        $errors = add_prod($_POST);
}


require_once './add_product_page.php';







