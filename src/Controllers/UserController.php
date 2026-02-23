<?php

namespace Controllers;


use Request\LoginRequest;
use Request\ProfileEditRequest;
use Request\RegistrateRequest;

class UserController extends BaseController
{

    public function __construct() {
        parent::__construct();
    }
    public function getRegistration()
    {
        if ($this->authService->getCurrentUser()) {
            header("Location: /catalog");
            exit;
        }
        require_once '/var/www/html/src/Views/registration.php';
    }
    public function getLogin()
    {
        if ($this->authService->getCurrentUser()) {
            header("Location: /catalog");
            exit;
        }
        require_once '/var/www/html/src/Views/login.php';
    }

    function registration(RegistrateRequest $request)
    {
        $errors = $request->validate();
        if (empty($errors)) {
            $chekemail = $this->userModel->count_getbyEmail($request->getEmail());
            if ($chekemail > 0) {
                $errors['email'] = 'Такой email уже существует';
            } else {
                $password = password_hash($request->getPassword(), PASSWORD_DEFAULT); //['psw']
                $this->userModel-> password_hash($password);
                $user = $this->userModel->getByEmail($request->getEmail());

                header("Location: /catalog");
                exit;
            }
        }
        $errors = $errors ?? [];
        require_once '/var/www/html/src/Views/registration.php';
    }

    public function login(LoginRequest $request)
    {
        $errors = $request->validate();
        $user = $this->authService->auth($request->getEmail(), $request->getPassword());
        if ($user === false or $user == null) {
            $errors['PASSWORD'] = 'логин или пароль указаны неверно';
        } else {
            header("Location: /catalog");
            exit();
        }
        require_once '/var/www/html/src/Views/login.php';
    }

    public function profile()
    {
        if ($this->authService->getCurrentUser()) {
            $user = $this->userModel->UserbyDB();
            require_once '/var/www/html/src/Views/profile.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }
    public function profileEdit(ProfileEditRequest $request) {
        if ($this->authService->getCurrentUser()) {
            $user = $this->userModel->UserbyDB();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newName = $request->getName() ?? $user->getUserName();
                $newEmail = $request->getEmail() ?? $user->getUserEmail();
                $newPassword = $request->getPassword() ?? '';

                $nameChanged = ($newName !== $user->getUserName());
                $emailChanged = ($newEmail !== $user->getUserEmail());
                $passwordChanged = !empty($newPassword);

                if ($passwordChanged) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $this->userModel->UpdateByPassword($newName, $newEmail, $hashedPassword);
                } else if ($nameChanged && $emailChanged) {
                    $this->userModel->UpdateByName_Email($newName, $newEmail);
                } else if ($nameChanged) {
                    $this->userModel->UpdateName($newName);
                } else if ($emailChanged) {
                    $this->userModel->UpdateEmail($newEmail);
                }
                header('Location: /profile');
                exit;
            } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                require_once '/var/www/html/src/Views/profile_edit.php';
            } else {
                header('Location: /login');
                exit;
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
    }
}

?>
