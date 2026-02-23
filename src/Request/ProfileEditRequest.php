<?php

namespace Request;

class ProfileEditRequest
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

}