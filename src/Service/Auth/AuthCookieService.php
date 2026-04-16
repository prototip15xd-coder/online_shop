<?php

declare(strict_types=1);

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
            return $this->userModel->userbyDB();
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
            return false;
        } else {
            $passwordDB = $user->getUserPassword();
            $userId = $user->getUserId();

            if (password_verify($password, $passwordDB)) {
                setcookie('userid', $userId, time() + (86400 * 30), "/"); // не понимаю почему ругается

                return true;
            } else {
                return false;
            }
        }
    }

    public function logout(): void
    {
        setcookie('userid', '', time() - 3600, '/');
        unset($_COOKIE['userid']);
    }

    public function checkUser(): void
    {
        if (!$this->getCurrentUser()) {
            header('Location: /login');
            exit;
        }
    }

}