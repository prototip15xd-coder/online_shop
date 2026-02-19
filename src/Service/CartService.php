<?php

namespace Service;

use Model\UserProduct;

class CartService
{
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->userProductModel = new UserProduct();
    }
    public function add_product()
    {
        $action = $_POST['action'];
        if ($action === 'plus') {
            $amount = 1;
        } elseif ($action === 'minus') {
            $amount = -1;
        }
        $this->userProductModel->add_productDB($amount);
        $action = false;
    }
}