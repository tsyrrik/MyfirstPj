<?php
require_once 'Model.php';
class ProductModel extends Model
{
    public function getAllProducts(): array {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}