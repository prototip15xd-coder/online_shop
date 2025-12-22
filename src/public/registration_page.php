<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function act_page(array $POST_DATA)
    {
        $errors = [];
        if (isset($POST_DATA['name'])) {
            $name = $POST_DATA['name'];
            if (strlen($name) < 4) {
                $errors['name'] = 'Имя должно быть длинее 4 символов';
            }
        } else {
            $errors['name'] = 'Имя должно быть заполнено';
        }

        if (isset($POST_DATA['email'])) {
            $email = $POST_DATA['email'];
            if (strpos($email, '@') === false) {
                $errors['email'] = 'email должен содержать знак @';
            }
        } else {
            $errors['email'] = 'email должен быть заполнен';
        }

        if (isset($POST_DATA['psw'])) {
            $password = $POST_DATA['psw'];
            $psw_repeat = $POST_DATA['psw-repeat'];
            if ($password !== $psw_repeat) {
                $errors['psw-repeat'] = "Пароли не совпадают\n";
            }
        } else {
            $errors['password'] = 'пароли должны быть заполнены';
        }
        return $errors;
    }

    $errors = act_page($_POST);
    if (empty($errors)) {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $chekemail = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $chekemail->execute(['email' => $_POST['email']]);

        if ($chekemail->rowCount() > 0) {
            $errors['email'] = 'Такой email уже существует';
        } else {

            $password = password_hash($_POST['psw'], PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute(['name' => $_POST['name'], 'email' => $_POST['email'], 'password' => $password]);
            $last = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $last->execute(['email' => $_POST['email']]);

            $data = $last->fetch();

        }
    }
}
$errors = $errors ?? [];
require_once './registration_form.php'

?>


#сделать сохр пользователя в бд
# проверка данных!
#вывести пользователя которого только что сохранили
