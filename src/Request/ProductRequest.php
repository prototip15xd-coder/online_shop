<?php

namespace Request;

class ProductRequest
{
    public function __construct(private array $data) {}
    public function getProductId(): int //здесь точно int не string?
    {
        return $this->data['product_id'];
    }

}