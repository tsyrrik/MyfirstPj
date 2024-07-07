<?php

namespace Model;

class UserProduct extends Model
{
    public function getProductsByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT p.id, p.name, p.description, p.price, up.count, p.img_url 
                                     FROM products p 
                                     JOIN user_products up ON p.id = up.product_id 
                                     WHERE up.user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addProductToCart(int $userId, int $productId, int $count): bool
    {
        $stmt = $this->pdo->prepare("SELECT count FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $existingProduct = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($existingProduct) {
            $newCount = $existingProduct['count'] + $count;
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = :count WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->execute(['count' => $newCount, 'user_id' => $userId, 'product_id' => $productId]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, count) VALUES (:user_id, :product_id, :count)");
            $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'count' => $count]);
        }
        return true;
    }

    public function delete(int $userId, int $productId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    public function productExists(int $productId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE id = :product_id");
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchColumn() > 0;
    }
}
