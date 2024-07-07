<?php

use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;

// Получаем URI запроса и метод HTTP
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Функция автозагрузки классов
spl_autoload_register(function ($class) {
    $prefixes = [
        'Controller\\' => __DIR__ . '/../Controller/',
        'Model\\' => __DIR__ . '/../Model/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        } else {
            echo "File not found: $file<br>";
        }
    }
});

// Обработка маршрута для регистрации
if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        require_once '../View/get_registration.php';
    } elseif ($requestMethod === 'POST') {
        $userController = new UserController();
        $userController->registrate();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для входа в систему
elseif ($requestUri === '/login') {
    if ($requestMethod === 'GET') {
        require_once '../View/get_login.php';
    } elseif ($requestMethod === 'POST') {
        $userController = new UserController();
        $userController->login();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для профиля пользователя
elseif ($requestUri === '/my_profile') {
    $userController = new UserController();
    $userController->showProfile();
}
// Обработка маршрута для каталога продуктов
elseif ($requestUri === '/catalog') {
    if ($requestMethod === 'GET') {
        $productController = new ProductController();
        $productController->showCatalog();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для добавления товара в корзину
elseif ($requestUri === '/add-product') {
    if ($requestMethod === 'GET') {
        $cartController = new CartController();
        $cartController->getAddProductForm();
    } elseif ($requestMethod === 'POST') {
        $cartController = new CartController();
        $cartController->addProduct();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для удаления продукта из корзины
elseif ($requestUri === '/remove-product') {
    if ($requestMethod === 'POST') {
        $cartController = new CartController();
        $cartController->removeProduct();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для отображения корзины
elseif ($requestUri === '/cart') {
    if ($requestMethod === 'GET') {
        $cartController = new CartController();
        $cartController->showCart();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для выхода из системы
elseif ($requestUri === '/logout') {
    if ($requestMethod === 'GET') {
        $userController = new UserController();
        $userController->logout();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Если маршрут не найден, возвращаем код ошибки 404 и подключаем страницу ошибки
else {
    http_response_code(404);
    require_once '../View/404.php';
}
