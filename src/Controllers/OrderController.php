<?php

namespace Controllers;

use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;

class OrderController extends BaseController /// здесь повторяется только проверка, как отделить часть цикла я не совсем понимаю
{
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private Product $productModel;
    private UserProduct $userProductModel;


    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
        $this->userProductModel = new UserProduct();
    }

    public function getCheckoutForm()
    {
        $this->checkUser();
        require_once '/var/www/html/src/Views/create-order.php';
    }

    public function handleCheckoutOrder()
    {
        $this->checkUser();
        $errors = $this->validate();
        if (empty($errors)) {
            $data = $_POST;
            $user = $this->authService->getCurrentUser();
            $this->orderService->createOrder($data, $user);
            header('Location: /orders');
        } else {
            require_once '/var/www/html/src/Views/create-order.php';
        }
    }

    private function validate(): array
    {
        $errors = [];
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            if (strlen($name) < 4) {
                $errors['name'] = 'Имя должно быть длинее 2 символов';
            }
        } else {
            $errors['name'] = 'Имя должно быть заполнено';
        }

        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
            if (strlen($phone) > 12 || strlen($phone) < 10) {
                $errors['phone'] = 'Введите корректный номер телефона';
            }
        } else {
            $errors['phone'] = 'Номер телефона должен быть заполнен';
        }

        if (!isset($_POST['address'])) {
            $errors['address'] = 'Адрес получателя должен быть заполнен';
        }

        return $errors;
    }

    public function getAllOrders()
    {
        $this->checkUser();
        $orders = $this->orderModel->getOrders($_SESSION['userid']);
        foreach ($orders as $order) {
            $orderId = $order->getOrderId();
            $orderProducts = $this->orderProductModel->getAllProductFromOrderByOrderId($orderId);
            $productsData = [];

            foreach ($orderProducts as $orderProduct) {
                $productId = $orderProduct->getProductId();
                $product = $this->productModel->productByproductId($productId);
                $order->addProduct($product, $orderProduct->getAmount());
            }
        }
        require_once '/var/www/html/src/Views/user_orders.php';
    }

    public function getOrderByOrderID()
    {
        $this->checkUser();
        $data = $_POST;
        $order = $this->orderModel->getOrder($data['order_id']); /// содержит объект заказа (имя,адрес,тел)
        //// содержит массив объектов user_product
        $orderProducts = $this->orderProductModel->getAllProductFromOrderByOrderId($data['order_id']);
        /// содержит массив объектов продуктов
        $products = [];
        foreach ($orderProducts as $orderProduct) {
            $productId = $orderProduct->getProductId();
            $product = $this->productModel->productByproductId($productId);
            $order->products[] = $products;
            $order->amount = $orderProduct->getAmount();
        }





//        //var_dump($orders);
//        $userOrder = [];
//        foreach ($orders as $order) {
//            $orderId = $order->getOrderId();
//            $orderProducts = $this->orderProductModel->getAllProductFromOrderByOrderId($orderId);
//            $userOrder['orderProducts'] = $orderProducts;
//        }
//        //$newUserOrder[] = $userOrder;
//        //var_dump($userOrder);
        require_once '/var/www/html/src/Views/order.php';
    }
    public function checkUser()
    {
        if (!$this->authService->getCurrentUser()) {
            header('Location: /login');
            exit;
        }
    }
}

