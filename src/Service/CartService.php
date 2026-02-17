<?php

namespace Service;

use Model\UserProduct;

class CartService
{
    private UserProduct $userProduct;
    public function __construct()
    {
        $this->userProduct = new UserProduct();
    }
    public function add_product()
    {
        $action = $_POST['action'];
        if ($action === 'plus') {
            $this->userProductModel->add_productDB();///нужно переделать эти функции в юзер продукт
            $action = false;  /// когда отправляю запрос на +- то после обновления страницы запрос в перемнной action сохраняется  а не сбрасывается
        } else if ($action === 'minus') {
            $this->userProductModel->delete_productDB();
            $action = false;
        }
    }
}