<?php

namespace Model;

class Product extends Model
{
    // Метод для получения всех продуктов
    public function getAll(): array
    {
        // Выполняем SQL-запрос для получения всех записей из таблицы products
        $stmt = $this->pdo->query("SELECT * FROM products");
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $products = [];
        foreach ($result as $data) {
            $products[] = new \Entity\Product($data);
        }

        return $products;
    }


    // Проверка существования продукта по product_Id
    public function exists(int $productId): bool
    {
        // Подготавливаем SQL-запрос для проверки существования записи с указанным product_id
        $stmt = $this->pdo->prepare("SELECT 1 FROM products WHERE id = :product_id");
        // Выполняем запрос с переданным параметром product_id
        $stmt->execute([':product_id' => $productId]);
        // Возвращаем true, если запись найдена, иначе false
        return (bool) $stmt->fetchColumn();
    }
    // Метод для получения продуктов по массиву идентификаторов
    public function getProductsByIds(array $productIds): array
    {
        // Создаем строку с плейсхолдерами для IN-условия
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        // Подготавливаем SQL-запрос с IN-условием
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");

        // Выполняем запрос с переданными идентификаторами продуктов
        $stmt->execute($productIds);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $products = [];
        foreach ($result as $data) {
            $products[] = new \Entity\Product($data);
        }

        return $products;
    }
}
