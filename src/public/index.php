<?php
session_start();

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];



if ($requestUri ==='/registration') {
    require_once './classes/User.php';
    $user = new User();
    if ($requestMethod === 'GET') {
        $user->getRegistrate();
    } elseif ($requestMethod === 'POST') {
        $user->registration();
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri ==='/login') {
    require_once './classes/User.php';
    $user = new User();
    $user->login($_POST);
} elseif ($requestUri === '/add_product') {
    require_once './classes/Products.php';
    $product = new Products();
    $product -> add_product($_POST, $_SESSION);
} elseif ($requestUri === '/catalog') {
    require_once './classes/Products.php';
    $product = new Products();
    $product -> catalog();
} elseif ($requestUri === '/profile') {
    require_once './classes/User.php';
    $user = new User();
    $user->profile();
} elseif ($requestUri === '/profile?edit=true') {
    //////????????????????????????????????????????
} elseif ($requestUri === '/cart') {
    require_once './classes/User.php';
    $user = new User();
    $user -> cart();
} else {
    echo ("$requestUri");
    http_response_code(404);
    require_once './404.php';
}

// УБЕРИ ДВОЙНИКИ