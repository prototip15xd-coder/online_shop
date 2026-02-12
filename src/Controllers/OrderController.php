<?php

namespace Controllers;

use Model\Cart;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Service\AuthService;

class OrderController extends BaseController
{
    private Cart $cartModel;
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private Product $productModel;


    public function __construct()
    {
        parent::__construct();
        $this->cartModel = new Cart();
        $this->orderModel = new Order();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
    }

    public function getCheckoutForm()
    {
        if ($this->authService->check()) {
            require_once '/var/www/html/src/Views/create-order.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }

    public function handleCheckoutOrder()
    {
        //$this->session();
        if ($this->authService->check()) {
            $errors = $this->validate($_POST);

            if (empty($errors)) {
                $contactName = $_POST['name'];
                $contactPhone = $_POST['phone'];
                $contactComm = $_POST['comm'];
                $address = $_POST['address'];
                $user = $this->getCurrentUser();

                $orderId = $this->orderModel->create($contactName, $contactPhone, $contactComm, $address, $user->getId());

                $user_products = $this->cartModel->getAllUserProductsByUser($user->getId());

                foreach ($user_products as $user_product) {
                    $productId = $user_product['product_id'];
                    $amount = $user_product['amount'];
                    $orderProduct = $this->orderProductModel->createOrderProduct(
                        $orderId,
                        $productId,
                        $amount);
                }
                $this->cartModel->deleteByUser($user->getId());
                header('Location: /orders');


            } else {
                require_once '/var/www/html/src/Views/create-order.php';
            }
        } else {
            header('Location: /login');
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
        //$this->session();
        if ($this->authService->check()) {
            $orders = $this->orderModel->getOrders($this->getCurrentUser());
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
        } else {
            header('Location: /login');
        }
    }

    public function getOrderByOrderID(): array
    {
        if ($this->authService->check()) {
            $orders = $this->orderModel->getOrders($this->getCurrentUser());
            $userOrder = [];
            foreach ($orders as $order) {
                $orderId = $order->getId();
                $orderProducts = $this->orderProductModel->getAllProductFromOrderByOrderId($orderId);
                $userOrder['orderProducts'] = $orderProducts;
            }
            $newUserOrder[] = $userOrder;
            return $newUserOrder;
        } else {
            header('Location: /login');
        }
    }
}

