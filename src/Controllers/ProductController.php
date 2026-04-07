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
            $product_id = $request->getProductId();

            if ($product_id === 0) {
                header("Location: /catalog");
                exit;
            }

            $product = $this->productService->getProduct($product_id);

            if (!$product) {
                header("Location: /catalog");
                exit;
            }

            $user_product = $this->userProductService->getUserProduct($product_id);
            $product->setProductAmount($user_product->getAmount());
            $productReviews = $this->productReview($request->getProductId());

            require_once __DIR__ . '/../Views/product.php';
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function productReview(int $product_id): ?array
    {
        $productReviews = $this->productReviewService->getReview($product_id);

        if (isset($productReviews)) {
            foreach ($productReviews as $productReview) {
                $user_id = $productReview->getUserId();
                $user = $this->userService->getUserbyID($user_id);
                $productReview->setUserName($user->getUserName());
            }
        }

        return $productReviews;
    }
}