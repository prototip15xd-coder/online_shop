<?php

namespace Model;
#[\AllowDynamicProperties]
class Product extends Model // сделай другю модель для отзывов
{
     private $id;
     private $name;
     private $description;
     private $price;
     private $image_url;
     private $value;


    public function getProductId()
    {
        return $this->id;
    }


    public function getProductName()
    {
        return $this->name;
    }


    public function getProductDescription()
    {
        return $this->description;
    }


    public function getProductPrice()
    {
        return $this->price;
    }
    public function getProductImageUrl()
    {
        return $this->image_url;
    }
    public function getProductValue()
    {
        return $this->value;
    }
    public function objProduct(array $product) {
        $obj = new self();
        $obj->id = $product["id"];
        $obj->name = $product["name"];
        $obj->description = $product["description"];
        $obj->price = $product["price"];
        $obj->image_url = $product["image_url"];
        $obj->value = $product["value"];
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


    public function validate_product()
    {
        $stms = $this->connection->prepare("SELECT id FROM {$this->getTableName()} WHERE id = :product_id");
        $stms->execute(['product_id' => $_POST['product_id']]);
        return $stms->rowCount();
    }

    public function product_reviews($productId)// нужно создать модель продукт ревью
    {
        $stms = $this->connection->prepare("SELECT * FROM products_review WHERE product_id = :product_id");
        $stms->execute(['product_id' => $productId]);
        $result = $stms->fetchAll(\PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }
        return $result; //должен содержать массив отзывов
    }
}