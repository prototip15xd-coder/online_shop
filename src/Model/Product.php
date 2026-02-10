<?php

namespace Model;

class Product extends Model
{
     private int $id;
     private string $name;
     private string $description;
     private int $price;
     private string $image_url;
     private int $amount;
    public function getAmount(): int
    {
        return $this->amount ?? 0;
    }
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getId()
    {
        return $this->id;
    }


    public function getName()
    {
        return $this->name;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function getPrice()
    {
        return $this->price;
    }
    public function objProduct(array $product) {
        $obj = new self();
        $obj->id = $product["id"];
        $obj->name = $product["name"];
        $obj->description = $product["description"];
        $obj->price = $product["price"];
        $obj->image_url = $product["image_url"];
        return $obj;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }


    public function productByDB(): array | null
    {
        $stms = $this->connection->query('SELECT * FROM products');
        $products_array = $stms->fetchAll();
        $products = [];
        foreach ($products_array as $product) {
            $obj = $this->objProduct($product);
            $products[] = $obj;
        }
        return $products;
    }
    public function productByproductId($productId): ?Product
    {
        $stms = $this->connection->prepare('SELECT * FROM products WHERE id = :id');
        $stms -> execute([':id' => $productId]);
        $product_array = $stms->fetch(\PDO::FETCH_ASSOC);
        if (!$product_array) {
            return null;
        }
        $obj = $this->objProduct($product_array);
        return $obj;
    }


    public function validate_product()
    {
        $stms = $this->connection->prepare("SELECT id FROM products WHERE id = :product_id");
        $stms->execute(['product_id' => $_POST['product_id']]);
        return $stms->rowCount();
    }
    public function add_productDB()
    {
        $stms = $this->connection->prepare("SELECT id FROM products WHERE id = :product_id");
        $stms->execute(['product_id' => $_POST['product_id']]);
        $stmt = $this->connection->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $user_p = $this->connection->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id ");
        $user_p->execute(['user_id' => $_SESSION['userid'], 'product_id' => $_POST['product_id']]);
        $existingRecord = $user_p->fetch(\PDO::FETCH_ASSOC);
        if ($existingRecord) {
            $stmt = $this->connection->prepare("UPDATE user_products SET amount = amount + :amount WHERE user_id = :user_id AND product_id = :product_id");
        }
        $amount = 1;
        $stmt->execute([
            'user_id' => $_SESSION['userid'],
            'product_id' => $_POST['product_id'],
            'amount' => $amount
        ]);
    }
    public function delete_productDB()
    {
        $stms = $this->connection->prepare("SELECT id FROM products WHERE id = :product_id");
        $stms->execute(['product_id' => $_POST['product_id']]);
        $stmt = $this->connection->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
        $user_p = $this->connection->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id ");
        $user_p->execute(['user_id' => $_SESSION['userid'], 'product_id' => $_POST['product_id']]);
        $existingRecord = $user_p->fetch(\PDO::FETCH_ASSOC);
        if ($existingRecord) {
            $stmt = $this->connection->prepare("UPDATE user_products SET amount = amount - :amount WHERE user_id = :user_id AND product_id = :product_id");
        }
        $amount = 1;
        $stmt->execute([
            'user_id' => $_SESSION['userid'],
            'product_id' => $_POST['product_id'],
            'amount' => $amount
        ]);
    }
}