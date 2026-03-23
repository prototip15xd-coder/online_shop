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

    public function add_product(int $productId, string $action): void
    {
        $amount = match($action) {
            'plus'  =>  1,
            'minus' => -1,
            default =>  0,
        };

        $userId = $this->authService->getCurrentUser()->getUserId();
        UserProduct::add_productDB($userId, $productId, $amount);
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