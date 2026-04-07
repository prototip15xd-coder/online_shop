<?php

namespace Service;

use Model\Product;

class ProductService extends Service
{

    public function __construct()
    {
        parent::__construct();
    }

    public function rowCountProduct(int $productId): int
    {
        return $this->productModel->validateProduct($productId);
    }

    public function getProduct(int $productId): Product
    {
        return $this->productModel->productByproductId($productId);
    }

    public function getProductWithAmount(int $userId): array
    {
        return Product::getWithAmount($userId);
    }

}