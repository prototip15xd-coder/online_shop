<?php

namespace Controllers;

use DTO\OrderCreateDTO;
use Model\Order;
use Request\AddOrderRequest;
use Request\OrderRequest;

class OrderController extends BaseController /// здесь повторяется только проверка, как отделить часть цикла я не совсем понимаю
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
        $user = $this->userModel->UserbyDB();
        if (empty($errors)) {
            $dto = new OrderCreateDTO($request->getContactName(),
                $request->getPhone(),
                $request->getComment(),
                $request->getAddress(),
                $user);
            $this->orderService->createOrder($dto);
            header('Location: /orders');
        } else {
            require_once '/var/www/html/src/Views/create-order.php';
        }
    }
//    private function validate(): array
//    {
//        $errors = [];
//        if (isset($_POST['name'])) {
//            $name = $_POST['name'];
//            if (strlen($name) < 4) {
//                $errors['name'] = 'Имя должно быть длинее 2 символов';
//            }
//        } else {
//            $errors['name'] = 'Имя должно быть заполнено';
//        }
//        if (isset($_POST['phone'])) {
//            $phone = $_POST['phone'];
//            if (strlen($phone) > 12 || strlen($phone) < 10) {
//                $errors['phone'] = 'Введите корректный номер телефона';
//            }
//        } else {
//            $errors['phone'] = 'Номер телефона должен быть заполнен';
//        }
//        if (!isset($_POST['address'])) {
//            $errors['address'] = 'Адрес получателя должен быть заполнен';
//        }
//        return $errors;
//    }
    public function getAllOrders()
    {
        $this->authService->checkUser();
        $ordersModel = $this->orderModel->getOrders($_SESSION['userid']); /// содержит масив заказов
        $orders = [];
        foreach ($ordersModel as $order) {
            $orderId = $order->getOrderId();
            $order = $this->orderService->getOrder($orderId,$order);
            $orders[] = $order;
        }
        require_once '/var/www/html/src/Views/user_orders.php';
    }

    public function getOrderByOrderID(OrderRequest $request)
    {
        $this->authService->checkUser();
        $orderModel = $this->orderModel->getOrder($request->getOrderId());
        $order = $this->orderService->getOrder($request->getOrderId(),$orderModel);
        require_once '/var/www/html/src/Views/order.php';
    }
//    public function checkUser()
//    {
//        if (!$this->authService->getCurrentUser()) {
//            header('Location: /login');
//            exit;
//        }
//    }
}

