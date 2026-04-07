<?php

namespace Model;

class UserProduct extends Model
{
    private int $id;
    private int $userId;
    private int $productId;
    private int $amount;
    private ?Product $product = null;
    private ?int $totalSum = null;

    public function getId(): int
    {
        return $this->id ?? 0;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
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

    public static function objUserProduct(array $userProduct): UserProduct
    {
        $obj = new self();
        $obj->id = $userProduct["id"];
        $obj->userId = $userProduct["user_id"];
        $obj->productId = $userProduct["product_id"];
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

    public function getUserProducts(int $userId): array
    {
        $stms = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} 
         WHERE user_id = :user_id"
        );
        $stms->execute([":user_id" => $userId]);
        $userProducts = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $objUserProducts =[];

        foreach ($userProducts as $userProduct) {
            $obj = $this->objUserProduct($userProduct);
            $objUserProducts[] = $obj;
        }

        return $objUserProducts;
    }

    public static function getByUserIdWithProducts(int $userId): array
    {
        $tableName = static::getTableName();
        $stms = static::getPDO()->prepare(
            "SELECT * FROM {$tableName} up 
            INNER JOIN products p 
            ON up.product_id = p.id 
            WHERE up.user_id = :user_id"
        );
        $stms->execute([":user_id" => $userId]);
        $userProductsWithProducts = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $objUserProductsWithProducts = [];

        foreach ($userProductsWithProducts as $userProduct) {
            $obj = static::objUserProduct($userProduct);
            $objUserProductsWithProducts[] = $obj;
        }

        return $objUserProductsWithProducts;

    }

    public function userProductByDB(int $productId): UserProduct
    {
        $userProduct = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} 
         WHERE user_id = :user_id AND product_id = :product_id"
        );
        $userProduct->execute(['user_id' => $_SESSION['userid'], 'product_id' => $productId]);
        $product  = $userProduct->fetch(\PDO::FETCH_ASSOC);

        if ($product=== false) {
            $product = [
                'id' => 0,
                'user_id' => $_SESSION['userid'],
                'product_id' => $productId,
                'amount' => 0
            ];
        }

        $obj = $this->objUserProduct($product);
        return $obj;
    }

    public function getAllByUserId(int $userId): UserProduct|array
    {
        $stmt = static::getPDO()->prepare(
            "SELECT * FROM {$this->getTableName()} 
            WHERE user_id = :user_id"
        );
        $stmt->execute([':user_id' => $userId]);
        $allProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $userProducts = [];

        foreach ($allProducts as $product) {
            $obj = $this->objUserProduct($product);
            $userProducts[] = $obj;
        }

        return $userProducts;
    }

    public function deleteByUserId(int $usId): void
    {
        $stmt = static::getPDO()->prepare(
            "DELETE FROM {$this->getTableName()} 
            WHERE user_id = :user_id"
        );
        $stmt->execute([':user_id' => $usId]);
    }

    public static function addProductDB(int $userId, int $productId, int $amount): void
    {
        $tableName = static::getTableName();
        $stmt = static::getPDO()->prepare(
            "UPDATE {$tableName} 
            SET amount = amount + :amount 
            WHERE user_id = :user_id AND product_id = :product_id"
        );
        $stmt->execute([
            'user_id'    => $userId,
            'product_id' => $productId,
            'amount'     => $amount
        ]);

        if ($stmt->rowCount() == 0) {
            $stmt = static::getPDO()->prepare(
                "INSERT INTO {$tableName} (user_id, product_id, amount) 
                VALUES (:user_id, :product_id, :amount)"
            );
            $stmt->execute([
                'user_id'    => $userId,
                'product_id' => $productId,
                'amount'     => $amount
            ]);
        }
    }
}