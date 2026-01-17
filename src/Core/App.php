<?php

class App
{
    private array $routes =[
        '/registration' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getRegistration',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'Registration',
            ]
        ],
        '/login' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'login',
            ]
        ],
        '/catalog' => [
            'GET' => [
                'class' => 'CatalogController',
                'method' => 'catalog',
            ]
        ],
        '/profile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'profile',
            ],
            'POST' => [] //СДЕЛАЙ ЗДЕСЬ КАК РАЗ РЕДАКЦИЮ ПРОФИЛЯ!!!
        ],
        '/add_product' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'add_product',
            ]
        ],

    ];

    public function Run()
    {
        session_start();

        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];


        if ($requestUri === '/registration') {
            require_once '../Controllers/UserController.php';
            $user = new UserController();
            if ($requestMethod === 'GET') {
                $user->getRegistrate();
            } elseif ($requestMethod === 'POST') {
                $user->registration();
            } else {
                echo "$requestMethod для адреса $requestUri не поддерживается";
            }
        } elseif ($requestUri === '/login') {
            require_once '../Controllers/UserController.php';
            $user = new UserController();
            $user->login($_POST);
        } elseif ($requestUri === '/add_product') {
            require_once '../Controllers/ProductController.php';
            $product = new ProductController();
            $product->add_product($_POST, $_SESSION);
        } elseif ($requestUri === '/catalog') {
            require_once '../Controllers/ProductController.php';
            $product = new ProductController();
            $product->catalog();
        } elseif ($requestUri === '/profile') {

            require_once '../Controllers/UserController.php';
            $user = new UserController();
            if ($requestMethod === 'GET') {
                $user->getProfile();
            } elseif ($requestMethod === 'POST') {
                $user->editProfile();
            } else {
                echo "$requestMethod для адреса $requestUri не поддерживается";
            }


            echo $requestMethod;
            require_once '../Controllers/UserController.php';
            $user = new UserController();
            $user->profile();
        } elseif ($requestUri === '/profile?edit=true') {         ////
            echo $requestMethod;
            require_once '../Controllers/UserController.php';
            $user = new UserController();
            $isEditing = isset($_GET['edit']); // ЧТО ЭТО???
            echo $isEditing;
            $user->profile();
        } elseif ($requestUri === '/cart') {                      ////
            require_once '../Controllers/CartController.php';
            $user = new CartController();
            $user->cart();
        } else {
            echo("$requestUri");
            http_response_code(404);
            require_once '../404.php';
        }
    }
}