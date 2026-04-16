<?php

declare(strict_types=1);

namespace Controllers;

use Request\LoginRequest;
use Request\ProfileEditRequest;
use Request\RegistrateRequest;
use DTO\UserCreateDTO;

class UserController extends Controller
{

    public function getRegistration(): void
    {
        if ($this->authService->getCurrentUser()) {
            header("Location: /catalog");
            exit;
        }

        require_once __DIR__ . '/../Views/registration.php';
    }

    public function getLogin(): void
    {
        if ($this->authService->getCurrentUser()) {
            header("Location: /catalog");
            exit;
        }

        require_once __DIR__ . '/../Views/login.php';
    }

    public function registration(RegistrateRequest $request): void
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $emailCount = $this->userService->countEmail($request->getEmail());

            if ($emailCount > 0) {
                $errors['email'] = 'Такой email уже существует';
            } else {
                $dto = new UserCreateDTO($request->getName(),
                    $request->getEmail(),
                    $request->getPassword());
                $this->userService->registrate($dto);
                header('Location: /catalog');
                exit();
            }
        }

        require_once __DIR__ . '/../Views/registration.php';
    }

    public function login(LoginRequest $request): void
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $user = $this->authService->auth($request->getEmail(), $request->getPassword());

            if ($user === false || $user === null) {
                $errors['PASSWORD'] = 'логин или пароль указаны неверно';
            } else {
                header("Location: /catalog");
                exit();
            }
        }

        require_once __DIR__ . '/../Views/login.php';
    }

    public function profile(): void
    {
        if ($this->authService->getCurrentUser()) {
            $user = $this->userService->getUser();

;           require_once __DIR__ . '/../Views/profile.php';
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function profileEdit(ProfileEditRequest $request): void
    {
        if ($this->authService->getCurrentUser()) {
            $user = $this->userService->getUser();

            if ($request->getMethod() === 'POST') {
                $this->userService->profileEdit($request->getName(), $request->getEmail(), $request->getPassword(), $user);
                header('Location: /profile');
                exit;
            } else if ($request->getMethod() === 'GET') {
                require_once __DIR__ . '/../Views/profile_edit.php';
            } else {
                header('Location: /login');
                exit;
            }
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}