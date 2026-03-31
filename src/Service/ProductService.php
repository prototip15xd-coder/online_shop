<?php

namespace Service;

use Model\Product;
class ProductService
{
    private Product $ProductModel;

    public function __construct()
    {
        $this->ProductModel = new Product();
    }
    public function rowCountProduct(int $productId): int
    {
        return $this->ProductModel->validateProduct($productId);
    }

}