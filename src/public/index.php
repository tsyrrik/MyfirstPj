<?php
// Получаем URI запроса и метод HTTP
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Обработка маршрута для регистрации
if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        // Если метод GET, подключаем форму регистрации
        require_once '../View/get_registration.php';
    } elseif ($requestMethod === 'POST') {
        // Если метод POST, обрабатываем данные регистрации
        require_once '../Controller/UserController.php';
        $obj = new UserController();
        $obj->registrate();
    } else {
        // Если метод HTTP не поддерживается, выводим сообщение об ошибке
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для входа в систему
elseif ($requestUri === '/login') {
    if ($requestMethod === 'GET') {
        // Если метод GET, подключаем форму входа
        require_once '../View/get_login.php';
    } elseif ($requestMethod === 'POST') {
        // Если метод POST, обрабатываем данные входа
        require_once '../Controller/UserController.php';
        $obj = new UserController();
        $obj->login();
    } else {
        // Если метод HTTP не поддерживается, выводим сообщение об ошибке
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
        // Если метод GET, отображаем каталог продуктов
        require_once '../Controller/ProductController.php';
        $product = new ProductController();
        $product->showCatalog();
    } else {
        // Если метод HTTP не поддерживается, выводим сообщение об ошибке
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Если маршрут не найден, возвращаем код ошибки 404 и подключаем страницу ошибки
else {
    http_response_code(404);
    require_once '../View/404.php';
}