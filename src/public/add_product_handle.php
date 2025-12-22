<?php
// проверка пользователя , валидания -> сохр в базу данных
// добавляем товары через сумму! а не каждый отдельной строкой
echo "Метод запроса: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "URL: " . $_SERVER['REQUEST_URI'] . "<br>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = new PDO('mysql:host=localhost;dbname=catalog', 'root', '');

}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function add_prod($POST_DATA)
    {
        $errors = [];
        if (empty($POST_DATA['product_id']) || empty($POST_DATA['amount'])) {
            $errors['USERNAME'] = 'Выберите товар и количество';
        } else {
            $product_Id = $POST_DATA['product_id'];
            $amount = $POST_DATA['amount'];

            ??????????
            $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
            $stms = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stms->execute(['email' => $USERNAME]);
            $user = $stms->fetch();

            if ($user === false) {
                $errors['PASSWORD'] = 'логин или пароль указаны неверно';
            } else {
                $passworddb = $user['password'];
                if (password_verify($PASSWORD, $passworddb)) {
                    session_start();
                    $_SESSION['userid'] = $user['id'];
                    header("Location: /catalog");
                } else {
                    $errors['PASSWORD'] = 'логин или пароль указаны неверно';
                }
            }
        }
        return $errors;
    }

    $errors = logIN($_POST);
}


require_once './login_page.php';






