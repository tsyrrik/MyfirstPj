<?php
require_once '../Model/User_Products.php';

class CartController
{
    private User_Products $userProducts;

    public function __construct()
    {
        $this->userProducts = new User_Products();
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
        $error = $this->validateAddProduct($userId, $productId, $count);

        if ($error) {
            echo $error;
            return;
        }

        // Добавляем продукт в корзину
        $success = $this->userProducts->addProductToCart($userId, $productId, $count);
        if ($success) {
            echo "Продукт успешно добавлен в корзину";
        } else {
            echo "Ошибка при добавлении продукта в корзину";
        }
    }

    // Метод для валидации входных данных
    private function validateAddProduct($userId, $productId, $count)
    {
        if ($userId <= 0) {
            return "Неверный идентификатор пользователя";
        }
        if ($productId <= 0) {
            return "Неверный идентификатор продукта";
        }
        if ($count <= 0) {
            return "Неверное количество продукта";
        }
        if (!$this->userProducts->productExists($productId)) {
            return "Продукт с данным идентификатором не существует";
        }
        return null;
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
        $cartItems = $this->userProducts->getCartByUserId($userId);

        require_once '../View/cart.php';
    }
}

