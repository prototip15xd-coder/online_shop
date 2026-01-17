<?php
class User
{
    public function getbyEmail($email)
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');   //ЭТА ЧАСТЬ КОДА ПРОВЕРЯЕТ НАЛИЧИЕ ПОЛЬЗОВАТЕЛЯ АДАПТИРУЙ ДЛЯ ТОВАРА !!!! В ФАЙЛЕ С КОРЗИНОЙ
        $stms = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stms->execute(['email' => $email]);
        $result = $stms->fetch();

        return $result;
    }

    public function count_getbyEmail(string $email)
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');   //ЭТА ЧАСТЬ КОДА ПРОВЕРЯЕТ НАЛИЧИЕ ПОЛЬЗОВАТЕЛЯ АДАПТИРУЙ ДЛЯ ТОВАРА !!!! В ФАЙЛЕ С КОРЗИНОЙ
        $stms = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stms->execute(['email' => $email]);
        $stms->rowCount();
        return $stms;
    }

    public function password_hash($password)
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $_POST['name'], 'email' => $_POST['email'], 'password' => $password]);
        return $stmt;
    }


    public function UpdateByPassword($newName, $newEmail, $hashedPassword)  /// сделай опциональность для редактирования каждого парметра
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'password' => $hashedPassword,
            'id' => $_SESSION['userid']
        ]);
    }

    public function UpdateByName_Email($newName, $newEmail)
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'id' => $_SESSION['userid']
        ]);
    }
    public function UserbyDB()
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $stmt = $pdo->prepare("SELECT name, email, password FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['userid']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    public function UpdateName($newName)
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $stmt = $pdo->prepare("UPDATE users SET name = :name WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'id' => $_SESSION['userid']
        ]);
    }

    public function UpdateEmail($newEmail)
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");
        $stmt->execute([
            'email' => $newEmail,
            'id' => $_SESSION['userid']
        ]);
    }
}