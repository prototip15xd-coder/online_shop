<?php

namespace Model;

use Model\Model;

class OrderProduct extends Model
{
    private int $id;
    private int $order_id;
    private int $product_id;
    private int $amount;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
    protected function getTableName(): string
    {
        return "order_products";
    }

    public function objOrderProduct($product){
        $obj = new self();
        $obj->id = $product["id"];
        $obj->order_id = $product["order_id"];
        $obj->product_id = $product["product_id"];
        $obj->amount = $product["amount"];
        return $obj;
    }
    public function createOrderProduct(int $orderId, int $productId, int $amount) {
        $stmt=$this->connection->prepare("INSERT INTO {$this->getTableName()} (order_id, product_id, amount) 
        VALUES (:order_id, :product_id, :amount)");

        $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $productId,
            ':amount' => $amount
        ]);
    }

    public function getAllProductFromOrderByOrderId(int $orderId): array {
        $stmt=$this->connection->prepare("SELECT * FROM {$this->getTableName()} WHERE order_id = :order_id");
        $stmt->execute(['order_id' => $orderId]);
        $products= $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $all_products_from_order=[];
        foreach ($products as $product) {
            $obj = $this->objOrderProduct($product);
            $all_products_from_order[] = $obj;
        }
        return $all_products_from_order;
    }


}