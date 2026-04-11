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

    public function objUser(array $user): self
    {
        $obj = new self();
        $obj->id = $user["id"];
        $obj->name = $user["name"];
        $obj->email = $user["email"];
        $obj->password = $user["password"];
        return $obj;
    }

    protected static function getTableName(): string
    {
        return "users";
    }

    public function getbyEmail(string $email): User|null
    {
        $stms = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE email = :email");
        $stms->execute(['email' => $email]);
        $result = $stms->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }
        $obj = $this->objUser($result);
        return $obj;
    }

    public function countGetByEmail(string $email): ?int
    {
        $stms = static::getPDO()->prepare("SELECT * FROM {$this->getTableName()} WHERE email = :email");
        $stms->execute(['email' => $email]);
        $stms->rowCount();
        return $stms;
    }

    public function registrate(string $name, string $email, string $password): void
    {
        $stmt = static::getPDO()->prepare(
            "INSERT INTO {$this->getTableName()} (name, email, password) 
            VALUES (:name, :email, :password)"
        );
        $password = password_hash($password, PASSWORD_DEFAULT); //['psw']
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
        //return $stmt; разве он должен что-то возвращать?
    }

    public function UpdatePassword(string $newName, string $newEmail, string $hashedPassword): void
    {
        $stmt = static::getPDO()->prepare(
            "UPDATE {$this->getTableName()} 
            SET name = :name, email = :email, password = :password 
            WHERE id = :id"
        );
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'password' => $hashedPassword,
            'id' => $_SESSION['userid']
        ]);
    }

    public function UpdateNameEmail(string $newName, string $newEmail): void
    {
        $stmt = static::getPDO()->prepare(
            "UPDATE {$this->getTableName()} 
            SET name = :name, email = :email 
            WHERE id = :id"
        );
        $stmt->execute([
            'name' => $newName,
            'email' => $newEmail,
            'id' => $_SESSION['userid']
        ]);
    }

    public function userbyDB(): User
    {
        $stmt = static::getPDO()->prepare(
            "SELECT id, name, email, password 
            FROM {$this->getTableName()} 
            WHERE id = :id"
        );
        $stmt->execute(['id' => $_SESSION['userid']]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        $obj = $this->objUser($user);
        return $obj;
    }

    public function UserbyId(int $userId): User
    {
        $stmt = static::getPDO()->prepare(
            "SELECT id, name, email, password 
                FROM {$this->getTableName()} 
                WHERE id = :id"
        );
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        $obj = $this->objUser($user);
        return $obj;
    }

    public function UpdateName(string $newName): void
    {
        $stmt = static::getPDO()->prepare("UPDATE {$this->getTableName()} SET name = :name WHERE id = :id");
        $stmt->execute([
            'name' => $newName,
            'id' => $_SESSION['userid']
        ]);
    }

    public function UpdateEmail(string $newEmail): void
    {
        $stmt = static::getPDO()->prepare("UPDATE {$this->getTableName()} SET email = :email WHERE id = :id");
        $stmt->execute([
            'email' => $newEmail,
            'id' => $_SESSION['userid']
        ]);
    }
}