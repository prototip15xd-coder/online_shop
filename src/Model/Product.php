<?php

namespace Model;

class Product extends Model // НИКАКОЙ БЛЯТЬ ДИНАМИКИ НАХУЙ
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
    public function objProduct(array $product) {
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

    protected function getTableName(): string
    {
        return "products";
    }
    public function productByDB(): array | null
    {
        $stms = $this->connection->query("SELECT * FROM {$this->getTableName()}");
        $products_array = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $products = [];
        foreach ($products_array as $product) {
            $obj = $this->objProduct($product);
            $products[] = $obj;
        }
        return $products;
    }
    public function productByproductId($productId): ?Product
    {
        $stms = $this->connection->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
        $stms -> execute([':id' => $productId]);
        $product_array = $stms->fetch(\PDO::FETCH_ASSOC);
        if (!$product_array) {
            return null;
        }
        $obj = $this->objProduct($product_array);
        return $obj;
    }


    public function validate_product($productId): int
    {
        $stms = $this->connection->prepare("SELECT id FROM {$this->getTableName()} WHERE id = :product_id");
        $stms->execute(['product_id' => $productId]);
        return $stms->rowCount();
    }


}