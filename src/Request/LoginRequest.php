<?php

namespace Request;

class LoginRequest
{
    public function __construct(private array $data) {}

    public function getEmail(): string
    {
        return $this->data['email'];
    }

    public function getPassword(): string
    {
        return $this->data['password'];
    }

    public function validate(): array
    {
        $errors = [];

        if (empty($this->getEmail()) || empty($this->getPassword())) {
            $errors['USERNAME'] = 'Все поля должны быть заполнены';
        }

        return $errors;
    }

}