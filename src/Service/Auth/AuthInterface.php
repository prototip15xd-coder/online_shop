<?php

declare(strict_types=1);

namespace Service\Auth;

use Model\User;

interface AuthInterface
{
    public function getCurrentUser(): ?User;

    public function check(): bool;

    public function auth(string $email, string $password): bool;

    public function logout();

    public function checkUser();

}