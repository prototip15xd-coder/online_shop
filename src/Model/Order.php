<?php

namespace Model;

use Model\Model;

class Order extends Model
{
    public function create(
        string $name,
        string $phone,
        string $comment,
        string $address,
        int $userId
    )
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO orders (contact_name, contact_phone, comment, address, user_id) 
                    VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id"
        );
        $stmt->execute([
            'name'=>$name,
            'phone'=>$phone,
            'comment'=>$comment,
            'address'=>$address,
            'user_id'=>$userId
        ]);

        $data = $stmt->fetch();
        return $data['id'];

    }

    public function getOrders(int $us_id): array
    {
        $stmt = $this->connection->prepare("SELECT orders.id,
                       SUM(order_products.amount) as amount,
                       MAX(orders.contact_name) as contact_name,
                       MAX(orders.contact_phone) as contact_phone,
                       MAX(orders.comment) as comment,
                       MAX(orders.address) as address
                       FROM order_products 
                       JOIN orders ON order_products.order_id = orders.id
                       WHERE orders.user_id = :user_id
                       GROUP BY orders.id");
        $stmt->execute([':user_id' => $us_id]);
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $orders;
    }


}