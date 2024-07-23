<?php

// Функция автозагрузки классов
spl_autoload_register(function ($class) {

    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        echo "File not found: $file<br>";
    }
});

require_once __DIR__ . '/../App.php';

$app = new App();
$app->handle();
