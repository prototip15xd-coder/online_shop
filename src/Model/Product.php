<?php

namespace Model;

class Product extends Model
{
        public function productByDB()
    {
        $stms = $this->connection->query('SELECT * FROM products');
        $products = $stms->fetchAll();
        return $products;
    }

    public function validate_product()
    {
        $stms = $this->connection->prepare("SELECT id FROM products WHERE id = :product_id");
        $stms->execute(['product_id' => $_POST['product_id']]);
        return $stms->rowCount();
    }
    public function add_productDB()
    {
        $stms = $this->connection->prepare("SELECT id FROM products WHERE id = :product_id");
        $stms->execute(['product_id' => $_POST['product_id']]);
        $stmt = $this->connection->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $user_p = $this->connection->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id ");
        $user_p->execute(['user_id' => $_SESSION['userid'], 'product_id' => $_POST['product_id']]);
        $existingRecord = $user_p->fetch(\PDO::FETCH_ASSOC);
        if ($existingRecord) {
            $stmt = $this->connection->prepare("UPDATE user_products SET amount = amount + :amount WHERE user_id = :user_id AND product_id = :product_id");
        }
        $stmt->execute([
            'user_id' => $_SESSION['userid'],
            'product_id' => $_POST['product_id'],
            'amount' => $_POST['amount']
        ]);    //как сделать так чтобы можно было добавлять не все товары
    }
}