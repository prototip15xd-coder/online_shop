<?php

namespace Request;

class ProductRequest
{
    public function __construct(private array $data) {

    }
    public function getProductId(): string {
        return $this->data['product_id'];
    }

}