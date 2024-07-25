<?php

namespace Controller;

use Model\UserProduct;
use Model\Product;

class CartController
{
    private UserProduct $userProduct;
    private Product $product;

    // Конструктор инициализирует модели UserProduct и Product
    public function __construct()
    {
        $this->userProduct = new UserProduct();
        $this->product = new Product();
    }

    // Метод отображает корзину пользователя
    public function showCart(): void
    {
        session_start();

        // Проверка, авторизован ли пользователь
        if (!isset($_SESSION['userId'])) {
            http_response_code(403); // Возвращает код ошибки 403 (доступ запрещен)
            require_once '../View/403.php';
            return;
        }

        // Получение идентификатора пользователя из сессии
        $userId = $_SESSION['userId'];

        // Получение продуктов в корзине пользователя
        $cartItems = $this->userProduct->getProductsByUserId($userId);

        // Подключение представления корзины
        require_once '../View/cart.php';
    }

    // Метод добавления продукта в корзину
    public function addProduct(): void
    {
        session_start();

        // Проверка, авторизован ли пользователь
        if (!isset($_SESSION['userId'])) {
            http_response_code(403); // Возвращает код ошибки 403 (доступ запрещен)
            require_once '../View/403.php';
            return;
        }

        // Получение идентификатора пользователя из сессии
        $userId = $_SESSION['userId'];

        // Получение идентификатора продукта и количества из POST-запроса
        $productId = $_POST['productId'];
        $count = isset($_POST['count']) ? intval($_POST['count']) : 1;
        // Валидация входных данных
        $errors = $this->validateAddProduct($userId, $productId, $count);

        // Если есть ошибки, выводим их и прерываем выполнение
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

    // Метод валидации данных для добавления продукта в корзину
    private function validateAddProduct($userId, $productId, $count): array
    {
        $errors = [];
        if ($userId <= 0) {
            $errors[] = "Неверный идентификатор пользователя";
        }
        if ($productId <= 0) {
            $errors[] = "Неверный идентификатор продукта";
        }
        if ($count <= 0) {
            $errors[] = "Неверное количество продукта";
        }
        if (!$this->product->productExists($productId)) {
            $errors[] = "Продукт с данным идентификатором не существует";
        }
        return $errors;
    }
    // Метод увеличения количества продукта на 1
    public function increaseProduct(): void
    {
        session_start();

        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $productId = $_POST['productId'];

        $errors = $this->validateIncreaseProduct($userId, $productId);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            return;
        }

        $currentCount = $this->userProduct->getProductCount($userId, $productId);
        $newCount = $currentCount + 1;
        $this->userProduct->updateProductCount($userId, $productId, $newCount);

        echo "Количество продукта увеличено на 1.";
    }

    private function validateIncreaseProduct($userId, $productId): array
    {
        $errors = [];
        if ($productId <= 0) {
            $errors[] = "Неверный идентификатор продукта";
        }
        return $errors;
    }
    // Метод уменьшения количества продукта на 1
    public function decreaseProduct(): void
    {
        session_start();

        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $productId = $_POST['productId'];

        $errors = $this->validateDecreaseProduct($userId, $productId);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            return;
        }

        $currentCount = $this->userProduct->getProductCount($userId, $productId);
        if ($currentCount > 0) {
            $newCount = $currentCount - 1;

            if ($newCount > 0) {
                $this->userProduct->updateProductCount($userId, $productId, $newCount);
                echo "Количество продукта уменьшено на 1.";
            } else {
                $this->userProduct->delete($userId, $productId);
                echo "Продукт удален из корзины.";
            }
        } else {
            echo "Продукт не найден в корзине.";
        }
    }

    private function validateDecreaseProduct($userId, $productId): array
    {
        $errors = [];

        if ($productId <= 0) {
            $errors[] = "Неверный идентификатор продукта";
        }
        return $errors;
    }
    // Метод удаления продукта из корзины
    public function removeProduct(): void
    {
        session_start();

        // Проверка, авторизован ли пользователь
        if (!isset($_SESSION['userId'])) {
            http_response_code(403); // Возвращает код ошибки 403 (доступ запрещен)
            require_once '../View/403.php';
            return;
        }

        // Получение идентификатора пользователя из сессии
        $userId = $_SESSION['userId'];

        // Получение идентификатора продукта из POST-запроса
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

        // Проверка, корректен ли идентификатор продукта
        if ($productId) {
            $this->userProduct->delete($userId, $productId); // Удаление продукта из корзины
            header('Location: /cart'); // Перенаправление на страницу корзины
            return; // Возвращаем управление, прерывая выполнение метода
        } else {
            echo "Неверный идентификатор продукта.";
        }
    }

    // Метод отображения формы для добавления продукта в корзину
    public function getAddProductForm(): void
    {
        // Получение всех продуктов из базы данных
        $products = $this->product->getAllProducts();

        // Подключение представления формы добавления продукта
        require_once __DIR__ . '/../View/add-product.php';
    }
}
