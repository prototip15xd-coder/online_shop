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

    public function addProduct(int $productId, string $action): void
    {
        $amount = match($action) { // проблема из 48 строки
            'plus'  =>  1,
            'minus' => -1,
            default =>  0,
        };

        $userId = $this->authService->getCurrentUser()->getUserId();
        // PSR-1: Имена методов ДОЛЖНЫ быть в camelCase.
        // add_productDB — нарушение, snake_case здесь недопустим.
        // Переименуй в addProductDB или addProduct (в самой модели тоже нужно исправить)
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

    // PSR-12: Встроенный комментарий с временной меткой (//25:48) — не подходит для продакшн-кода.
    // Используй стандартный формат: // TODO: цена заказа должна отображаться внизу в корзине
    // PSR-12: match — это языковая конструкция, перед скобкой должен быть пробел: match ($action)
    // (см. метод addProduct выше — та же проблема)
    public function getCartSum(): int   //25:48     цена заказа должно высвечиваться внизу в корзине!
    {
        $total = 0;

        foreach ($this->getUserProducts() as $userProduct) {
            $total += $userProduct->getTotalSum();
        }

        return $total;
    }
}