<?php

namespace Service;

use Model\UserProduct;

class UserProductService extends Service
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserProduct(int $productId): UserProduct
    {
        return $this->userProductModel->userProductByDB($productId);
    }


}