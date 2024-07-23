<?php

use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;

class App
{
    // Ассоциативный массив для маршрутов и методов HTTP
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'registrate',
            ]
        ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'login',
            ]
        ],
        '/my_profile' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'showProfile',
            ]
        ],
        '/catalog' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'showCatalog',
            ]
        ],
        '/add-product' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'getAddProductForm',
            ],
            'POST' => [
                'class' => CartController::class,
                'method' => 'addProduct',
            ]
        ],
        '/remove-product' => [
            'POST' => [
                'class' => CartController::class,
                'method' => 'removeProduct',
            ]
        ],
        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'showCart',
            ]
        ],
        '/logout' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'logout',
            ]
        ]
    ];

    // Метод для обработки входящих запросов
    public function handle()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $route = $this->getRoute($requestUri, $requestMethod);

        if ($route) {
            $class = $route['class'];
            $method = $route['method'];

            $controller = new $class();
            $controller->$method();
        } else {
            http_response_code(404);
            require_once 'View/404.php';
        }
    }
// Поиск маршрута вроде как облегчает поддержку
    private function getRoute(string $uri, string $method): ?array
    {
        return $this->routes[$uri][$method] ?? null;
    }
}