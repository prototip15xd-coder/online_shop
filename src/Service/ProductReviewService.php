<?php

namespace Service;

class ProductReviewService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getReview(int $productId): ?array
    {
        return $this->productReviewModel->productReviews($productId);
    }

}