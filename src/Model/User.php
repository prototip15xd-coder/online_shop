<?php

namespace Model;

class User extends Model
{

    public function getbyEmail($email)
    {
        $stms = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stms->execute(['email' => $email]);
        $result = $stms->fetch();

        return $result;
    }

    public function count_getbyEmail(string $email)
    {
        $stms = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stms->execute(['email' => $email]);
        $stms->rowCount();
        return $stms;
    }

    public function password_hash($password)
    {
        $stmt = $this->connection->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $_POST['name'], 'email' => $_POST['email'], 'password' => $password]);
        return $stmt;
    }


    public function UpdateByPassword($newName, $newEmail, $hashedPassword)  /// сделай опциональность для редактирования каждого парметра
    {
        $stmt = $this->connection->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'password' => $hashedPassword,
            'id' => $_SESSION['userid']
        ]);
    }

    public function UpdateByName_Email($newName, $newEmail)
    {
        $stmt = $this->connection->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'id' => $_SESSION['userid']
        ]);
    }
    public function UserbyDB()
    {
        $stmt = $this->connection->prepare("SELECT name, email, password FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['userid']]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user;
    }
    public function UpdateName($newName)
    {
        $stmt = $this->connection->prepare("UPDATE users SET name = :name WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'id' => $_SESSION['userid']
        ]);
    }

    public function UpdateEmail($newEmail)
    {
        $stmt = $this->connection->prepare("UPDATE users SET email = :email WHERE id = :id");
        $stmt->execute([
            'email' => $newEmail,
            'id' => $_SESSION['userid']
        ]);
    }
}