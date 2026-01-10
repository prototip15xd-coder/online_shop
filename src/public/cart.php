<?php

session_start();

if (isset($_SESSION['userid'])) {
    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
    $us_id = $_SESSION['userid'];


    $stms = $pdo->prepare("SELECT products.name,
       products.description,
       products.price,
       products.image_url,
       user_products.amount 
       FROM user_products 
       JOIN products ON user_products.product_id = products.id
       WHERE user_products.user_id = :user_id");
    $stms ->execute(['user_id'=> $us_id]);
    $all_products = $stms->fetchAll(PDO::FETCH_ASSOC);
/*
    $all_products = [];
    foreach ($products as $product) {
        $product_id = $product['product_id'];
        $stms_url = $pdo->prepare("SELECT * FROM products WHERE id = :prod_id");
        $stms_url ->execute(['prod_id' => $product_id]);
        $prod_inf = $stms_url->fetch(PDO::FETCH_ASSOC);
        $prod_inf['amount'] = $product['amount'];
        $all_products[] = $prod_inf;

    }
*/
    require_once './cart_page.php';
} else {
    header("Location: /login");
}

