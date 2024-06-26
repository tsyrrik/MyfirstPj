<?php
require_once __DIR__ . '/../Model/ProductModel.php';

class ProductController
{
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    // Метод для отображения каталога продуктов
    public function showCatalog()
    {
        // Начало сессии
        session_start();

        // Проверка, авторизован ли пользователь
        if (isset($_SESSION['userId'])) {
            // Получение списка продуктов из модели
            $products = $this->productModel->getAllProducts();

            // Подключение файла с каталогом продуктов и передача данных
            require_once __DIR__ . '/../View/catalog.php';
        } else {
            // Если пользователь не авторизован, устанавливаем код ответа 403 и подключаем файл с ошибкой доступа
            http_response_code(403);
            require_once __DIR__ . '/../View/403.php';
        }
    }
}