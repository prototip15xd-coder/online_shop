<?php

class Cart
{
    public function cartbyDB()
    {
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
        $stms->execute(['user_id' => $us_id]);
        $all_products = $stms->fetchAll(PDO::FETCH_ASSOC);
        return $all_products;
    }
}