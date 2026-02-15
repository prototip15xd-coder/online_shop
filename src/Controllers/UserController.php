<?php

namespace Controllers;

use Model\Product;
use Model\User;
use Controllers\ProductController;

class UserController extends BaseController
{
    private ProductController $productController;

    public function __construct() {
        parent::__construct();
        $this->productController = new ProductController();
    }
    public function getRegistration()
    {
        if (isset($_SESSION['userid'])) {
            header("Location: /catalog");
            exit;
        }
        require_once '/var/www/html/src/Views/registration.php';
    }
    public function getLogin()
    {
        if (isset($_SESSION['userid'])) {
            header("Location: /catalog");
            exit;
        }
        require_once '/var/www/html/src/Views/login.php';
    }

    function registration()
    {
        $errors = $this->validate($_POST);
        if (empty($errors)) {
            $chekemail = $this->userModel->count_getbyEmail($_POST['email']);
            if ($chekemail > 0) {
                $errors['email'] = 'Такой email уже существует';
            } else {
                $password = password_hash($_POST['psw'], PASSWORD_DEFAULT);
                $this->userModel-> password_hash($password);

                $user = $this->userModel->getByEmail($_POST['email']);

                header("Location: /catalog");
                exit;
            }
        }

        $errors = $errors ?? [];
        require_once '/var/www/html/src/Views/registration.php';
    }

    private function validate(array $POST_DATA)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            if (isset($POST_DATA['name'])) {
                $name = $POST_DATA['name'];
                if (strlen($name) < 4) {
                    $errors['name'] = 'Имя должно быть длинее 4 символов';
                }
            } else {
                $errors['name'] = 'Имя должно быть заполнено';
            }

            if (isset($POST_DATA['email'])) {
                $email = $POST_DATA['email'];
                if (strpos($email, '@') === false) {
                    $errors['email'] = 'email должен содержать знак @';
                }
            } else {
                $errors['email'] = 'email должен быть заполнен';
            }

            if (isset($POST_DATA['psw'])) {
                $password = $POST_DATA['psw'];
                $psw_repeat = $POST_DATA['psw-repeat'];
                if ($password !== $psw_repeat) {
                    $errors['psw-repeat'] = "Пароли не совпадают\n";
                }
            } else {
                $errors['password'] = 'пароли должны быть заполнены';
            }
            return $errors;
        }
    }

    public function login()
    {
        $errors = [];
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $errors['USERNAME'] = 'Все поля должны быть заполнены';
        } else {
            $email = $_POST['email'];
            $PASSWORD = $_POST['password'];

            $user = $this->auth($email, $PASSWORD);
            if ($user === false or $user == null) {
                $errors['PASSWORD'] = 'логин или пароль указаны неверно';
            } else {
                header("Location: /catalog");
                exit();
            }
            require_once '/var/www/html/src/Views/login.php';
        }
    }

    public function profile()
    {
        if (isset($_SESSION['userid'])) {
            $user = $this->userModel->UserbyDB();
            require_once '/var/www/html/src/Views/profile.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }
    public function profileEdit() {
        if (isset($_SESSION['userid'])) {
            $user = $this->userModel->UserbyDB();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newName = $_POST['name'] ?? $user->getName();
                $newEmail = $_POST['email'] ?? $user->getEmail();
                $newPassword = $_POST['password'] ?? '';

                $nameChanged = ($newName !== $user->getName());
                $emailChanged = ($newEmail !== $user->getEmail());
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
