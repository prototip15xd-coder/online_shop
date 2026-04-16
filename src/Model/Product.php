<?php

declare(strict_types=1);

namespace Model;

class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private ?string $imageUrl = null;
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
        return $this->imageUrl;
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
        $this->imageUrl = $image_url;
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

    public static function objProduct(array $product): Product
    {
        $obj = new self();
        $obj->id = $product["id"] ?? null;
        $obj->name = $product["name"] ?? null;
        $obj->description = $product["description"] ?? null;
        $obj->price = (int)$product["price"] ?? null;
        $obj->imageUrl = $product["image_url"] ?? null;
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
        $productsArray = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $products = [];

        foreach ($productsArray as $product) {
            $obj = static::objProduct($product);
            $products[] = $obj;
        }

        return $products;
    }

    public function productByProductId(int $productId): ?Product
    {
        $stms = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
        $stms -> execute([':id' => $productId]);
        $productArray = $stms->fetch(\PDO::FETCH_ASSOC);

        if (!$productArray) {
            return null;
        }

        $obj = $this->objProduct($productArray);
        return $obj;
    }

    public static function getProductsByOrderID(int $orderId): ?array
    {
        $tableName = static::getTableName();
        $stms = static::getPDO()->prepare("SELECT * FROM {$tableName} p 
         INNER JOIN order_products op ON p.id = op.product_id 
         WHERE op.order_id = :order_id"
        );
        $stms -> execute([':order_id' => $orderId]);
        $productsArray = $stms->fetchAll(\PDO::FETCH_ASSOC);

        if (!$productsArray) {
            return null;
        }

        $objArray =[];

        foreach ($productsArray as $product) {
            $obj = static::objProduct($product);
            $objArray[] = $obj;
        }

        return $objArray;
    }

    public static function getWithAmount(int $userId): ?array
    {
        $tableName = static::getTableName();
        $stms = static::getPDO()->prepare("SELECT p.*, up.amount FROM {$tableName} p 
            LEFT JOIN user_products up ON p.id = up.product_id AND up.user_id = :user_id"
        );

        $stms -> execute([":user_id" => $userId]);
        $productsArray = $stms->fetchAll(\PDO::FETCH_ASSOC);

        if (!$productsArray) {
            return null;
        }

        $objArray =[];

        foreach ($productsArray as $product) {
            $obj = static::objProduct($product);
            $objArray[] = $obj;
        }

        return $objArray;
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