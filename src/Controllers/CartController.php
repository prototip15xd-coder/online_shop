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

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->cartService = new CartService();
        $this->authService = new AuthSessionService();
    }

    public function cart()
    {
        if ($this->authService->check()) {
            $all_products = $this->cartService->getUserProducts();
            $cartTotalSum = $this->cartService->getCartSum();

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
            $this->cartService->add_product($request->getProductId(), $request->getAction());

            $userId   = $this->authService->getCurrentUser()->getUserId();
            $products = Product::getWithAmount($userId);

            $updatedProduct = null;
            foreach ($products as $product) {
                if ($product->getProductId() === $request->getProductId()) {
                    $updatedProduct = $product;
                    break;
                }
            }

            $totalCount = array_sum(array_map(
                fn($p) => $p->getProductAmount() ?? 0,
                $products
            ));

            header('Content-Type: application/json');
            echo json_encode([
                'amount' => $updatedProduct?->getProductAmount() ?? 0,
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