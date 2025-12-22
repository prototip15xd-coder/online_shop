<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri ==='/registration') {
    if ($requestMethod === 'GET') {
        require_once './registration_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './registration_page.php';
    } else {
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
} elseif ($requestUri ==='/login') {
        require_once './login_handle.php';
} elseif ($requestUri === '/add_product') {
    if ($requestMethod === 'GET') {
        require_once './add_product_page.php';
    }
} elseif ($requestUri === '/catalog') {
    require_once './catalog.php';
} elseif ($requestUri === '/profile') {
    require_once './profile.php';
} else {
    echo ("$requestUri");
    http_response_code(404);
    require_once './404.php';
}