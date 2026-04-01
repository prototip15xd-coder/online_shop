<?php

namespace Service;

use Model\Product;
use Model\ProductReview;
class ProductReviewService
{

    private ProductReview $productReviewModel;

    public function __construct()
    {
        $this->productReviewModel = new ProductReview();
    }

    public function getReview(int $productId): ?array
    {
        return $this->productReviewModel->productReviews($productId);
    }

}