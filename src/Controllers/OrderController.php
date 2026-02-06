<?php

namespace Controllers;

use Model\Cart;
use Model\Order;
use Model\OrderProduct;
use Model\Product;

class OrderController
{
    private Cart $cartModel;
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private Product $productModel;


    public function __construct()
    {
        $this->cartModel = new Cart();
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
    }

    public function getCheckoutForm()
    {
        if (isset($_SESSION['userid'])) {
            require_once '/var/www/html/src/Views/create-order.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }

    public function handleCheckoutOrder()     ///НЕ РАБОТАЕТ?
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['userid'])) {
            header('Location: /login');
            exit;
        }

        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $contactName = $_POST['name'];
            $contactPhone = $_POST['phone'];
            $contactComm = $_POST['comm'];
            $address = $_POST['address'];
            $userId = $_SESSION['userid'];

            $orderId = $this->orderModel->create($contactName, $contactPhone, $contactComm, $address, $userId);

            $user_products = $this->cartModel->getAllByUserId($userId);

            foreach ($user_products as $user_product) {
                $productId = $user_product['product_id'];
                $amount = $user_product['amount'];
                $orderProduct = $this->orderProductModel->createOrderProduct(
                    $orderId,
                    $productId,
                    $amount);
            }
            $this->cartModel->deleteByUserId($userId);
            header('Location: /orders');


        } else {
            require_once '/var/www/html/src/Views/create-order.php';
        }

    }

    private function validate(array $data): array
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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userid'])) {
            header('Location: /login');
            exit;
        }
        $orders = $this->orderModel->getOrders($_SESSION['userid']);
        foreach ($orders as $order) {
            $orderId = $order->getId();
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

    public function getOrderByOrderID(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userid'])) {
            header('Location: /login');
        }
        $orders = $this->orderModel->getOrders($_SESSION['userid']);
        $userOrder = [];
        foreach ($orders as $order) {
            $orderId = $order->getId();
            $orderProducts = $this->orderProductModel->getAllProductFromOrderByOrderId($orderId);
            $userOrder['orderProducts'] = $orderProducts;
        }
        $newUserOrder[] = $userOrder;
        return $newUserOrder;
    }
}

