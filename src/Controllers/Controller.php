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

abstract class Controller
{
    protected User $userModel;
    protected AuthInterface $authService;
    protected OrderService $orderService;
    protected CartService $cartService;
    protected LoggerService $loggerService;
    protected LoggerDBService $loggerDBService;
    protected UserSevice $userService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->authService = new AuthSessionService();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->loggerService = new LoggerService();
        $this->loggerDBService = new LoggerDBService();
        $this->userService = new UserSevice();
    }
}