<?php
require_once 'Model.php';

class Product extends Model
{
    // Метод для получения всех продуктов
    public function getAllProducts(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Проверка существования продукта по product_Id
    public function productExists(int $productId): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM products WHERE id = :product_id");
        $stmt->execute([':product_id' => $productId]);
        return (bool) $stmt->fetchColumn();
    }
}
