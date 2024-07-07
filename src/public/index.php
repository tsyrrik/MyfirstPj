<?php

use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;

// Функция автозагрузки классов
spl_autoload_register(function ($class) {
    $prefixes = [
        'Controller\\' => __DIR__ . '/../Controller/',
        'Model\\' => __DIR__ . '/../Model/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        } else {
            echo "File not found: $file<br>";
        }
    }
});

require_once __DIR__ . '/../App.php';

$app = new App();
$app->handle();
