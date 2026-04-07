<?php

declare(strict_types=1);

namespace Controllers;

use Request\LoginRequest;
use Request\ProfileEditRequest;
use Request\RegistrateRequest;
use DTO\UserCreateDTO;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

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

    function registration(RegistrateRequest $request): void
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $chekemail = $this->userService->countEmail($request->getEmail());

            if ($chekemail > 0) {
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

        $errors = $errors ?? [];
        require_once __DIR__ . '/../Views/registration.php';
    }

    public function login(LoginRequest $request): void
    {
        $errors = $request->validate();
        $user = $this->authService->auth($request->getEmail(), $request->getPassword());

        if ($user === false or $user == null) {
            $errors['PASSWORD'] = 'логин или пароль указаны неверно';
        } else {
            header("Location: /catalog");
            exit();
        }

        require_once __DIR__ . '/../Views/login.php';
    }

    public function profile(): void
    {
        if ($this->authService->getCurrentUser()) {
            $user = $this->userService->getUser();
;            require_once __DIR__ . '/../Views/profile.php';
        } else {
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function profileEdit(ProfileEditRequest $request): void
    {
        if ($this->authService->getCurrentUser()) {
            $user = $this->userService->getUser();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->userService->profileEdit($request, $user);
                header('Location: /profile');
                exit;
            } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                require_once __DIR__ . '/../Views/profile_edit.php';
            } else {
                header('Location: /login');
                exit;
            }
        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
    }
}