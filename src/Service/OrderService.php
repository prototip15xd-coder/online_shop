<?php

namespace Service;

use Model\Order;
use Model\OrderProduct;
use Model\User;
use Model\UserProduct;

class OrderService
{
    private Order $orderModel;
    private UserProduct $userProductModel;
    private OrderProduct $orderProductModel;
    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userProductModel = new UserProduct();
        $this->orderProductModel = new OrderProduct();

    }
    public function createOrder(array $data, User $user)
    {
        $orderId = $this->orderModel->create($data, $user->getUserId());
        $user_products = $this->userProductModel->getAllByUserId($user->getUserId());
        foreach ($user_products as $user_product) {
            $this->orderProductModel->createOrderProduct($orderId, $user_product->getProductId(), $user_product->getAmount());
        }
        $this->userProductModel->deleteByUserId($user->getUserId());
    }
}