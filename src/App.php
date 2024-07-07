<?php

class App
{
    // Ассоциативный массив для маршрутов и методов HTTP
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => \Controller\UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => \Controller\UserController::class,
                'method' => 'registrate',
            ]
        ],
        '/login' => [
            'GET' => [
                'class' => \Controller\UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => \Controller\UserController::class,
                'method' => 'login',
            ]
        ],
        '/my_profile' => [
            'GET' => [
                'class' => \Controller\UserController::class,
                'method' => 'showProfile',
            ]
        ],
        '/catalog' => [
            'GET' => [
                'class' => \Controller\ProductController::class,
                'method' => 'showCatalog',
            ]
        ],
        '/add-product' => [
            'GET' => [
                'class' => \Controller\CartController::class,
                'method' => 'getAddProductForm',
            ],
            'POST' => [
                'class' => \Controller\CartController::class,
                'method' => 'addProduct',
            ]
        ],
        '/remove-product' => [
            'POST' => [
                'class' => \Controller\CartController::class,
                'method' => 'removeProduct',
            ]
        ],
        '/cart' => [
            'GET' => [
                'class' => \Controller\CartController::class,
                'method' => 'showCart',
            ]
        ],
        '/logout' => [
            'GET' => [
                'class' => \Controller\UserController::class,
                'method' => 'logout',
            ]
        ]
    ];

    // Метод для обработки входящих запросов
    public function handle()
    {
        // Получаем URI запроса и метод HTTP
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Проверка наличия маршрута в массиве
        if (isset($this->routes[$requestUri][$requestMethod])) {
            $route = $this->routes[$requestUri][$requestMethod];
            $class = $route['class'];
            $method = $route['method'];

            // Создаем экземпляр класса и вызываем метод
            $controller = new $class();
            $controller->$method();
        } else {
            // Если маршрут не найден, возвращаем код ошибки 404 и подключаем страницу ошибки
            http_response_code(404);
            require_once 'View/404.php';
        }
    }
}
