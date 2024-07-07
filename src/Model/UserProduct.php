<?php

namespace Model;

class UserProduct extends Model
{
    public function addProductToCart(int $userId, int $productId, int $count): bool
    {
        // Проверяем, существует ли уже запись с таким productId для данного пользователя
        $stmt = $this->pdo->prepare("SELECT count FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId
        ]);
        $existingProduct = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($existingProduct) {
            // Если запись существует, обновляем количество
            $newCount = $existingProduct['count'] + $count;
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = :count WHERE user_id = :user_id AND product_id = :product_id");
            return $stmt->execute([
                ':count' => $newCount,
                ':user_id' => $userId,
                ':product_id' => $productId
            ]);
        } else {
            // Если записи не существует, добавляем новую
            $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, count) VALUES (:user_id, :product_id, :count)");
            return $stmt->execute([
                ':user_id' => $userId,
                ':product_id' => $productId,
                ':count' => $count
            ]);
        }
    }

    // Метод для получения продуктов в корзине по userId
    public function getCartByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.name, p.price, p.img_url, up.count 
            FROM user_products up 
            JOIN products p ON up.product_id = p.id 
            WHERE up.user_id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
