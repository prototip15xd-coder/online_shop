<?php

declare(strict_types=1);

namespace Request;

class ProductRequest
{
    public function __construct(private array $data) {}

    public function getProductId(): int
    {
        return (int)$this->data['product_id'];
    }
}