<?php
// Получаем URI запроса и метод HTTP
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Обработка маршрута для регистрации
if ($requestUri === '/registration') {
    if ($requestMethod === 'GET') {
        // Если метод GET, подключаем форму регистрации
        require_once './registration/get_registration.php';
    } elseif ($requestMethod === 'POST') {
        // Если метод POST, обрабатываем данные регистрации
        require_once './registration/handle_registration.php';
    } else {
        // Если метод HTTP не поддерживается, выводим сообщение об ошибке
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для входа в систему
elseif ($requestUri === '/login') {
    if ($requestMethod === 'GET') {
        // Если метод GET, подключаем форму входа
        require_once './login/get_login.php';
    } elseif ($requestMethod === 'POST') {
        // Если метод POST, обрабатываем данные входа
        require_once './login/handle_login.php';
    } else {
        // Если метод HTTP не поддерживается, выводим сообщение об ошибке
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для профиля пользователя
elseif ($requestUri === '/my_profile') {
    if ($requestMethod === 'GET') {
        // Если метод GET, отображаем профиль пользователя
        require_once './my_profile.php';
    } elseif ($requestMethod === 'POST') {
        // Если метод POST, обновляем данные профиля пользователя
        require_once './my_profile.php';
    } else {
        // Если метод HTTP не поддерживается, выводим сообщение об ошибке
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Обработка маршрута для каталога продуктов
elseif ($requestUri === '/catalog') {
    if ($requestMethod === 'GET') {
        // Если метод GET, отображаем каталог продуктов
        require_once './catalog.php';
    } else {
        // Если метод HTTP не поддерживается, выводим сообщение об ошибке
        echo "HTTP метод $requestMethod не поддерживается";
    }
}
// Если маршрут не найден, возвращаем код ошибки 404 и подключаем страницу ошибки
else {
    http_response_code(404);
    require_once './404.php';
}

?>