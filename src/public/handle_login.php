<?php
$USERNAME = $_POST['login'];
$PASSWORD = $_POST['password'];
$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
$stms = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stms->execute(['email' => $USERNAME]);
$user = $stms->fetch();

$errors = [];
if ($user === false) {
    $errors['USERNAME'] = 'login указаны неверно';
} else {
    $passworddb = $user['password'];
    if (password_verify($PASSWORD, $passworddb)) {
        session_start();
        $_SESSION['userid'] = $user['userid'];
        #setcookie("userid", $user['id']);

        header("Location: /catalog.php");
    } else {
        $errors['PASSWORD'] = 'password указаны неверно';
    }
}

require_once './login_page.php';