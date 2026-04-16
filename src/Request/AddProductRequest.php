<?php

declare(strict_types=1);

namespace Request;

class AddProductRequest
{
    public function __construct(private array $data) {}

    public function getAction(): string
    {
        return $this->data['action'];
    }

    public function getProductId(): int
    {
        return (int)$this->data['product_id'];
    }
}