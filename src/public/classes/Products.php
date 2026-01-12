<?php

class Products
{
    public function catalog() {
        if (isset($_SESSION['userid'])) {
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
            $stms = $pdo->query('SELECT * FROM products');
            $products = $stms->fetchAll();
            require_once '/var/www/html/src/public/catalog_page.php';
        } else {
            require_once '/var/www/html/src/public/login';

        }
    }

    public function add_product_validate($POST_DATA, $SESSION_DATA) {
        $errors = [];

        if (!isset($SESSION_DATA['userid'])) {
            require_once '/var/www/html/src/public/login';
        } else {
            $amount = $POST_DATA['amount'];
            if (empty($POST_DATA['product_id']) || empty($POST_DATA['amount'])) {
                $errors['product_id'] = 'Выберите товар и количество';
            } else {
                if (!is_numeric($amount) || $amount <= 0) {
                    $errors['amount'] = 'Количество товаров должно быть корректным числом';
                }
            }
        }
        return $errors;
    }

    public function add_product($POST_DATA, $SESSION_DATA) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $errors = $this-> add_product_validate($_POST, $_SESSION);
            if (empty($errors)) {
                $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stms = $pdo->prepare("SELECT id FROM products WHERE id = :product_id");
                $stms->execute(['product_id' => $_POST['product_id']]);
                if ($stms->rowCount() === 0) {
                    $errors['product_id'] = 'Такого товара не существует';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:user_id, :product_id, :amount)");
                    $user_p = $pdo->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id ");
                    $user_p->execute(['user_id' => $_SESSION['userid'], 'product_id' => $_POST['product_id']]);

                    $existingRecord = $user_p ->fetch(PDO::FETCH_ASSOC);

                    if ($existingRecord) {
                        $updateStmt = $pdo->prepare("UPDATE user_products SET amount = amount + :amount WHERE user_id = :user_id AND product_id = :product_id");
                        $updateStmt->execute([
                            'user_id' => $_SESSION['userid'],
                            'product_id' => $_POST['product_id'],
                            'amount' => $_POST['amount']
                        ]);
                    } else {
                        $stmt->execute(
                            ['user_id' => $_SESSION['userid'],
                                'product_id' => $_POST['product_id'],
                                'amount' => +$_POST['amount']]);
                    }

                }
            }

        }
        $errors = $errors ?? [];
        require_once '/var/www/html/src/public/add_product_page.php';
    }

}