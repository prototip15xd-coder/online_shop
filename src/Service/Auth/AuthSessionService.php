<?php

declare(strict_types=1);

namespace Service\Auth;

use Model\User;

class AuthSessionService implements AuthInterface
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
            return $this->userModel->userbyDB();
        } else {
            return null;
        }
    }

    public function check(): bool
    {
        $this->session();
        return isset($_SESSION['userid']);
    }

    public function auth(string $email, string $password): bool
    {
        $user = $this->userModel->getByEmail($email);

        if (!$user) {
            return false;
        } else {
            $passwordDB = $user->getUserPassword();

            if (password_verify($password, $passwordDB)) {
                $this->session();
                $_SESSION['userid'] = $user->getUserId();
                return true;
            } else {
                return false;
            }
        }
    }

    private function session(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function logout(): void
    {
        $this->session();
        session_destroy();
    }

    public function checkUser(): void
    {
        if (!$this->getCurrentUser()) {
            header('Location: /login');
            exit;
        }
    }
}