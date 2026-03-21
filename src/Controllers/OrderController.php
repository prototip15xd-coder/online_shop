<?php

namespace Controllers;

use DTO\OrderCreateDTO;
use Model\Order;
use Request\AddOrderRequest;
use Request\OrderRequest;

class OrderController extends BaseController
{
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
    }

    public function getCheckoutForm()
    {
        $this->authService->checkUser();
        require_once '/var/www/html/src/Views/create-order.php';
    }

    public function handleCheckoutOrder(AddOrderRequest $request)
    {
        $this->authService->checkUser();
        $errors = $request->validate();

        if (empty($errors)) {
            $dto = new OrderCreateDTO($request->getContactName(),
                $request->getPhone(),
                $request->getComment(),
                $request->getAddress()); /// как здесь избежать передачи юзерИд?
            $this->orderService->createOrder($dto);
            header('Location: /orders');
        } else {
            require_once '/var/www/html/src/Views/create-order.php';
        }
    }

    public function getAllOrders()
    {
        $orders = $this->orderService->getAllOrders();
        require_once '/var/www/html/src/Views/user_orders.php';
    }

    public function getOrderByOrderID(OrderRequest $request)
    {
        $this->authService->checkUser();
        $user = $this->authService->getCurrentUser();
        $order= $this->orderModel->getOrder($request->getOrderId());
        $order->setOrderProducts($this->orderService->getOrderProduct($request->getOrderId()));
        /////добавь сюда тотал сум и вызов полной цены заказа
        require_once '/var/www/html/src/Views/order.php';
    }

}

