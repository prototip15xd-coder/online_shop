<?php

declare(strict_types=1);

namespace Model;

class ProductReview extends Model
{
    private int $id;
    private int $productId;
    private int $userId;
    private int $orderId;
    private int $rating;
    private ?string $review;
    private ?string $name;

    public function getReviewId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function getUserName(): string
    {
        return $this->name;
    }

    public function setUserName(?string $name): void
    {
        $this->name = $name;
    }

    protected static function getTableName(): string
    {
        return "products_review";
    }

    public function objProductReview(array $product): ProductReview
    {
        $obj = new self();
        $obj->id = $product["id"];
        $obj->productId = $product["product_id"];
        $obj->userId = $product["user_id"];
        $obj->orderId = $product["order_id"];
        $obj->rating = $product["rating"];
        $obj->review = $product["review"] ?? null;
        $obj->name = $product["name"] ?? null;
        return $obj;
    }

    public function productReviews(int $productId): ?array
    {
        $tableName = static::getTableName();
        $stms = static::getPDO()->prepare("SELECT * FROM {$tableName} WHERE product_id = :product_id");
        $stms->execute(['product_id' => $productId]);
        $productReviews = $stms->fetchAll(\PDO::FETCH_ASSOC);

        $objProductReviews = [];

        if (!$productReviews) {
            return null;
        }

        foreach ($productReviews as $productReview) {
            $obj = $this->objProductReview($productReview);
            $objProductReviews[] = $obj;
        }

        return $objProductReviews;
    }
}