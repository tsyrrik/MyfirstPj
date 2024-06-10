<?php
echo "Hello world!" . "<br>";

try {
    $pdo = new PDO("pgsql:host=localhost; port=5432; dbname=testdb", 'testuser', 'root');
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id SERIAL PRIMARY KEY, name VARCHAR(255) NOT NULL)");
    echo "Таблица создана или уже существует.";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>