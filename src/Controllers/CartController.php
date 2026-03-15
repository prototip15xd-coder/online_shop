<?php

namespace Controllers;

use Model\Product;
use Model\UserProduct;
use Request\AddProductRequest;
use Service\Auth\AuthSessionService;
use Service\CartService;

class CartController extends BaseController
{
    private UserProduct $userProductModel;
    private Product $productModel;

    public function __construct() {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->cartService = new CartService();
        $this->authService = new AuthSessionService();
    }
//    public function cart() /// старая версия
//    {
//        if (isset($_SESSION['userid'])) {
//            $all_products = $this->cartModel->cartbyDB();
//            require_once '/var/www/html/src/Views/cart.php';
//        } else {
//            require_once '/var/www/html/src/Views/login.php';
//        }
//
//    }

    public function cart()
    {
        if ($this->authService->check()) {
            $all_products = $this->cartService->getUserProducts();
            $cartTotalSum = $this->cartService->getCartSum();
//            foreach ($user_products as $user_product) {
//                $product_id = $user_product->getProductId();
//                $product = $this->productModel->productByproductId($product_id);/////должен быть объект продукта
//                $product_amount = $user_product->getAmount(); /// просто количество
//                $product->amount = $product_amount;
//                $all_products[] = $product;
//            }
            require_once '/var/www/html/src/Views/cart.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }
    public function add_product_validate($action, $productId)   /// сделать реализацию +- в самой корзине?
    {
        $errors = [];
        $objUserProduct = $this->userProductModel->userProductByDB($productId);
        $amount = $objUserProduct->getAmount();
        if ($this->authService->check()) {
            $res = $this->productModel->validate_product($productId);
            if (!isset($res)) {
                $errors['product_id'] = 'Данный товар не существует или закончился';
            } else {
                if ($action === 'minus') {
                    $amount -= 1;
                    if ($amount < 0) {
                        $errors['amount'] = 'Количество товаров должно быть больше нуля';
                    }
                }
            }
        }
        return $errors;
    }

    public function addProduct(AddProductRequest $request)
    {
        $errors = $this->add_product_validate($request->getAction(), $request->getProductId());
        if (empty($errors)) {
            $this->cartService->add_product();
        }
        header('Location: /catalog');
    }

//    public function getUserProducts(): array    ////вот это прикольная фича ее оставь
//    {
//        $user = $this->authService->getCurrentUser();
//        if ($user === null) {
//            return [];
//        }
//        $userProducts = $this->userProductModel->getUserProducts(); ////там глобальная перемеенная SESSION убери
//        /// содержит массив объектов продуктов
//        foreach ($userProducts as &$userProduct) {
//            $product = $this->productModel->productByproductId($userProduct->getProductId());
//            $userProduct->setProduct($product);
//            $totalSum = $userProduct->getAmount() * $userProduct->getProduct()->getPrice();//    $product->getProductPrice(); ////ты обращается к продукту напрямую а в видео
//            ///  у юзер продукт есть св-во продукт и обращаются через юзпродукт-продукт-цена
//            $userProduct->setTotalSum($totalSum);
//            $product->amount = $userProduct->getAmount();////свойтсов не исп нужно переделать переменные?
//        }
//        return $userProducts;
//    }
}