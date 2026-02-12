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
    public function objUserProduct($userProduct) {
        $obj = new self();
        $obj->id = $userProduct["id"];
        $obj->user_id = $userProduct["user_id"];
        $obj->product_id = $userProduct["product_id"];
        $obj->amount = $userProduct["amount"];
        return $obj;
    }
    public function userProductByDB($product_id) ///для случая когда запрос гет и мы просто заходим в каталог
    {
        $user_id = $_SESSION["userid"];
        $stms = $this->connection->prepare('SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id');
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
}