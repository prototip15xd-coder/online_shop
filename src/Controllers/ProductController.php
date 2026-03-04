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
    protected Product $productModel;
    protected OrderProduct $orderProductModel;
    protected UserProduct $userProductModel;
    protected ProductReview $productReviewModel;



    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
        $this->userProductModel = new UserProduct();
        $this->productReviewModel = new ProductReview();

    }
    public function catalog() ////вьюху переделай
    {
        if ($this->authService->check()) {
            $products = $this->productModel->productByDB();
            foreach ($products as $product) {
                $product_id = $product->getProductId();
                $user_product = $this->userProductModel->userProductByDB($product_id);
                $product->setProductAmount($user_product->getAmount());
            }
            require_once '/var/www/html/src/Views/catalog.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }

//    public function addProductValidate(AddProductRequest $request) //// уместно ли его перевести в AddProductRequest?
//    {
//        $errors = [];
//        $product_id = $request->getProductId();
//        $objUserProduct = $this->userProductModel->userProductByDB($product_id);
//        $amount = $objUserProduct->getAmount();
//        if ($this->authService->check()) {
//            $res = $this->productModel->validate_product();
//            if (!isset($res)) {
//                $errors['product_id'] = 'Данный товар не существует или закончился';
//            } else {
//                if ($request->getAction() === 'minus') {
//                    $amount -= 1;
//                    if ($amount < 0) {
//                        $errors['amount'] = 'Количество товаров должно быть больше нуля';
//                    }
//                }
//            }
//        }
//        return $errors;
//    }
//
//    public function add_product()
//    {
//        $errors = $this->add_product_validate($_POST['action']);
//        if (empty($errors)) {
//            $action = $_POST['action'];
//            if ($action === 'plus') {
//                $this->userProductModel->add_productDB();
//                $action = false;  /// когда отправляю запрос на +- то после обновления страницы запрос в перемнной action сохраняется  а не сбрасывается
//            } else if ($action === 'minus' || $action === 'remove') {
//                $this->userProductModel->delete_productDB();
//                $action = false;
//            }
//        }
//        //$products = $this->catalog();
//    }
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
//    public function addProduct(AddProductRequest $request)
//    {
//        if ($request->getAction() !== null) {
//            $errors = $this->addProductValidate($request);
//            if (empty($errors)) {
//                $this->cartService->add_product();
//            }
//            header("Location: /catalog");
//        }
//    }
}