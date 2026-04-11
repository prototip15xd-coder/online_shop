<?php

declare(strict_types=1);

namespace DTO;

class UserCreateDTO
{
    public function __construct(
        private string $userName,
        private string $userEmail,
        private string $password
    ) {}

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}