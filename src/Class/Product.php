<?php

class Product
{
    // Метод для отображения каталога продуктов
    public function showCatalog()
    {
        // Начало сессии
        session_start();

        // Проверка, авторизован ли пользователь
        if (isset($_SESSION['userId'])) {
            // Если пользователь авторизован, подключаем файл с каталогом продуктов
            require_once __DIR__ . '/../View/catalog.php';
        } else {
            // Если пользователь не авторизован, устанавливаем код ответа 403 и подключаем файл с ошибкой доступа
            http_response_code(403);
            require_once __DIR__ . '/../View/403.php';
        }
    }
}

