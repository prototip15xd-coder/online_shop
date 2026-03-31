<?php

namespace Service;

use DTO\UserCreateDTO;
use Model\User;

class UserSevice
{
    protected User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
    public function registrate(UserCreateDTO $dto)
    {
        $this->userModel->registrate($dto->getUserName(), $dto->getUserEmail(), $dto->getPassword());
    }

}