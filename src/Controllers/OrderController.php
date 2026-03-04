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
        //$user = $this->userModel->UserbyDB();
        if (empty($errors)) {
            $dto = new OrderCreateDTO($request->getContactName(), ////зачем здесь dto если мы потом делаем тоже самое???
                $request->getPhone(),
                $request->getComment(),
                $request->getAddress()); /// как здесь избежать передачи юзерИд?
            $this->orderService->createOrder($dto);
            header('Location: /orders');
        } else {
            ///передай обратно товары и сумму заказа
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

