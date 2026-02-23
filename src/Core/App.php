<?php

namespace Core;

class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => \Controllers\UserController::class, //'UserController',
                'method' => 'getRegistration',
            ],
            'POST' => [
                'class' => \Controllers\UserController::class, //'UserController',
                'method' => 'registration',
            ]
        ],
        '/login' => [
            'GET' => [
                'class' => \Controllers\UserController::class,//'UserController',
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => \Controllers\UserController::class, //'UserController',
                'method' => 'login',
            ]
        ],
        '/catalog' => [
            'GET' => [
                'class' => \Controllers\ProductController::class,//'ProductController',
                'method' => 'catalog',
            ],
            'POST' => [
                'class' => \Controllers\ProductController::class,//'ProductController',
                'method' => 'add_product',
            ]
        ],
        '/profile' => [
            'GET' => [
                'class' => \Controllers\UserController::class,//'UserController',
                'method' => 'profile',
            ],
            'POST' => [
                'class' => \Controllers\UserController::class,//'UserController',
                'method' => 'profile',
            ]
        ],
        '/profile-edit' => [
            'GET' => [
                'class' => \Controllers\UserController::class,//'UserController',
                'method' => 'profileEdit',
            ],
            'POST' => [
                'class' => \Controllers\UserController::class,//'UserController',
                'method' => 'profileEdit',
            ]
        ],
        '/cart' => [
            'GET' => [
                'class' => \Controllers\CartController::class,//'CartController',
                'method' => 'cart',
            ]
        ],
        '/logout' => [
            'GET' => [
                'class' => \Controllers\UserController::class,//'UserController',
                'method' => 'logout',
            ]
        ],
        '/create-order' => [
            'GET' => [
                'class' => \Controllers\OrderController::class,
                'method' => 'getCheckoutForm',
            ],
            'POST' => [
                'class' => \Controllers\OrderController::class,
                'method' => 'handleCheckoutOrder',
            ]
        ],
        '/orders' => [
            'GET' => [
                'class' => \Controllers\OrderController::class,
                'method' => 'getAllOrders',
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

                $controller = new $class();
                $requestClass = $handler['request'];
                if ($requestClass !== null) {
                    $request = new $requestClass($_POST);
                    $controller->$method($request);
                } else {
                    $controller->$method();
                }
//                if ($method === 'GET') {
//                    $controller->$method();
//                } elseif ($method === 'POST') {
//                    $controller->$method($_POST);
//                }
                $controller->$method($_POST);
            } else {
                echo "$requestMethod не поддерживается для $requestUri";
            }
        } else {
            http_response_code(404);
            require_once '../Views/404.php';
        }
    }

    public function addRoute(string $route, string $routeMethod, string $className, string $classMethod){
        $this->routes[$route][$routeMethod] = [
                'class' => $className,
                'method' => $classMethod, ];
    }
    public function get(string $route, string $className, string $classMethod, string $request = null){
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $classMethod,
            'request' => $request, ];
    }
    public function post(string $route, string $className, string $classMethod, string $request = null){
        $this->routes[$route]['POST'] = [
            'class' => $className,
            'method' => $classMethod,
            'request' => $request ];
    }
    public function put(string $route, string $className, string $classMethod){
        $this->routes[$route]['PUT'] = [
            'class' => $className,
            'method' => $classMethod ];
    }
    public function delete(string $route, string $className, string $classMethod){
        $this->routes[$route]['DELETE'] = [
            'class' => $className,
            'method' => $classMethod ];
    }
}