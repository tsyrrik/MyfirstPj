<?php

namespace Model;

class Product extends Model
{
    // Метод для получения всех продуктов
    public function getAll(): array
    {
        // Выполняем SQL-запрос для получения всех записей из таблицы products
        $stmt = $this->pdo->query("SELECT * FROM products");
        // Возвращаем все найденные записи в виде ассоциативного массива
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
}
