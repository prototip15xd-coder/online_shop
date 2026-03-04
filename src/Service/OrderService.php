<?php

namespace Service;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;
use Service\CartService;

class OrderService
{
    private Order $orderModel;
    private UserProduct $userProductModel;
    private OrderProduct $orderProductModel;
    private Product $productModel;
    private AuthInterface $authService;
    private CartService $cartService;
    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userProductModel = new UserProduct();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
        $this->authService = new AuthSessionService();
        $this->cartService = new CartService();

    }
    public function createOrder(OrderCreateDTO $data)// ,array $data, User $user)
    {
        $orderSum = $this->cartService->getCartSum();
        if ($orderSum < 100)
        {
            throw new \Exception('Сумма заказа должна превышать 100р рублей');
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
        $orderProducts = $this->orderProductModel->getAllProductFromOrderByOrderId($orderId); //массив ордер продукт(типо юзер продукт но уже заказанные, там есть и продукт и тотал сум)
            foreach ($orderProducts as &$orderProduct) {
            $productId = $orderProduct->getProductId();
            $product = $this->productModel->productByproductId($productId);
            $orderProduct->setProduct($product);
            $totalSum = $orderProduct->getAmount() * $product->getProductPrice();
            $orderProduct->setTotalSum($totalSum);
            }
        return $orderProducts;
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
}