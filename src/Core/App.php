<?php

namespace Core;
use Service\LoggerService;
use Service\LoggerDBService;

class App
{
    private array $routes = [];
    private LoggerDBService $logger;
    public function __construct(LoggerDBService $logger)
    {
        $this->logger = $logger;
    }

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

                $queryParams = [];
                $queryString = parse_url($requestUri, PHP_URL_QUERY);

                if ($queryString) {
                    parse_str($queryString, $queryParams);
                }
                try {
                    if ($requestClass !== null) {
                        if ($requestMethod === 'GET') {
                            $request = new $requestClass($queryParams);
                        } elseif ($requestMethod === 'POST') {
                            $request = new $requestClass($_POST);
                        }
                        $controller->$method($request);
                    } else {
                        $controller->$method();
                    }
                } catch (\Throwable $exception) {
                        $this->logger->error($exception);
                        http_response_code(500);
                        require_once '../Views/500.php';
                        exit;
                }

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

    public function get(string $route, string $className, string $classMethod, ?string $request = null){
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $classMethod,
            'request' => $request ];
    }

    public function post(string $route, string $className, string $classMethod, ?string $request = null){
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