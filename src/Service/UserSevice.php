<?php

namespace Service;

use DTO\UserCreateDTO;
use Model\User;
use Request\ProfileEditRequest;

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

    public function getUser(): User
    {
        return $this->userModel->userbyDB();
    }

    public function profileEdit(ProfileEditRequest $request, User $user)
    {
        $newName = $request->getName() ?? $user->getUserName();
        $newEmail = $request->getEmail() ?? $user->getUserEmail();
        $newPassword = $request->getPassword() ?? '';
        $nameChanged = ($newName !== $user->getUserName());
        $emailChanged = ($newEmail !== $user->getUserEmail());
        $passwordChanged = !empty($newPassword);

        if ($passwordChanged) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->userModel->UpdatePassword($newName, $newEmail, $hashedPassword);
        } else if ($nameChanged && $emailChanged) {
            $this->userModel->UpdateNameEmail($newName, $newEmail);
        } else if ($nameChanged) {
            $this->userModel->UpdateName($newName);
        } else if ($emailChanged) {
            $this->userModel->UpdateEmail($newEmail);
        }
    }

    public function countEmail($email)
    {
        return $this->userModel->countGetByEmail($email);

    }

    public function getUserByID(int $userId): User
    {
        return $this->userModel->UserbyID($userId);
    }
}