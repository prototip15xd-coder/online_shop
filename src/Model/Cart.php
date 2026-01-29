<?php

namespace Model;

class Cart extends Model
{
    public function cartbyDB()
    {
        $us_id = $_SESSION['userid'];

        $stms = $this->connection->prepare("SELECT products.name,
               products.description,
               products.price,
               products.image_url,
               user_products.amount 
               FROM user_products 
               JOIN products ON user_products.product_id = products.id
               WHERE user_products.user_id = :user_id");
        $stms->execute([':user_id' => $us_id]);
        $all_products = $stms->fetchAll(\PDO::FETCH_ASSOC);
        return $all_products;
    }

    public function getAllByUserId(int $us_id): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM user_products WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
        $all_products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $all_products;
    }

    public function deleteByUserId(int $us_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM user_products WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
    }
}