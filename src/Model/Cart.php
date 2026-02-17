<?php


namespace Model;

use Model\Product;
use Controllers\ProductController;

class Cart extends Model /// ПЕРЕНЕСИ В ЮЗЕР ПРОДУКТ ТАМ НАДО ЧТО ТО ПРИДУМАТЬ
{
    private string $name;
    private string $description;
    private int $price;
    private string $image_url;
    private int $amount;

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }



    public function objCartProduct(array $Product)
    {
        $obj = new self();
        $obj->name = $Product["name"];
        $obj->description = $Product["description"];
        $obj->price = $Product["price"];
        $obj->image_url = $Product["image_url"];
        $obj->amount = $Product["amount"];
        return $obj;
    }
    public function cartbyDB()
    {
        $us_id = $_SESSION['userid'];

        $stms = $this->connection->prepare("SELECT products.name,
               products.description,
               products.price,
               products.image_url,
               user_products.amount 
               FROM user_products 
               JOIN products ON user_products.product_id = products.id
               WHERE user_products.user_id = :user_id");
        $stms->execute([':user_id' => $us_id]);
        $products = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $all_products = [];
        foreach ($products as $product) {
            $obj = $this->objCartProduct($product);
            $all_products[] = $obj;
        }
        return $all_products;
    }

    public function getAllByUserId(int $us_id): Cart|array
    {
        $stmt = $this->connection->prepare("SELECT * FROM user_products WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
        $all_products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $all_products;
    }

    public function deleteByUserId(int $us_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM user_products WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
    }
}