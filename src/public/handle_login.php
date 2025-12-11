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
        header("Location: /src/public/catalog.php");
    } else {
        $errors['PASSWORD'] = 'password указаны неверно';
    }
}

require_once './login_page.php';