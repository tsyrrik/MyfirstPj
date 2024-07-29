<?php

namespace Model;

class UserProduct extends Model
{
    public function getProductsByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT product_id, count FROM user_products WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $userProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $products = [];
        foreach ($userProducts as $userProduct) {
            $products[$userProduct['product_id']] = $userProduct['count'];
        }

        return $products;
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
    public function getOneByUserIdAndProductId(int $userId, int $productId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (empty($result)) {
            return null;
        }

        return $result;
    }
    public function increaseProductCount(int $userId, int $productId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE user_products SET count = count + 1 WHERE user_id = :user_id AND product_id = :product_id");
        return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }
    public function decreaseProductCount(int $userId, int $productId): bool
    {
        $existingProduct = $this->getOneByUserIdAndProductId($userId, $productId);
        if ($existingProduct && $existingProduct['count'] > 1) {
            $stmt = $this->pdo->prepare("UPDATE user_products SET count = count - 1 WHERE user_id = :user_id AND product_id = :product_id");
            return $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        } elseif ($existingProduct && $existingProduct['count'] == 1) {
            $this->delete($userId, $productId);
            return true;
        }
        return false;
    }
    public function delete(int $userId, int $productId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }
}
