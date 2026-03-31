<?php

namespace Service;

use Model\Product;
use Model\UserProduct;

class UserProductService
{
    private UserProduct $userProductModel;

    public function __construct()
    {
        $this->userProductModel = new UserProduct();
    }
    public function getUserProduct(int $productId): UserProduct
    {
        return $this->userProductModel->userProductByDB($productId);
    }


}