<?php

namespace Controllers;
use Model\User;
use Service\Auth\AuthSessionService;
use Service\Auth\AuthInterface;
use Service\CartService;
use Service\LoggerDBService;
use Service\OrderService;
use Service\LoggerService;
use Service\UserSevice;
use Service\ProductService;
use Service\UserProductService;
use Service\ProductReviewService;


abstract class Controller
{
    protected User $userModel;
    protected AuthInterface $authService;
    protected OrderService $orderService;
    protected CartService $cartService;
    protected LoggerService $loggerService;
    protected LoggerDBService $loggerDBService;
    protected UserSevice $userService;
    protected ProductService $productService;
    protected UserProductService $userProductService;
    protected ProductReviewService $productReviewService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->authService = new AuthSessionService();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->loggerService = new LoggerService();
        $this->loggerDBService = new LoggerDBService();
        $this->userService = new UserSevice();
        $this->productService = new ProductService();
        $this->userProductService = new UserProductService();
        $this->productReviewService = new ProductReviewService;
    }
}