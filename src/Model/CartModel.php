<?php

require_once 'Model.php';

class CartModel extends Model
{
    public function addProductToCart(int $userId, int $productId, int $count): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, count) VALUES (:user_id, :product_id, :count)");
        $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':count' => $count
        ]);
    }

    public function getCartByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
