<?php

namespace Service;

use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService
{
    private AuthInterface $authService;

    public function __construct()
    {
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

        UserProduct::add_productDB($amount);
        $action = false;
    }

    public function getUserProducts(): array
    {
        $user = $this->authService->getCurrentUser();
        $userProducts = UserProduct::getByUserIdWithProducts($user->getUserId());

        foreach ($userProducts as &$userProduct) {
            $totalSum = $userProduct->getAmount() * $userProduct->getProduct()->getProductPrice();
            $userProduct->setTotalSum($totalSum);
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