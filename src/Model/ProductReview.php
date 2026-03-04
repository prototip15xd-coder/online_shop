<?php

namespace Model;
/// НИКАКОЙ БЛЯТЬ ДИНАМИКИ НАХУЙ
class ProductReview extends Model
{
    private int $id;
    private int $product_id;
    private int $user_id;
    private int $order_id;
    private int $rating;
    private ?string $review;
    private ?string $name;

    public function getReviewId()
    {
        return $this->id;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function getReview()
    {
        return $this->review;
    }
    public function getUserName() {
        return $this->name;
    }
    public function setUserName(?string $name): void {
        $this->name = $name;
    }
    protected function getTableName(): string
    {
        return "products_review";
    }

    public function objProductReview(array $product) {
        $obj = new self();
        $obj->id = $product["id"];
        $obj->product_id = $product["product_id"];
        $obj->user_id = $product["user_id"];
        $obj->order_id = $product["order_id"];
        $obj->rating = $product["rating"];
        $obj->review = $product["review"] ?? null;
        $obj->name = $product["name"] ?? null;
        return $obj;
    }


    public function product_reviews($productId)// нужно создать модель продукт ревью
    {
        $stms = $this->connection->prepare("SELECT * FROM products_review WHERE product_id = :product_id");
        $stms->execute(['product_id' => $productId]);
        $productReviews = $stms->fetchAll(\PDO::FETCH_ASSOC);
        $objProductReviews = [];
        if (!$productReviews) {
            return null;
        } else {
            foreach ($productReviews as $productReview) {
                $obj = $this->objProductReview($productReview);
                $objProductReviews[] = $obj;///попробуй убрать это
            }
            return $objProductReviews;
        }//должен содержать массив объектов отзывов
    }

}