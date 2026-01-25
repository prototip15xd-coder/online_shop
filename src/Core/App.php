<?php

class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'getRegistration',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'registration',
            ]
        ],
        '/login' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'login',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'login',
            ]
        ],
        '/catalog' => [
            'GET' => [
                'class' => 'ProductController',
                'method' => 'catalog',
            ],
            'POST' => [
                'class' => 'ProductController',
                'method' => 'add_product',
            ]
        ],
        '/profile' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'profile',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'profile',
            ]
        ],
        '/profile-edit' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'profileEdit',
            ],
            'POST' => [
                'class' => 'UserController',
                'method' => 'profileEdit',
            ]
        ],
        '/cart' => [
            'GET' => [
                'class' => 'CartController',
                'method' => 'cart',
            ]
        ],
        '/logout' => [
            'GET' => [
                'class' => 'UserController',
                'method' => 'logout',
            ]
        ]

    ];

    public function Run()
    {
        session_start();

        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (isset($this->routes[$requestUri])) {   //если перестанет работать см урок разбор рефакторинга маршрутизации
            $routeMethod = $this->routes[$requestUri];
            if (isset($routeMethod[$requestMethod])) {
                $handler = $routeMethod[$requestMethod];

                $class = $handler['class'];
                $method = $handler['method'];

                require_once "../Controllers/{$class}.php";
                $controller = new $class();
                $controller->$method();
            } else {
                echo "$requestMethod не поддерживается для $requestUri";
            }
        } else {
            http_response_code(404);
            require_once '../Views/404.php';
        }
    }
}