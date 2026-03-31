<?php

namespace Model;

class Product extends Model
{
     private int $id;
     private string $name;
     private string $description;
     private int $price;
     private ?string $image_url = null;
     private string $value;
     private ?int $amount = null;
     private ?int $totalSum = null;

    public function getProductId(): int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->name;
    }

    public function getProductDescription(): ?string
    {
        return $this->description;
    }

    public function getProductPrice(): ?int
    {
        return $this->price;
    }

    public function getProductImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function getProductValue(): ?string
    {
        return $this->value;
    }

    public function getProductAmount(): ?int
    {
        return $this->amount;
    }


    public function setProductAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    public function setProductName(string $name): void
    {
        $this->name = $name;
    }

    public function setProductDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setProductImageUrl(string $image_url): void
    {
        $this->image_url = $image_url;
    }

    public function setProductPrice(int $price): void
    {
        $this->price = $price;
    }

    public function setProductValue(string $value): void
    {
        $this->value = $value;
    }
    public function getProductTotalSum(): ?int
    {
        return $this->totalSum;
    }

    public function setProductTotalSum(?int $totalSum): void
    {
        $this->totalSum = $totalSum;
    }

    public static function objProduct(array $product): self
    {
        $obj = new self();
        $obj->id = $product["id"] ?? null;
        $obj->name = $product["name"] ?? null;
        $obj->description = $product["description"] ?? null;
        $obj->price = $product["price"] ?? null;
        $obj->image_url = $product["image_url"] ?? null;
        $obj->value = $product["value"] ?? null;
        $obj->amount = $product["amount"] ?? null;
        $obj->totalSum = $product["totalSum"] ?? null;
        return $obj;
    }

    protected static function getTableName(): string
    {
        return "products";
    }

    public static function productsByDB(): array | null
    {
        $tableName = static::getTableName();
        $stms = static::getPDO()->prepare("SELECT * FROM $tableName");
        $stms->execute();
        $products_array = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $products = [];

        foreach ($products_array as $product) {
            $obj = static::objProduct($product);
            $products[] = $obj;
        }

        return $products;
    }

    public function productByproductId(int $productId): ?Product
    {
        $stms = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
        $stms -> execute([':id' => $productId]);
        $product_array = $stms->fetch(\PDO::FETCH_ASSOC);

        if (!$product_array) {
            return null;
        }

        $obj = $this->objProduct($product_array);
        return $obj;
    }

    public static function getProductsByOrderID(int $order_id): ?array
    {
        $tableName = static::getTableName();
        $stms = static::getPDO()->prepare("SELECT * FROM {$tableName} p 
         INNER JOIN order_products op ON p.id = op.product_id 
         WHERE op.order_id = :order_id"
        );
        $stms -> execute([':order_id' => $order_id]);
        $products_array = $stms->fetchAll(\PDO::FETCH_ASSOC);

        if (!$products_array) {
            return null;
        }

        $obj_array =[];

        foreach ($products_array as $product) {
            $obj = static::objProduct($product);
            $obj_array[] = $obj;
        }

        return $obj_array;
    }

    public static function getWithAmount(int $user_id): ?array
    {
        $tableName = static::getTableName();
        $stms = static::getPDO()->prepare("SELECT p.*, up.amount FROM {$tableName} p 
            LEFT JOIN user_products up ON p.id = up.product_id AND up.user_id = :user_id"
        );

        $stms -> execute([":user_id" => $user_id]);
        $products_array = $stms->fetchAll(\PDO::FETCH_ASSOC);

        if (!$products_array) {
            return null;
        }

        $obj_array =[];

        foreach ($products_array as $product) {
            $obj = static::objProduct($product);
            $obj_array[] = $obj;
        }

        return $obj_array;
    }

    public function validateProduct(int $productId): int
    {
        $stms = static::getPDO()->prepare("SELECT id FROM {$this->getTableName()} 
          WHERE id = :product_id"
        );

        $stms->execute(['product_id' => $productId]);
        return $stms->rowCount();
    }


}