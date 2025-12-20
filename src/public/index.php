<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri ==='/registration') {
    if ($requestMethod === 'GET') {   //помоему надо наоборот пост и гет написать
        require_once './registration_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './handle_login.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }

    require_once './registration_form.php';
} elseif ($requestUri ==='/login') {
    require_once './login_page.php';
} elseif ($requestUri ==='/handle_login') {
    require_once './handle_login.php';
} else {
    http_response_code(404);
    require_once './404.php';
}