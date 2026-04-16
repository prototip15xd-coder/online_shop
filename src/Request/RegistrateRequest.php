<?php

declare(strict_types=1);

namespace Request;

class RegistrateRequest
{
    public function __construct(private array $data) {}

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getEmail(): string
    {
        return $this->data['email'];
    }

    public function getPassword(): string
    {
        return $this->data['psw'];
    }

    public function getPasswordRepeat(): string
    {
        return $this->data['psw-repeat'];
    }

    public function validate(): array
    {
        $errors = [];

        if ($this->getName() !== null) {
            $name = $this->getName();

            if (strlen($name) < 4) {
                $errors['name'] = 'Имя должно быть длиннее 4 символов';
            }

        } else {
            $errors['name'] = 'Имя должно быть заполнено';
        }

        if (!empty($this->getEmail())) {
            $email = $this->getEmail();

            if (strpos($email, '@') === false) {
                $errors['email'] = 'email должен содержать знак @';
            }

        } else {
            $errors['email'] = 'email должен быть заполнен';
        }

        if (!empty($this->getPassword())) {
            $password = $this->getPassword();
            $passwordRepeat = $this->getPasswordRepeat();

            if ($password !== $passwordRepeat) {
                $errors['psw-repeat'] = "Пароли не совпадают\n";
            }

        } else {
            $errors['password'] = 'пароли должны быть заполнены';
        }

        return $errors;
    }
}