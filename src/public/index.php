<?php
// Получаем URI запроса и метод HTTP
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Обработка маршрута для регистрации
if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        require_once '../View/get_registration.php';
    } elseif ($requestMethod === 'POST') {
        require_once '../Controller/UserController.php';
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
        require_once '../Controller/UserController.php';
        $obj = new UserController();
        $obj->login();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для профиля пользователя
elseif ($requestUri === '/my_profile') {
    require_once '../Controller/UserController.php';
    $obj = new UserController();
    $obj->showProfile();
}
// Обработка маршрута для каталога продуктов
elseif ($requestUri === '/catalog') {
    if ($requestMethod === 'GET') {
        require_once '../Controller/ProductController.php';
        $product = new ProductController();
        $product->showCatalog();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для добавления товара в корзину
elseif ($requestUri === '/add-product') {
    if ($requestMethod === 'GET') {
        require_once '../Controller/CartController.php';
        $cartController = new CartController();
        $cartController->getAddProductForm();
    } elseif ($requestMethod === 'POST') {
        require_once '../Controller/CartController.php';
        $cartController = new CartController();
        $cartController->addProduct();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для отображения корзины
elseif ($requestUri === '/cart') {
    if ($requestMethod === 'GET') {
        require_once '../Controller/CartController.php';
        $cartController = new CartController();
        $cartController->showCart();
    } else {
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для выхода из системы
elseif ($requestUri === '/logout') {
    if ($requestMethod === 'GET') {
        require_once '../Controller/UserController.php';
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
