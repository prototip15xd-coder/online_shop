<?php


$USERNAME = $_POST['USERNAME'];
$PASSWORD = $_POST['PASSWORD'];
$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
$stms = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stms->execute(['email' => $USERNAME]);
$user = $stms->fetch();

$errors = [];
if ($user === false) {
    $errors['USERNAME'] = 'username not found';
} else {
    $passworddb = $user['PASSWORD'];
    if (password_verify($PASSWORD, $passworddb)) {
        print_r($_POST);
        require_once './catalog.php';
    } $errors['PASSWORD'] = 'password или login указаны неверно';
}

require_once './login_page.php';