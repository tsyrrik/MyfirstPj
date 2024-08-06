<?php

namespace Model;

class UserProduct extends Model
{
    public function getProductsByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT id, user_id, product_id, count FROM user_products WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $userProducts = [];
        foreach ($results as $result) {
            $userProducts[] = new \Entity\UserProduct($result);
        }

        return $userProducts;
    }

    public function getOneByUserIdAndProductId(int $userId, int $productId): ?\Entity\UserProduct
    {
        $stmt = $this->pdo->prepare("SELECT id, user_id, product_id, count FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? new \Entity\UserProduct($result) : null;
    }

    public function addProductToCart(int $userId, int $productId, int $count): bool
    {
        $stmt = $this->pdo->prepare("SELECT id, count FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $existingProduct = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($existingProduct) {
            $newCount = $existingProduct['count'] + $count;
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = :count WHERE id = :id");
            return $stmt->execute(['count' => $newCount, 'id' => $existingProduct['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, count) VALUES (:user_id, :product_id, :count)");
            return $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'count' => $count]);
        }
    }

    public function increaseProductCount(int $userId, int $productId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE user_products SET count = count + 1 WHERE user_id = :user_id AND product_id = :product_id");
        return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    public function decreaseProductCount(int $userId, int $productId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE user_products SET count = count - 1 WHERE user_id = :user_id AND product_id = :product_id");
        return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    public function delete(int $userId, int $productId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    public function getCount(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT count(*) FROM user_products WHERE user_id = :user_id");
        return $stmt->execute(['user_id' => $userId]);

        $result = $stmt->fetch();

        if (empty($result)) {
            return 0;
        }

        return $result['count'];
    }
    public function getTotalProductCount(int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT SUM(count) as total_count FROM user_products WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch();

        return $result['total_count'] ?? 0;
    }

    public function getTotalPrice(int $userId): float
    {
        $stmt = $this->pdo->prepare("
            SELECT SUM(up.count * p.price) as total_price
            FROM user_products up
            JOIN products p ON up.product_id = p.id
            WHERE up.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch();

        return $result['total_price'] ?? 0.0;
    }
}
