<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\Product;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;
use Service\LoggerDBService;
use Service\LoggerService;


class OrderService extends Service
{
    private AuthInterface $authService;
    private CartService $cartService;
    protected LoggerService $loggerService;
    protected LoggerDBService $loggerDBService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthSessionService();
        $this->cartService = new CartService();
        $this->loggerService = new LoggerService();
        $this->loggerDBService = new LoggerDBService();
    }

    public function createOrder(OrderCreateDTO $data): void
    {
        $orderSum = $this->cartService->getCartSum();

        if ($orderSum < 100)
        {
            $exception = new \Exception('Сумма заказа должна превышать 100р рублей');
            $this->loggerDBService->error($exception);
            throw $exception;
        }

        $user = $this->authService->getCurrentUser();
        $orderId = $this->orderModel->create($data->getContactName(),
            $data->getContactPhone(),
            $data->getComment(),
            $data->getAddress(),
            $user->getUserId());
        $user_products = $this->userProductModel->getAllByUserId($user->getUserId());

        foreach ($user_products as $user_product) {
            $this->orderProductModel->createOrderProduct($orderId, $user_product->getProductId(), $user_product->getAmount());
        }

        $this->userProductModel->deleteByUserId($user->getUserId());
    }

    public function getOrderProduct(int $orderId): array
    {
        $products = Product::getProductsByOrderID($orderId);

        foreach ($products as &$product) {
            $totalSum = $product->getProductAmount() * $product->getProductPrice();
            $product->setProductTotalSum($totalSum);
        }

        return $products;
    }

    public function getAllOrders(): array
    {
        $this->authService->checkUser();
        $user = $this->authService->getCurrentUser();
        $orders = $this->orderModel->getOrders($user->getUserId());

        foreach ($orders as $order) {
            $order->setOrderProducts($this->getOrderProduct($order->getOrderId()));
            $totalOrderSum = 0;
            foreach ($order->getOrderProducts() as $orderProduct) {
                $totalOrderSum += $orderProduct->getProductTotalSum();
            }
            $order->setOrderCost($totalOrderSum);
        }

        return $orders;
    }

    public function getOrder(int $orderId): Order
    {
        $this->authService->checkUser();
        return $this->orderModel->getOrder($orderId);
    }
}