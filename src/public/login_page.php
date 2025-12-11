<!DOCTYPE html>
<html lang="en">
#ДОБАВИТЬ ОТРАЖЕНИЕ ОШИБОК ERRORS
</head>
<body>
<!-- ИСПРАВЛЕНО: Правильный HTML вместо Pug/Jade синтаксиса -->
<div class="circle">
    <form action="handle_login.php" method="POST">
        <div class="content">
            <p>CodePen.io</p>
            <input type="text" placeholder="USERNAME" required>
            <input type="password" placeholder="PASSWORD" required>
        </div>
        <div class="login">
            <input type="submit" class="btn" value="LOGIN">
        </div>
    </form>
</div>
</body>
</html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* ИСПРАВЛЕНО: Добавлены фигурные скобки и точки с запятой */
        * {
            box-sizing: border-box;
            position: relative;
            font-family: 'Cabin', sans-serif;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: black;
            overflow-x: hidden;
            overflow-y: hidden;
        }

        p {
            font-size: 30px;
            letter-spacing: 1px;
            font-weight: 500;
            margin-bottom: 18px;
            color: white;
        }

        .circle {
            height: 300px;
            width: 300px;
            margin: 0 auto;
            margin-top: 15%;
            padding: 60px;
            padding-top: 23px;
            border-radius: 100%;
            background-color: black;
            box-shadow: 0px 0px 150px -5px white;
            -webkit-transition: box-shadow 1s;
            -moz-transition: box-shadow 1s;
            -o-transition: box-shadow 1s;
            transition: box-shadow 1s;
        }

        /* ИСПРАВЛЕНО: Правильный CSS синтаксис вместо & */
        .circle input {
            margin-bottom: 20px;
            border: solid 2px white;
            border-radius: 5px;
            padding: 5px;
            width: 100%;
            text-align: center;
            letter-spacing: 1px;
            background-color: black;
            color: white;
        }

        /* ИСПРАВЛЕНО: Правильный CSS синтаксис */
        .circle .content,
        .circle .login {
            text-align: center;
        }

        /* ИСПРАВЛЕНО: Правильный CSS синтаксис */
        .circle .btn {
            padding: 10px;
            letter-spacing: 2px;
            cursor: pointer;
            color: white;
            background-color: transparent;
            border: solid 2px white;
            border-radius: 5px;
            width: 100%;
        }

        /* ИСПРАВЛЕНО: Добавлены точка с запятой и скобка */
        .circle:hover {
            box-shadow: 0px 0px 150px -5px #82FF9E;
        }
    </style>
