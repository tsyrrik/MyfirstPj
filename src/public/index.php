<?php
// Получаем URI запроса и метод HTTP
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Функция автозагрузки классов
$func = function (string $className) {
    // Преобразуем имя класса в путь к файлу
    $directories = ['../Controller', '../Model'];
    foreach ($directories as $directory) {
        $file = $directory . '/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
};
 spl_autoload_register($func);

// Обработка маршрута для регистрации
if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        require_once '../View/get_registration.php';
    } elseif ($requestMethod === 'POST') {
        $obj = new UserController();
        $obj->registrate();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для входа в систему
elseif ($requestUri === '/login') {
    if ($requestMethod === 'GET') {
        require_once '../View/get_login.php';
    } elseif ($requestMethod === 'POST') {
        $obj = new UserController();
        $obj->login();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для профиля пользователя
elseif ($requestUri === '/my_profile') {
    $obj = new UserController();
    $obj->showProfile();
}
// Обработка маршрута для каталога продуктов
elseif ($requestUri === '/catalog') {
    if ($requestMethod === 'GET') {
        $product = new ProductController();
        $product->showCatalog();
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
// Обработка маршрута для удаления товара из корзины
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
        $obj = new UserController();
        $obj->logout();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Если маршрут не найден, возвращаем код ошибки 404 и подключаем страницу ошибки
else {
    http_response_code(404);
    require_once '../View/404.php';
}
