<?php

namespace Controllers;
use Model\User;
use Service\AuthService;

abstract class BaseController
{
    protected User $userModel;
    protected AuthService $authService;
    public function __construct()
    {
        $this->userModel = new User();
        $this->authService = new AuthService();
    }
    public function getCurrentUser(): ?User
    {
        $this->session();
        if ($this->check()) {
            return $this->userModel->UserbyDB();
        } else {
            return null;
        }
    }
    public function check(): bool
    {
        //$this->session();
        print_r($_SESSION['userid']);
        return isset($_COOKIE['userid']);
    }

    public function auth(string $email, string $password): bool
    {
        $user = $this->userModel->getByEmail($email);
        if (!$user) {
            return false;//$errors['USERNAME'] = 'Все поля должны быть заполнены';
        } else {
            $passwordDB = $user->getPassword();
            if (password_verify($password, $passwordDB)) {
                $this->session();
                $_SESSION['userid'] = $user->getId();
                return true;
            } else {
                echo "333333";
                return false;
            }
        }
    }

    private function session()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    public function logout()
    {
        $this->session();
        session_destroy();
    }


}