<?php



class UserController
{
    public function getRegistration()
    {
        if (isset($_SESSION['userid'])) {
            header("Location: /catalog");   //// вызов класса!!!??
            exit;
        }
        require_once '/var/www/html/src/Views/registration.php';
    }

    function registration()
    {
        $errors = $this->validate($_POST);
        if (empty($errors)) {
            require_once '../Model/User.php';
            $userModel = new User();
            $chekemail = $userModel->count_getbyEmail($_POST['email']);
            if ($chekemail > 0) {
                $errors['email'] = 'Такой email уже существует';
            } else {
                $password = password_hash($_POST['psw'], PASSWORD_DEFAULT);
                $userModel-> password_hash($password);

                $user = $userModel->getByEmail($_POST['email']);
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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errors = [];
            if (empty($_POST['login']) || empty($_POST['password'])) {
                $errors['USERNAME'] = 'Все поля должны быть заполнены';
            } else {
                $email = $_POST['login'];
                $PASSWORD = $_POST['password'];

                require_once '../Model/User.php';
                $userModel = new User();
                $user = $userModel->getByEmail($email);

                if ($user === false) {
                    $errors['PASSWORD'] = 'логин или пароль указаны неверно';
                } else {
                    $passworddb = $user['password'];
                    if (password_verify($PASSWORD, $passworddb)) {
                        $_SESSION['userid'] = $user['id'];
                        require_once '/var/www/html/src/Controllers/ProductController.php';
                        $products = new ProductController();
                        $products->catalog();
                        //require_once '/var/www/html/src/Views/catalog.php';     // вызываем класс/функцию
                    } else {
                        $errors['PASSWORD'] = 'логин или пароль указаны неверно';
                    }
                }
            }
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }

    public function profile()
    {
        if (isset($_SESSION['userid'])) {
            require_once '../Model/User.php';
            $userModel = new User();
            $user = $userModel->UserbyDB();
            require_once '/var/www/html/src/Views/profile.php';
        } else {
            require_once '/var/www/html/src/Views/login.php';
        }
    }
    public function profileEdit() {
        if (isset($_SESSION['userid'])) {
            require_once '../Model/User.php';
            $userModel = new User();
            $user = $userModel->UserbyDB();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newName = $_POST['name'] ?? $user['name'];
                $newEmail = $_POST['email'] ?? $user['email'];
                $newPassword = $_POST['password'] ?? '';

                $nameChanged = ($newName !== $user['name']);
                $emailChanged = ($newEmail !== $user['email']);
                $passwordChanged = !empty($newPassword);

                if ($passwordChanged) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $userModel->UpdateByPassword($newName, $newEmail, $hashedPassword);
                } else if ($nameChanged && $emailChanged) {
                    $userModel->UpdateByName_Email($newName, $newEmail);
                } else if ($nameChanged) {
                    $userModel->UpdateName($newName);
                } else if ($emailChanged) {
                    $userModel->UpdateEmail($newEmail);
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
