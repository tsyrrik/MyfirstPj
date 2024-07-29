<?php

namespace Model;

class Model
{
    // Свойство для хранения экземпляра PDO
    protected \PDO $pdo;

    // Конструктор устанавливает соединение с базой данных PostgreSQL
    public function __construct()
    {
        // Создание нового экземпляра PDO для подключения к базе данных
        $this->pdo = new \PDO("pgsql:host=db; port=5432; dbname=dbname", 'dbuser', '123');

        // Установка атрибута для обработки ошибок PDO
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
