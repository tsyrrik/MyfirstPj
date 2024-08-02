<?php

namespace Entity;

class Product
{
    private int $id;
    private string $name;
    private ?string $description;
    private float $price;
    private ?string $imgUrl;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'] ?? null;
        $this->price = $data['price'];
        $this->imgUrl = $data['img_url'] ?? null;
    }

    // Геттеры
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }
}
