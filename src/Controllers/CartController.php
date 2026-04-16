<?php

declare(strict_types=1);

namespace Controllers;

use Request\AddProductRequest;

class CartController extends Controller
{
    public function cart(): void
    {
        if ($this->authService->check()) {
            $allProducts = $this->cartService->getUserProducts();
            $cartTotalSum = $this->cartService->getCartSum();

            require_once __DIR__ . '/../Views/cart.php';
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }


    public function addProduct(AddProductRequest $request): void
    {
        $errors = $this->cartService->addProductValidate($request->getAction(), $request->getProductId());

        if (empty($errors)) {
            $this->cartService->addProduct($request->getProductId(), $request->getAction());

            $userId = $this->authService->getCurrentUser()->getUserId();
            $products = $this->productService->getProductWithAmount($userId);

            $updatedProduct = null;
            foreach ($products as $product) {
                if ($product->getProductId() === $request->getProductId()) {
                    $updatedProduct = $product;
                    break;
                }
            }
            $totalCount = array_sum(array_map(
                fn($product) => $product->getProductAmount() ?? 0,
                $products
            ));

            $amount = 0;
            if ($updatedProduct !== null) {
                $amount = $updatedProduct->getProductAmount() ?? 0;
            }

            header('Content-Type: application/json');
            echo json_encode([
                'amount' => $amount,
                'count'  => $totalCount,
            ]);
            exit;
        }

        header('Content-Type: application/json');
        http_response_code(422);
        echo json_encode(['errors' => $errors]);
        exit;
    }
}
