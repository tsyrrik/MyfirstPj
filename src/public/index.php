<?php

use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;

// Функция автозагрузки классов
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    } else {
        echo "File not found: $file<br>";
    }
});

$app = new \App();

$app->addGetRoute('/registration', UserController::class, 'getRegistrate');
$app->addPostRoute('/registration', UserController::class, 'registrate');
$app->addGetRoute('/login', UserController::class, 'getLogin');
$app->addPostRoute('/login', UserController::class, 'login');
$app->addGetRoute('/my_profile', UserController::class, 'showProfile');
$app->addGetRoute('/catalog', ProductController::class, 'showCatalog');
$app->addGetRoute('/add-product', CartController::class, 'getAddProductForm');
$app->addPostRoute('/add-product', CartController::class, 'addProduct');
$app->addPostRoute('/increase-product', CartController::class, 'increaseProduct');
$app->addPostRoute('/decrease-product', CartController::class, 'decreaseProduct');
$app->addPostRoute('/remove-product', CartController::class, 'removeProduct');
$app->addGetRoute('/cart', CartController::class, 'showCart');
$app->addGetRoute('/logout', UserController::class, 'logout');

$app->handle();