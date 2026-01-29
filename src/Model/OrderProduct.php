<?php

namespace Model;

use Model\Model;

class OrderProduct extends Model
{
    public function create(int $orderId, int $productId, int $amount) {
        $stmt=$this->connection->prepare("INSERT INTO order_products (order_id, product_id, amount) 
        VALUES (:order_id, :product_id, :amount)");

        $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $productId,
            ':amount' => $amount
        ]);
    }





}