<?php

namespace Request;

class OrderRequest
{
    public function __construct(private array $data) {}

    public function getOrderId(): int
    {
        return $this->data['order_id'];
    }

}