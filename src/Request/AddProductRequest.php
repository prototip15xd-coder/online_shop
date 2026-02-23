<?php

namespace Request;

class AddProductRequest
{
    public function __construct(private array $data) {

    }
    public function getAction() {
        return $this->data['action'];
    }
    public function getProductId() {
        return $this->data['product_id'];
    }
}