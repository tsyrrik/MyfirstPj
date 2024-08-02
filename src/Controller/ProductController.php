<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;

class ProductController
{
    private Product $product;
    private UserProduct $userProduct;

    public function __construct()
    {
        $this->product = new Product();
        $this->userProduct = new UserProduct();
    }

    public function showCatalog(): void
    {
        session_start();

        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $products = $this->product->getAll();
            $userProducts = $this->userProduct->getProductsByUserId($userId);

            $productCounts = [];
            foreach ($userProducts as $userProduct) {
                $productCounts[$userProduct->getProductId()] = $userProduct->getCount();
            }

            $productsWithCounts = [];
            foreach ($products as $product) {
                $productId = $product->getId();
                $count = $productCounts[$productId] ?? 0;
                $productsWithCounts[] = ['product' => $product, 'count' => $count];
            }

            require_once __DIR__ . '/../View/catalog.php';
        } else {
            http_response_code(403);
            require_once __DIR__ . '/../View/403.php';
        }
    }
}
