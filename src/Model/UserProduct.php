<?php

namespace Model;

class UserProduct extends Model ///тут уже объявлены новые св-ва нужно довести до ума
{
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $amount;
    private ?Product $product = null;
    private ?int $totalSum = null;

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
    public function getTotalSum(): ?int
    {
        return $this->totalSum;
    }
    public function setTotalSum(int $totalSum): void
    {
        $this->totalSum = $totalSum;
    }
    public function getProduct(): ?Product
    {
        return $this->product;
    }
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
    protected static function getTableName(): string
    {
        return "user_products";
    }
    public static function objUserProduct($userProduct) {
        $obj = new self();
        $obj->id = $userProduct["id"];
        $obj->user_id = $userProduct["user_id"];
        $obj->product_id = $userProduct["product_id"];
        $obj->amount = $userProduct["amount"];
        if (isset($userProduct["name"])) {
            $productData = [
                "id" => $userProduct["product_id"],
                "name" => $userProduct["name"],
                "price" => $userProduct["price"],
                "description" => $userProduct["description"],
                "image_url" => $userProduct["image_url"],
                "value" => $userProduct["value"],
            ];
            $product = Product::objProduct($productData);
            $obj->setProduct($product);
        }
        return $obj;
    }
//    public function userProduct() {
//        $user_p = $this->connection->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id ");
//        $user_p->execute(['user_id' => $_SESSION['userid'], 'product_id' => $_POST['product_id']]);
//        $existingRecord = $user_p->fetch(\PDO::FETCH_ASSOC);
//        return $existingRecord;
//    }
    public function getUserProducts($user_id): array
    {
        $stms = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id");
        $stms->execute([":user_id" => $user_id]);
        $userProducts = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $objUserProducts =[];
        foreach ($userProducts as $userProduct) {
            $obj = $this->objUserProduct($userProduct);
            $objUserProducts[] = $obj;
        }
        return $objUserProducts;
    }
    public static function getByUserIdWithProducts(int $user_id): array
    {
        $tableName = static::getTableName();
        $stms = static::getPDO()->prepare("SELECT * FROM {$tableName} up 
         INNER JOIN products p ON up.product_id = p.id WHERE up.user_id = :user_id");
        $stms->execute([":user_id" => $user_id]);
        $userProductsWithProducts = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $objUserProductsWithProducts = [];
        foreach ($userProductsWithProducts as $userProduct) {
            $obj = static::objUserProduct($userProduct);
            $objUserProductsWithProducts[] = $obj;
        }
        return $objUserProductsWithProducts;

    }
    public function userProductByDB($product_id): UserProduct ///для случая когда запрос гет и мы просто заходим в каталог
    {
        $user_p = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id AND product_id = :product_id ");
        $user_p->execute(['user_id' => $_SESSION['userid'], 'product_id' => $product_id]);
        $product  = $user_p->fetch(\PDO::FETCH_ASSOC);
        if ($product=== false) {
            $product = [
                'id' => 0,
                'user_id' => $_SESSION['userid'],
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
        $stmt = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
        $all_products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $user_products = [];
        foreach ($all_products as $product) {
            $obj = $this->objUserProduct($product);
            $user_products[] = $obj;
        }
        return $user_products;
    }

    public function deleteByUserId(int $us_id)
    {
        $stmt = static::getPDO()->prepare("DELETE FROM {$this->getTableName()} WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $us_id]);
    }
    public static function add_productDB($amount)
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare("UPDATE {$tableName} SET amount = amount + :amount WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            'user_id' => $_SESSION['userid'],
            'product_id' => $_POST['product_id'],
            'amount' => $amount
        ]);
        if ($stmt->rowCount() == 0) {
            $stmt = static::getPDO()->prepare("INSERT INTO {$tableName} (user_id, product_id, amount) 
            VALUES (:user_id, :product_id, :amount)");
            $stmt->execute([
                'user_id' => $_SESSION['userid'],
                'product_id' => $_POST['product_id'],
                'amount' => $amount
            ]);
        }
    }
//    public function delete_productDB($amount)
//    {
//        $stmt = $this->connection->prepare("UPDATE {$this->getTableName()} SET amount = amount - :amount WHERE user_id = :user_id AND product_id = :product_id");
//        $stmt->execute([
//            'user_id' => $_SESSION['userid'],
//            'product_id' => $_POST['product_id'],
//            'amount' => $amount
//        ]);
//    }
}