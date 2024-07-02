<?php
require_once '../Model/CartModel.php';

class CartController
{
    private CartModel $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
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

        if ($productId && $count) {
            try {
                // Добавляем продукт в корзину
                $this->cartModel->addProductToCart($userId, $productId, $count);
                echo "Продукт успешно добавлен в корзину";
            } catch (Exception $e) {
                echo "Ошибка при добавлении продукта в корзину: " . $e->getMessage();
            }
        } else {
            echo "Неверный идентификатор продукта или его количество";
        }
    }
}