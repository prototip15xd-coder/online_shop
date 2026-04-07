<?php

namespace Service;

use Model\Logger;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\ProductReview;
use Model\User;
use Model\UserProduct;

class Service
{
    private logger $loggerModel;
    protected Order $orderModel;
    protected UserProduct $userProductModel;
    protected OrderProduct $orderProductModel;
    protected ProductReview $productReviewModel;
    protected Product $productModel;
    protected User $userModel;

    public function __construct()
    {
        $this->loggerModel = new Logger();
        $this->orderModel = new Order();
        $this->userProductModel = new UserProduct();
        $this->orderProductModel = new OrderProduct();
        $this->productReviewModel = new ProductReview();
        $this->productModel = new Product();
        $this->userModel = new User();
    }
}