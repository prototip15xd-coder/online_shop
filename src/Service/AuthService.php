<?php

namespace Service;

use Model\User;

class AuthService
{

    protected User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
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