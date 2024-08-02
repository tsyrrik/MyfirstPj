<?php

namespace Entity;

class UserProduct
{
    private int $id;
    private int $userId;
    private int $productId;
    private int $count;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->userId = $data['user_id'];
        $this->productId = $data['product_id'];
        $this->count = $data['count'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
