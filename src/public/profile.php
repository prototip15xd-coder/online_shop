<?php

session_start();

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
        header("Location: profile.php");
    }


    $stmt = $pdo->prepare("SELECT name, email, password FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['userid']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    require_once './profile_page.php';
} else {
    header("Location: /src/public/login_page.php");

}
?>

