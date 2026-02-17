<?php

namespace Model;

use Model\Model;

class Order extends Model
{
    private int $id;
    private int $amount;
    private string $contact_name;
    private string $contact_phone;
    private string $comment;
    private string $address;
    private array $products;
    private array $amountProduct;

    public function getAmountProduct(): array
    {
        return $this->amountProduct;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getAmount(): int
    {
        return $this->amount;
    }
    public function getContactName(): string
    {
        return $this->contact_name;
    }

    public function getContactPhone(): string
    {
        return $this->contact_phone;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
    protected function getTableName(): string
    {
        return "orders";
    }

    public function objOrder($order){
        $obj = new self();
        $obj->id = $order['id'];
        $obj->amount = $order['amount'];
        $obj->contact_name = $order['contact_name'];
        $obj->contact_phone = $order['contact_phone'];
        $obj->comment = $order['comment'];
        $obj->address = $order['address'];
        return $obj;
    }

    public function create( ///можно ли избравиться от этого?
        string $name,
        string $phone,
        string $comment,
        string $address,
        int $userId
    )

    {
        $stmt = $this->connection->prepare(
            "INSERT INTO {$this->getTableName()} (contact_name, contact_phone, comment, address, user_id) 
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
        $stmt = $this->connection->prepare("SELECT {$this->getTableName()}.id,
                       SUM(order_products.amount) as amount,
                       MAX({$this->getTableName()}.contact_name) as contact_name,
                       MAX({$this->getTableName()}.contact_phone) as contact_phone,
                       MAX({$this->getTableName()}.comment) as comment,
                       MAX({$this->getTableName()}.address) as address
                       FROM order_products 
                       JOIN {$this->getTableName()} ON order_products.order_id = {$this->getTableName()}.id
                       WHERE {$this->getTableName()}.user_id = :user_id
                       GROUP BY {$this->getTableName()}.id");
        $stmt->execute([':user_id' => $us_id]);
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $all_orders = [];
        foreach ($orders as $order) {
            $obj = $this->objOrder($order);
            $all_orders[] = $obj;
        }
        return $all_orders;
    }

    public function addProduct(Product $product, $amount_product) { ///ЧТО ЭТО??? ЭТО ВООБЩЕ ИСП СЕЙЧАС??
        $this->products[] = $product;
        $this->amountProduct[] = $amount_product;
    }


}