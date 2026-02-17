<?php

namespace Controllers;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;
use Model\User;
use Service\AuthService;

class ProductController extends BaseController
{
    protected User $userModel;
    protected Product $productModel;
    protected OrderProduct $orderProductModel;
    protected UserProduct $userProductModel;


    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProduct();
    }
    public function catalog()
    {
        if (isset($_POST['action'])) {
            $errors = $this->add_product();
        }
        if ($this->authService->check()) {
            $products = $this->productModel->productByDB();
            $productsAmount = [];
            foreach ($products as $product) {
                $product_id = $product->getId();
                $user_product = $this->userProductModel->userProductByDB($product_id);
                $amount = $user_product->getAmount();
                $productsAmount[$product_id] = $user_product->getAmount();
            }
            require_once '/var/www/html/src/Views/catalog.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }

    public function add_product_validate($action)
    {
        $errors = [];
        $product_id = $_POST["product_id"];
        $objUserProduct = $this->userProductModel->userProductByDB($product_id);
        $amount = $objUserProduct->getAmount();
        if ($this->authService->check()) {
            $res = $this->productModel->validate_product();
            if (!isset($res)) {
                $errors['product_id'] = 'Данный товар не существует или закончился';
            } else {
                if ($action === 'minus' || $action === 'remove') {
                    $amount -= 1;
                    if ($amount < 0) {
                        $errors['amount'] = 'Количество товаров должно быть больше нуля';
                    }
                }
            }
        }
        return $errors;
    }

    public function add_product()
    {
        $errors = $this->add_product_validate($_POST['action']);
        if (empty($errors)) {
            $action = $_POST['action'];
            if ($action === 'plus') {
                $this->productModel->add_productDB();
                $action = false;  /// когда отправляю запрос на +- то после обновления страницы запрос в перемнной action сохраняется  а не сбрасывается
            } else if ($action === 'minus' || $action === 'remove') {
                $this->productModel->delete_productDB();
                $action = false;
            }
        }
        //$products = $this->catalog();
    }
    public function product()
    {
        if (isset($_POST['action'])) {
            $errors = $this->add_product();
        } //попоробуй реализовать +- на странице товара   НУЖНО ПРИДУМАТЬ КАК СВЯЗАТЬ ДАННЫЕ
        // КАТАЛОГА ПОПРОБУЙ ВЫНЕСТИ +- В ОТДЕЛЬНУЮ ФУНКЦИЮ! ЧТОБЫ ИСП В КАТ И ПРОД
        if ($this->authService->check()) {
            $product_id = $_POST["product_id"];
            $product = $this->productModel->productByproductId($product_id);
            $productsAmount = []; //это нужно для +-
            $productReviews = $this->productModel->product_reviews($product_id);
            if (isset($productReviews)) {
                foreach ($productReviews as &$productReview) {
                    $user_id = $productReview['user_id'];
                    $user = $this->userModel->UserbyDB(); ////здесь возможно стоит обратиться к getName()
                    $userName = $user->getName();
                    $productReview['user_name'] = $userName;
                }//// теперь продукт ревью сод массив с отзывами где еще есть ключ изер нейм
            }
            require_once '/var/www/html/src/Views/product.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }
}