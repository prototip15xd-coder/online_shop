<?php

declare(strict_types=1);

namespace Service;

use Model\UserProduct;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService extends Service
{
    private AuthInterface $authService;
    private ProductService $productService;
    private UserProductService $userProductService;


    public function __construct()
    {
        $this->authService = new AuthSessionService();
        $this->productService = new ProductService();
        $this->userProductService = new UserProductService();
    }


    public function addProduct(int $productId, string $action): void
    {
        $amount = match ($action) {
            'plus'  =>  1,
            'minus' => -1,
            default =>  0,
        };

        $userId = $this->authService->getCurrentUser()->getUserId();
        UserProduct::addProductDB($userId, $productId, $amount);
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

    public function getCartSum(): float
    {
        $total = 0.0;

        foreach ($this->getUserProducts() as $userProduct) {
            $total += $userProduct->getTotalSum();
        }

        return $total;
    }

    public function addProductValidate(string $action, int $productId): array
    {
        $errors = [];
        $objUserProduct = $this->userProductService->getUserProduct($productId);
        $amount = $objUserProduct->getAmount();

        if ($this->authService->check()) {
            $result = $this->productService->rowCountProduct($productId);

            if ($result === 0) {
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
}