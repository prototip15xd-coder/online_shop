<?php

class User
{
    public function getRegistrate()
    {
        if (isset($_SESSION['userid'])) {
            header("Location: ../catalog.php");
        }
        require_once '../registration_form.php';   ///???
    }
//    public function getLogin()
//    {
//        session_start();
//        if (isset($_SESSION['userid'])) {
//            header("Location: ./catalog.php");
//        }
//        require_once './login_page.php';  ///???
//    }
    function registration()
    {
        //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validate($_POST);
            if (empty($errors)) {
                $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $chekemail = $pdo->prepare("SELECT email FROM users WHERE email = :email");
                $chekemail->execute(['email' => $_POST['email']]);

                if ($chekemail->rowCount() > 0) {
                    $errors['email'] = 'Такой email уже существует';
                } else {

                    $password = password_hash($_POST['psw'], PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
                    $stmt->execute(['name' => $_POST['name'], 'email' => $_POST['email'], 'password' => $password]);
                    $last = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                    $last->execute(['email' => $_POST['email']]);

                    $data = $last->fetch();

                }
            }

        $errors = $errors ?? [];
        require_once '../registration_form.php';
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

    public function login(array $POST_DATA) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errors = [];
            if (empty($POST_DATA['login']) || empty($POST_DATA['password'])) {
                $errors['USERNAME'] = 'Все поля должны быть заполнены';
            } else {
                $USERNAME = $POST_DATA['login'];
                $PASSWORD = $POST_DATA['password'];
                $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');   //ЭТА ЧАСТЬ КОДА ПРОВЕРЯЕТ НАЛИЧИЕ ПОЛЬЗОВАТЕЛЯ АДАПТИРУЙ ДЛЯ ТОВАРА !!!! В ФАЙЛЕ С КОРЗИНОЙ
                $stms = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                $stms->execute(['email' => $USERNAME]);
                $user = $stms->fetch();

                if ($user === false) {
                    $errors['PASSWORD'] = 'логин или пароль указаны неверно';
                } else {
                    $passworddb = $user['password'];
                    if (password_verify($PASSWORD, $passworddb)) {
                        $_SESSION['userid'] = $user['id'];
                        header("Location: /catalog");
                    } else {
                        $errors['PASSWORD'] = 'логин или пароль указаны неверно';
                    }
                }


            }
        }
        require_once '../login_page.php';
    }

    public function profile() {
        if (isset($_SESSION['userid'])) {
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newName = $_POST['name'];
                $newEmail = $_POST['email'];
                $newPassword = $_POST['password'];

                if (!empty($newPassword)) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
                    $stmt->execute([
                            'name' => $newName,
                            'email' => $newEmail,
                            'password' => $hashedPassword,
                            'id' => $_SESSION['userid']
                    ]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
                    $stmt->execute([
                            'name' => $newName,
                            'email' => $newEmail,
                            'id' => $_SESSION['userid']
                    ]);
                }
                header("Location: ../profile.php");
            }

            $stmt = $pdo->prepare("SELECT name, email, password FROM users WHERE id = :id");
            $stmt->execute(['id' => $_SESSION['userid']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $isEditing = isset($_GET['edit']);
            require_once '../profile_page.php';
        } else {
            header("Location: /src/public/login_page.php");

        }
    }

    public function cart() {

        if (isset($_SESSION['userid'])) {
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
            $us_id = $_SESSION['userid'];

            $stms = $pdo->prepare("SELECT products.name,
               products.description,
               products.price,
               products.image_url,
               user_products.amount 
               FROM user_products 
               JOIN products ON user_products.product_id = products.id
               WHERE user_products.user_id = :user_id");
            $stms ->execute(['user_id'=> $us_id]);
            $all_products = $stms->fetchAll(PDO::FETCH_ASSOC);
            require_once '../cart_page.php';
        } else {
            header("Location: ../login");
        }

    }
}

?>

