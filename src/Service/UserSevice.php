<?php

namespace Service;

use Model\User;

class UserSevice
{
    public function __construct()
    {
        $this->userModel = new User();
    }
    public function registrate($dto)
    {
        $this->userModel->registrate($dto->getName(), $dto->getEmail(), $dto->getPassword());
    }

}