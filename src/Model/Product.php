<?php

class Product
{
    public function productByDB()
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $stms = $pdo->query('SELECT * FROM products');
        $products = $stms->fetchAll();
        return $products;
    }

    public function validate_product()
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stms = $pdo->prepare("SELECT id FROM products WHERE id = :product_id");
        $stms->execute(['product_id' => $_POST['product_id']]);
        return $stms->rowCount();
    }
    public function add_productDB()
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stms = $pdo->prepare("SELECT id FROM products WHERE id = :product_id");
        $stms->execute(['product_id' => $_POST['product_id']]);
        $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $user_p = $pdo->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id ");
        $user_p->execute(['user_id' => $_SESSION['userid'], 'product_id' => $_POST['product_id']]);
        $existingRecord = $user_p->fetch(PDO::FETCH_ASSOC);
        if ($existingRecord) {
            $stmt = $pdo->prepare("UPDATE user_products SET amount = amount + :amount WHERE user_id = :user_id AND product_id = :product_id");
        }
        $stmt->execute([
            'user_id' => $_SESSION['userid'],
            'product_id' => $_POST['product_id'],
            'amount' => $_POST['amount']
        ]);
    }
}