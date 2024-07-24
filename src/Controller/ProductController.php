<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;

class ProductController
{
    private Product $product;
    private UserProduct $userProduct;

    // Конструктор инициализирует модели Product и UserProduct
    public function __construct()
    {
        $this->product = new Product();
        $this->userProduct = new UserProduct();
    }

    // Метод для отображения каталога продуктов
    public function showCatalog(): void
    {
        // Начало сессии
        session_start();

        // Проверка, авторизован ли пользователь
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];

            // Получение списка продуктов из модели
            $products = $this->product->getAllProducts();

            // Добавление количества продуктов в корзине для каждого продукта
            foreach ($products as &$product) {
                $product['count'] = $this->userProduct->getProductCount($userId, $product['id']);
            }

            // Подключение файла с каталогом продуктов и передача данных
            require_once __DIR__ . '/../View/catalog.php';
        } else {
            // Если пользователь не авторизован, устанавливаем код ответа 403 и подключаем файл с ошибкой доступа
            http_response_code(403);
            require_once __DIR__ . '/../View/403.php';
        }
    }
}
