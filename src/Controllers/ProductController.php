<?php

declare(strict_types=1);

namespace Controllers;

use Request\ProductRequest;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function catalog(): void
    {
        if ($this->authService->check()) {
            $products = $this->productService->getProductWithAmount($this->authService->getCurrentUser()->getUserId());
            $totalCount = 0;

            foreach ($products as $product) {
                $totalCount += $product->getProductAmount();
            }

            require_once __DIR__ . '/../Views/catalog.php';
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function product(ProductRequest $request): void
    {
        if ($this->authService->check()) {
            $productId = $request->getProductId();

            if ($productId === 0) {
                header("Location: /catalog");
                exit;
            }

            $product = $this->productService->getProduct($productId);

            if (!$product) {
                header("Location: /catalog");
                exit;
            }

            $userProduct = $this->userProductService->getUserProduct($productId);
            $product->setProductAmount($userProduct->getAmount());
            $productReviews = $this->productReview($request->getProductId());

            require_once __DIR__ . '/../Views/product.php';
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function productReview(int $productId): ?array
    {
        $productReviews = $this->productReviewService->getReview($productId);

        if (!empty($productReviews)) {
            foreach ($productReviews as $productReview) {
                $userId = $productReview->getUserId();
                $user = $this->userService->getUserByID($userId);
                $productReview->setUserName($user->getUserName());
            }
        }

        return $productReviews;
    }
}