<?php

namespace Model;

class Product extends Model
{
     private int $id;
     private string $name;
     private string $description;
     private int $price;
     private string $image_url;
     private string $value;
     private ?int $amount = null;


    public function getProductId()
    {
        return $this->id;
    }


    public function getProductName(): string
    {
        return $this->name;
    }


    public function getProductDescription(): string
    {
        return $this->description;
    }


    public function getProductPrice(): int
    {
        return $this->price;
    }
    public function getProductImageUrl(): string
    {
        return $this->image_url;
    }
    public function getProductValue(): string
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

    public function setProductId(int $id): void
    {
        $this->id = $id;
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

    public static function objProduct(array $product) {
        $obj = new self();
        $obj->id = $product["id"];
        $obj->name = $product["name"];
        $obj->description = $product["description"];
        $obj->price = $product["price"];
        $obj->image_url = $product["image_url"];
        $obj->value = $product["value"];
        $obj->amount = $product["amount"] ?? null;
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
        $products_array = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $products = [];
        foreach ($products_array as $product) {
            $obj = static::objProduct($product);
            $products[] = $obj;
        }
        return $products;
    }
    public function productByproductId($productId): ?Product
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
         INNER JOIN order_products op ON p.id = op.product_id WHERE op.order_id = :order_id");
        $stms -> execute([':order_id' => $order_id]);
        $products_array = $stms->fetch(\PDO::FETCH_ASSOC);
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


    public function validate_product($productId): int
    {
        $stms = static::getPDO()->prepare("SELECT id FROM {$this->getTableName()} WHERE id = :product_id");
        $stms->execute(['product_id' => $productId]);
        return $stms->rowCount();
    }


}