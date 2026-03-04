<?php

namespace Service;

use Model\Product;
use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService
{
    private UserProduct $userProductModel;
    private Product $productModel;
    private AuthInterface $authService;

    public function __construct()
    {
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->authService = new AuthSessionService();
    }
    public function add_product()
    {
        $action = $_POST['action'];
        if ($action === 'plus') {
            $amount = 1;
        } elseif ($action === 'minus') {
            $amount = -1;
        } else {
            $amount = 0;
        }
        $this->userProductModel->add_productDB($amount);
        $action = false;
    }
    public function getUserProducts(): array ////нужно исправить вьюху
    {
        $user = $this->authService->getCurrentUser();
        $userProducts = $this->userProductModel->getUserProducts($user->getUserId()); /// содержит массив объектов продуктов
        foreach ($userProducts as &$userProduct) {
            $product = $this->productModel->productByproductId($userProduct->getProductId());
            $totalSum = $userProduct->getAmount() * $product->getProductPrice(); ////ты обращается к продукту напрямую а в видео
            ///  у юзер продукт есть св-во продукт и обращаются через юзпродукт-продукт-цена
            $userProduct->setTotalSum($totalSum);
            $product->setProductAmount($userProduct->getAmount());
            $userProduct->setProduct($product);////свойтсов не исп нужно переделать переменные?
        }
        return $userProducts;
    }
    public function getCartSum(): int   //25:48     цена заказа должно высвечиваться внизу в корзине!
    {
        $total = 0;
        foreach ($this->getUserProducts() as $userProduct) {
            $total += $userProduct->getTotalSum();
        }
        return $total;
    }

}