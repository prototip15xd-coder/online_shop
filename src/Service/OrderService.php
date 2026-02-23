<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\User;
use Model\UserProduct;

class OrderService
{
    private Order $orderModel;
    private UserProduct $userProductModel;
    private OrderProduct $orderProductModel;
    private Product $productModel;
    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userProductModel = new UserProduct();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();

    }
    public function createOrder(OrderCreateDTO $data)// ,array $data, User $user)
    {
        $orderId = $this->orderModel->create($data->getContactName(),
            $data->getContactPhone(),
            $data->getComment(),
            $data->getAddress(),
            $data->getUser()->getUserId());
        $user_products = $this->userProductModel->getAllByUserId($data->getUser()->getUserId());
        foreach ($user_products as $user_product) {
            $this->orderProductModel->createOrderProduct($orderId, $user_product->getProductId(), $user_product->getAmount());
        }
        $this->userProductModel->deleteByUserId($data->getUser()->getUserId());
    }

    public function getOrder(int $orderId, Order $order): Order
    {
        $orderProducts = $this->orderProductModel->getAllProductFromOrderByOrderId($orderId);
        /// содержит массив объектов продуктов
        $products = [];
        foreach ($orderProducts as $orderProduct) {
            $productId = $orderProduct->getProductId();
            $product = $this->productModel->productByproductId($productId);
            $product->amount = $orderProduct->getAmount();
            $products[] = $product;
        }
        $order->products = $products;
        return $order;
    }
}