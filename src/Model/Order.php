<?php

namespace Model;

use Model\Model;
class Order extends Model
{
    private int $id;
    private string $contact_name;
    private string $contact_phone;
    private ?string $comment;
    private string $address;
    private ?array $orderProducts;
    private ?int $orderCost;


    public function getOrderId(): int
    {
        return $this->id;
    }

    public function getContactName(): string
    {
        return $this->contact_name;
    }

    public function getContactPhone(): string
    {
        return $this->contact_phone;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getOrderProducts(): array
    {
        return $this->orderProducts;
    }

    public function setOrderProducts(array $orderProducts): void
    {
        $this->orderProducts = $orderProducts;
    }

    public function getOrderCost(): int
    {
        return $this->orderCost;
    }

    public function setOrderCost(int $orderCost): void
    {
        $this->orderCost = $orderCost;
    }

    protected static function getTableName(): string
    {
        return "orders";
    }

    public function objOrder($order){
        $obj = new self();
        $obj->id = $order['id'];
        $obj->contact_name = $order['contact_name'];
        $obj->contact_phone = $order['contact_phone'];
        $obj->comment = $order['comment'] ?? null;
        $obj->address = $order['address'];
        $obj->orderProducts = $order['orderProducts'] ?? null;
        $obj->orderCost = $order['orderCost'] ?? null;
        return $obj;
    }

    public function create( string $name, $phone, $comm, string $address, int $userId)
    {
        $stmt = static::getPDO()->prepare(
            "INSERT INTO {$this->getTableName()} (contact_name, contact_phone, comment, address, user_id) 
                    VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id"
        );

        $stmt->execute([
            'name'=>$name,
            'phone'=>$phone,
            'comment'=>$comm,
            'address'=>$address,
            'user_id'=>$userId
        ]);

        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res['id'];
    }

    public function getOrder(int $orderId): Order
    {
        $stmt = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :order_id");
        $stmt->execute(['order_id' => $orderId]);
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $this->objOrder($order);
    }

    public function getOrders(int $us_id): array
    {
        $stmt = static::getPDO()->prepare("SELECT * FROM orders WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $all_orders = [];
        foreach ($orders as $order) {
            $obj = $this->objOrder($order);
            $all_orders[] = $obj;
        }
        return $all_orders;
    }
}