<?php

namespace Service\Auth;

use Model\User;

class AuthCookieService implements AuthInterface
{
    protected User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }

    public function getCurrentUser(): ?User
    {
        if ($this->check()) {
            return $this->userModel->UserbyDB();
        } else {
            return null;
        }
    }

    public function check(): bool
    {
        return isset($_COOKIE['userid']);
    }

    public function auth(string $email, string $password): bool
    {
        $user = $this->userModel->getByEmail($email);

        if (!$user) {
            return false;//$errors['USERNAME'] = 'Все поля должны быть заполнены';
        } else {
            $passwordDB = $user->getUserPassword();
            if (password_verify($password, $passwordDB)) {
                setcookie('userid', $user->getUserID());
                return true;
            } else {
                return false;
            }
        }

    }

    public function logout()
    {
        setcookie('userid', '', time() - 3600, '/');
        unset($_COOKIE['userid']);
    }

    public function checkUser()
    {
        if (!$this->getCurrentUser()) {
            header('Location: /login');
            exit;
        }
    }

}