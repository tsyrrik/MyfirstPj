<?php

namespace Class;

class Product {
    private int $id;
    private string $name;
    private string $description;
    private float $price;

    public function __construct(int $id, string $name, string $description, float $price) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    // Getters and setters for each property
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }
}

session_start();

if (isset($_SESSION['userId'])) {
    require_once './View/catalog.php';
} else {
    http_response_code(403);
    require_once './View/403.php';
}