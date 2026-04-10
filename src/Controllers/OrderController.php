<?php

declare(strict_types=1);

namespace Controllers;

use DTO\OrderCreateDTO;
use Request\AddOrderRequest;
use Request\OrderRequest;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCheckoutForm(): void
    {
        $this->authService->checkUser();
        require_once __DIR__ . '/../Views/create-order.php';
    }

    public function handleCheckoutOrder(AddOrderRequest $request): void
    {
        $this->authService->checkUser();
        $errors = $request->validate();

        if (empty($errors)) {
            $dto = new OrderCreateDTO($request->getContactName(),
                $request->getPhone(),
                $request->getComment(),
                $request->getAddress());
            $this->orderService->createOrder($dto);
            header('Location: /orders');
            exit();
        } else {
            require_once __DIR__ . '/../Views/create-order.php';
        }
    }

    public function getAllOrders(): void
    {
        if ($this->authService->getCurrentUser() !== null) {
            $orders = $this->orderService->getAllOrders();
            require_once __DIR__ . '/../Views/user_orders.php';
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function getOrderByOrderID(OrderRequest $request): void
    {
        if ($this->authService->getCurrentUser() !== null) {
            $order = $this->orderService->getOrder($request->getOrderId());
            $order->setOrderProducts($this->orderService->getOrderProduct($request->getOrderId()));
            require_once __DIR__ . '/../Views/order.php';
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }
}

