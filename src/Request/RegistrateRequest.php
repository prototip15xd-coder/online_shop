<?php

namespace Request;

class RegistrateRequest
{
    public function __construct(private array $data) {

    }
    public function getName(): string {
        return $this->data['name'];
    }
    public function getEmail(): string {
        return $this->data['email'];
    }
    public function getPassword(): string {
        return $this->data['psw'];
    }
    public function getPasswordRepeat(): string {
        return $this->data['psw-repeat'];
    }
    public function validate()
    {
        $errors = [];
        if ($this->getName() !== null) {  //['name']
            $name = $this->getName();
            if (strlen($name) < 4) {
                $errors['name'] = 'Имя должно быть длинее 4 символов';
            }
        } else {
            $errors['name'] = 'Имя должно быть заполнено';
        }

        if ($this->getEmail() !== null) { //['email']
            $email = $this->getEmail();
            if (strpos($email, '@') === false) {
                $errors['email'] = 'email должен содержать знак @';
            }
        } else {
            $errors['email'] = 'email должен быть заполнен';
        }

        if ($this->getPassword() !== null) { ///['psw']
            $password = $this->getPassword();
            $psw_repeat = $this->getPasswordRepeat(); //['psw-repeat']
            if ($password !== $psw_repeat) {
                $errors['psw-repeat'] = "Пароли не совпадают\n";
            }
        } else {
            $errors['password'] = 'пароли должны быть заполнены';
        }
        return $errors;
    }

}