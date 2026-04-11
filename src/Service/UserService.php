<?php

namespace Service;

use DTO\UserCreateDTO;
use Model\User;
use Request\ProfileEditRequest;

class UserService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registrate(UserCreateDTO $dto): void
    {
        $this->userModel->registrate($dto->getUserName(), $dto->getUserEmail(), $dto->getPassword());
    }

    public function getUser(): User
    {
        return $this->userModel->userbyDB();
    }

    public function profileEdit(?string $name, ?string $email, ?string $password, User $user): void
    {
        $newName = !empty($name) ? $user->getUserName();
        $newEmail = $email ?? $user->getUserEmail();
        $newPassword = $password ?? $user->getUserPassword();
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

    public function countEmail(string $email): int
    {
        return $this->userModel->countGetByEmail($email);

    }

    public function getUserById(int $userId): User
    {
        return $this->userModel->UserbyId($userId);
    }
}