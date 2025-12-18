<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri ==='/registration') {
    if ($requestMethod === 'POST') {   //помоему надо наоборот пост и гет написать
        require_once './registration_form.php';
    } elseif ($requestMethod === 'GET') {
        require_once './handle_login.php';
    }
    require_once './registration_form.php';
} elseif ($requestUri ==='/login') {
    require_once './login_page.php';
} elseif ($requestUri ==='/handle_login') {
    require_once './handle_login.php';
} else {
    echo '404 Not Found';  //добавить стиль и файлик для нее
}