<?php
function logIN($POST_DATA)
{
    $errors = [];
    if (empty($POST_DATA['login']) || empty($POST_DATA['password'])) {
        $errors['USERNAME'] = 'Все поля должны быть заполнены';
    } else {
        $USERNAME = $POST_DATA['login'];
        $PASSWORD = $POST_DATA['password'];
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $stms = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stms->execute(['email' => $USERNAME]);
        $user = $stms->fetch();

        if ($user === false) {
            $errors['PASSWORD'] = 'логин или пароль указаны неверно';
        } else {
            $passworddb = $user['password'];
            if (password_verify($PASSWORD, $passworddb)) {
                session_start();
                $_SESSION['userid'] = $user['id'];
                header("Location: /src/public/catalog.php");
            } else {
                $errors['PASSWORD'] = 'логин или пароль указаны неверно';
            }
        }
    }
    return $errors;
}
$errors = logIN($_POST);

require_once './login_page.php';






