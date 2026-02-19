<?php

namespace Model;

use PDO;

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    public function getUserId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->name;
    }

    public function getUserEmail(): string
    {
        return $this->email;
    }

    public function getUserPassword(): string
    {
        return $this->password;
    }

    public function objUser(array $user) {
        $obj = new self();
        $obj->id = $user["id"];
        $obj->name = $user["name"];
        $obj->email = $user["email"];
        $obj->password = $user["password"];
        return $obj;
    }
    protected function getTableName(): string
    {
        return "users";
    }


    public function getbyEmail(string $email): User|null
    {
        $stms = $this->connection->prepare("SELECT * FROM {$this->getTableName()} WHERE email = :email");
        $stms->execute(['email' => $email]);
        $result = $stms->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }
        $obj = $this->objUser($result);
        return $obj;
    }

    public function count_getbyEmail(string $email)
    {
        $stms = $this->connection->prepare("SELECT * FROM {$this->getTableName()} WHERE email = :email");
        $stms->execute(['email' => $email]);
        $stms->rowCount();
        return $stms;
    }

    public function password_hash($password)
    {
        $stmt = $this->connection->prepare("INSERT INTO {$this->getTableName()} (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $_POST['name'], 'email' => $_POST['email'], 'password' => $password]);
        return $stmt;
    }


    public function UpdateByPassword($newName, $newEmail, $hashedPassword)  /// сделай опциональность для редактирования каждого парметра
    {
        $stmt = $this->connection->prepare("UPDATE {$this->getTableName()} SET name = :name, email = :email, password = :password WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'password' => $hashedPassword,
            'id' => $_SESSION['userid']
        ]);
    }

    public function UpdateByName_Email($newName, $newEmail)
    {
        $stmt = $this->connection->prepare("UPDATE {$this->getTableName()} SET name = :name, email = :email WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'id' => $_SESSION['userid']
        ]);
    }
    public function UserbyDB()
    {
        $stmt = $this->connection->prepare("SELECT id, name, email, password FROM {$this->getTableName()} WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['userid']]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        $obj = $this->objUser($user);
        return $obj;
    }
    public function UpdateName($newName)
    {
        $stmt = $this->connection->prepare("UPDATE {$this->getTableName()} SET name = :name WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'id' => $_SESSION['userid']
        ]);
    }

    public function UpdateEmail($newEmail)
    {
        $stmt = $this->connection->prepare("UPDATE {$this->getTableName()} SET email = :email WHERE id = :id");
        $stmt->execute([
            'email' => $newEmail,
            'id' => $_SESSION['userid']
        ]);
    }
}