<?php

namespace Controllers;

use Model\Product;
use Model\UserProduct;
use Request\AddProductRequest;

class CartController extends Controller // OPTIMIZE: переложи логику моделей в сервис
{
    private UserProduct $userProductModel;
    private Product $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
    }

    public function cart()
    {
        if ($this->authService->check()) {
            $allProducts = $this->cartService->getUserProducts();
            $cartTotalSum = $this->cartService->getCartSum();

            require_once '/var/www/html/src/Views/cart.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }

    public function addProductValidate(string $action, int $productId): array  // TODO: сделать реализацию +- в самой корзине
    {
        $errors = [];
        $objUserProduct = $this->userProductModel->userProductByDB($productId);
        $amount = $objUserProduct->getAmount();

        if ($this->authService->check()) {
            $result = $this->productModel->validateProduct($productId);

            if (!isset($result)) {
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
        $errors = $this->addProductValidate($request->getAction(), $request->getProductId());

        if (empty($errors)) {
            $this->cartService->addProduct($request->getProductId(), $request->getAction());

            $userId = $this->authService->getCurrentUser()->getUserId();
            $products = Product::getWithAmount($userId);

            $updatedProduct = null;
            foreach ($products as $product) {
                if ($product->getProductId() === (int)$request->getProductId()) {
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
                'amount' => $updatedProduct->getProductAmount() ?? 0,
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
