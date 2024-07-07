<?php

namespace Controller;

use Model\UserProduct;
use Model\Product;

class CartController
{
    private UserProduct $userProduct;
    private Product $product;

    public function __construct()
    {
        $this->userProduct = new UserProduct();
        $this->product = new Product();
    }

    // Метод для отображения формы добавления продукта
    public function getAddProductForm()
    {
        require_once '../View/add-product.php';
    }

    // Метод для добавления продукта в корзину
    public function addProduct()
    {
        session_start();
        if (!isset($_SESSION['userId'])) {
            // Если пользователь не авторизован, возвращаем код ошибки 403 и подключаем файл с ошибкой доступа
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }
        $userId = $_SESSION['userId'];
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $count = filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT);

        // Валидация входных данных
        $errors = $this->validateAddProduct($userId, $productId, $count);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            return;
        }

        // Добавляем продукт в корзину
        $success = $this->userProduct->addProductToCart($userId, $productId, $count);
        if ($success) {
            echo "Продукт успешно добавлен в корзину";
        } else {
            echo "Ошибка при добавлении продукта в корзину";
        }
    }

    // Метод для валидации входных данных
    private function validateAddProduct($userId, $productId, $count): array
    {
        $errors = [];
        // Проверка идентификатора пользователя
        if ($userId <= 0) {
            $errors[] = "Неверный идентификатор пользователя";
        }

        // Проверка идентификатора продукта
        if ($productId <= 0) {
            $errors[] = "Неверный идентификатор продукта";
        }

        // Проверка количества продукта
        if ($count <= 0) {
            $errors[] = "Неверное количество продукта";
        }

        // Проверка существования продукта
        if (!$this->product->productExists($productId)) {
            $errors[] = "Продукт с данным идентификатором не существует";
        }

        return $errors;
    }

    // Метод для отображения содержимого корзины
    public function showCart()
    {
        session_start();
        if (!isset($_SESSION['userId'])) {
            // Если пользователь не авторизован, возвращаем код ошибки 403 и подключаем файл с ошибкой доступа
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $cartItems = $this->userProduct->getCartByUserId($userId);

        require_once '../View/cart.php';
    }

}
