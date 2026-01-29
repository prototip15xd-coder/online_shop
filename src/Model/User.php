<?php

namespace Model;

class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
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



    public function getbyEmail(string $email): User|null
    {
        $stms = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stms->execute(['email' => $email]);
        $result = $stms->fetch();
        if ($result === false) {
            return null;
        }
        $obj = $self->objUser($result);
        return $obj;
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
        $obj = $self->objUser($user);
        return $obj;
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