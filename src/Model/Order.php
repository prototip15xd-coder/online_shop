<?php

declare(strict_types=1);

namespace Model;

class Order extends Model
{
    private int $id;
    private string $contactName;
    private string $contactPhone;
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
        return $this->contactName;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
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

    public function objOrder($order): ?Order
    {
        $obj = new self();
        $obj->id = $order['id'];
        $obj->contactName = $order['contact_name'];
        $obj->contactPhone = $order['contact_phone'];
        $obj->comment = $order['comment'] ?? null;
        $obj->address = $order['address'];
        $obj->orderProducts = $order['orderProducts'] ?? null;
        $obj->orderCost = $order['orderCost'] ?? null;

        return $obj;
    }

    public function create( string $name, string $phone, string $comm, string $address, int $userId): int //точно int?
    {
        $stmt = static::getPDO()->prepare("
            INSERT INTO {$this->getTableName()} (contact_name, contact_phone, comment, address, user_id) 
            VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id
            ");

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

    public function getOrders(int $userId): array
    {
        $stmt = static::getPDO()->prepare("SELECT * FROM orders WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $allOrders = [];

        foreach ($orders as $order) {
            $obj = $this->objOrder($order);
            $allOrders[] = $obj;
        }

        return $allOrders;
    }
}