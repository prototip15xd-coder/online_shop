<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Product;
use Model\Order;
use Model\OrderProduct;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;
use Service\LoggerDBService;
use Service\LoggerService;


class OrderService
{
    private Order $orderModel;
    private UserProduct $userProductModel;
    private OrderProduct $orderProductModel;
    private AuthInterface $authService;
    private CartService $cartService;
    protected LoggerService $loggerService;
    protected LoggerDBService $loggerDBService;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userProductModel = new UserProduct();
        $this->orderProductModel = new OrderProduct();
        $this->authService = new AuthSessionService();
        $this->cartService = new CartService();
        $this->loggerService = new LoggerService();
        $this->loggerDBService = new LoggerDBService();
    }

    public function createOrder(OrderCreateDTO $data)// ,array $data, User $user)
    {
        $orderSum = $this->cartService->getCartSum();

        if ($orderSum < 100)
        {
            $exception = throw new \Exception('Сумма заказа должна превышать 100р рублей', $this->authService->getCurrentUser()->getUserId());
            $this->loggerDBService->error($exception);/////????
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

    public function getOrderProduct(int $orderId): array ////надо оптимизировать
    {
        $products = Product::getProductsByOrderID($orderId);

        foreach ($products as &$product) {
            $totalSum = $product->getAmount() * $product->getProductPrice();
            $product->setTotalSum($totalSum);
        }

        return $products;
    }
    public function getAllOrders()///перенеси в сервис
    {
        $this->authService->checkUser();
        $user = $this->authService->getCurrentUser();
        $orders = $this->orderModel->getOrders($user->getUserId());

        foreach ($orders as $order) {
            $order->setOrderProducts($this->getOrderProduct($order->getOrderId()));
            /////добавь сюда тотал сум и вызов полной цены заказа
        }

        return $orders;
    }
    public function getOrder($orderId)
    {
        $this->authService->checkUser();
        return $order = $this->orderModel->getOrder($orderId);
    }
}