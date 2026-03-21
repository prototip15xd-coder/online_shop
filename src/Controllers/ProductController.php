<?php

namespace Controllers;
use Model\OrderProduct;
use Model\Product;
use Model\ProductReview;
use Model\UserProduct;
use Model\User;
use Request\AddProductRequest;
use Request\ProductRequest;

class ProductController extends BaseController
{
    protected User $userModel;
    protected OrderProduct $orderProductModel;
    protected Product $productModel;
    protected UserProduct $userProductModel;
    protected ProductReview $productReviewModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
        $this->userProductModel = new UserProduct();
        $this->productReviewModel = new ProductReview();

    }

    public function catalog() ////вьюху переделай
    {
        if ($this->authService->check()) {
            $products = Product::getWithAmount($this->authService->getCurrentUser()->getUserId());

            require_once '/var/www/html/src/Views/catalog.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }

    public function product(ProductRequest $request)  ///отдельный rout add-product
    {
        if ($this->authService->check()) {
            $product_id = $request->getProductId();

            if ($product_id === 0) {
                header("Location: /catalog");
                exit;
            }

            $product = $this->productModel->productByproductId($product_id);

            if (!$product) {
                header("Location: /catalog");
                exit;
            }

            $user_product = $this->userProductModel->userProductByDB($product_id);
            $product->setProductAmount($user_product->getAmount());
            $productReviews = $this->productReview($request->getProductId());

            require_once '/var/www/html/src/Views/product.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }

    public function productReview($product_id)
    {
        $productReviews = $this->productReviewModel->product_reviews($product_id);

        if (isset($productReviews)) {
            foreach ($productReviews as $productReview) {
                $user_id = $productReview->getUserId();
                $user = $this->userModel->UserbyID($user_id);
                $productReview->setUserName($user->getUserName());
            }
        }

        return $productReviews;

    }
}