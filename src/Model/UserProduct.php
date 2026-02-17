<?php

namespace Model;

class UserProduct extends Model
{
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $amount;

    public function getId(): int
    {
        return $this->id ?? 0;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAmount(): int
    {
        return $this->amount ?? 0;
    }
    protected function getTableName(): string
    {
        return "user_products";
    }
    public function objUserProduct($userProduct) {
        $obj = new self();
        $obj->id = $userProduct["id"];
        $obj->user_id = $userProduct["user_id"];
        $obj->product_id = $userProduct["product_id"];
        $obj->amount = $userProduct["amount"];
        return $obj;
    }
    public function userProducts(): array
    {
        $user_id = $_SESSION["userid"];
        $stms = $this->connection->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id");
        $stms->execute([":user_id" => $user_id]);
        $products = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $objUserProducts =[];
        foreach ($products as $product) {
            $obj = $this->objUserProduct($product);
            $objUserProducts[] = $obj;
        }
        return $objUserProducts;
    }
    public function userProductByDB($product_id): UserProduct ///для случая когда запрос гет и мы просто заходим в каталог
    {
        $user_id = $_SESSION["userid"];
        $stms = $this->connection->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id AND product_id = :product_id");
        $stms->execute([":user_id" => $user_id, ":product_id" => $product_id]);
        $product = $stms->fetch(\PDO::FETCH_ASSOC);
        if ($product=== false) {
            $product = [
                'id' => 0,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'amount' => 0
            ];
        }
        $obj = $this->objUserProduct($product);
        return $obj;
    }
//    public function objCartProduct(array $Product)
//    {
//        $obj = new self();
//        $obj->name = $Product["name"];
//        $obj->description = $Product["description"];
//        $obj->price = $Product["price"];
//        $obj->image_url = $Product["image_url"];
//        $obj->amount = $Product["amount"];
//        return $obj;
//    }
//    public function cartbyDB() ///старый и громоздний оставлю для кайфа
//    {
//        $us_id = $_SESSION['userid'];
//
//        $stms = $this->connection->prepare("SELECT products.name,
//               products.description,
//               products.price,
//               products.image_url,
//               user_products.amount
//               FROM user_products
//               JOIN products ON user_products.product_id = products.id
//               WHERE user_products.user_id = :user_id");
//        $stms->execute([':user_id' => $us_id]);
//        $products = $stms->fetchAll(\PDO::FETCH_ASSOC);
//        $all_products = [];
//        foreach ($products as $product) {
//            $obj = $this->objCartProduct($product);
//            $all_products[] = $obj;
//        }
//        return $all_products;
//    }

    public function getAllByUserId(int $us_id): UserProduct|array
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
        $all_products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $all_products;
    }

    public function deleteByUserId(int $us_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM {$this->getTableName()} WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
    }
    public function add_productDB()
    {
        $result = $this->userProductByDB($_POST["product_id"]);
        $stmt = $this->connection->prepare("UPDATE {$this->getTableName()} SET amount = amount + 1 WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            'user_id' => $_SESSION['userid'],
            'product_id' => $_POST['product_id'],
        ]);
    }
    public function delete_productDB()
    {

        $result = $this->userProductByDB($_POST["product_id"]);
        $stmt = $this->connection->prepare("UPDATE {$this->getTableName()} SET amount = amount - 1 WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            'user_id' => $_SESSION['userid'],
            'product_id' => $_POST['product_id'],
        ]);
    }
}