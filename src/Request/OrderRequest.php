<?php

declare(strict_types=1);

namespace Request;

class OrderRequest
{
    public function __construct(private array $data) {}

    public function getOrderId(): int
    {
        return (int)$this->data['order_id'];
    }

}