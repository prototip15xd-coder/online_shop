<?php

namespace Request;

class AddOrderRequest
{
    public function __construct(private array $data) {

    }
    public function getContactName(): string {
        return $this->data['name'];
    }
    public function getPhone(): string {
        return $this->data['phone'];
    }
    public function getAddress(): string {
        return $this->data['address'];
    }
    public function getComment(): string {
        return $this->data['comm'];
    }
    public function validate(): array
    {
        $errors = [];
        if ($this->getContactName() !== null) {
            $name = $this->getContactName();
            if (strlen($name) < 4) {
                $errors['name'] = 'Имя должно быть длинее 2 символов';
            }
        } else {
            $errors['name'] = 'Имя должно быть заполнено';
        }

        if ($this->getPhone() !== null) {
            $phone = $this->getPhone();
            if (strlen($phone) > 12 || strlen($phone) < 10) {
                $errors['phone'] = 'Введите корректный номер телефона';
            }
        } else {
            $errors['phone'] = 'Номер телефона должен быть заполнен';
        }

        if (!isset($_POST['address'])) {
            $errors['address'] = 'Адрес получателя должен быть заполнен';
        }

        return $errors;
    }
}