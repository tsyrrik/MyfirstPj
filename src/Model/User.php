<?php

namespace Model;

class User extends Model
{
    // Метод для получения пользователя по email
    public function getByEmail(string $email): ?\Entity\User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? new \Entity\User($result) : null;
    }

    // Метод для создания нового пользователя
    public function create(string $name, string $email, string $password): void
    {
        // Подготавливаем SQL-запрос для вставки нового пользователя
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        // Выполняем запрос с переданными параметрами
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password // Пароль уже должен быть хэширован перед вызовом этого метода
        ]);
    }

    // Метод для получения пользователя по id
    public function getById(int $id): ?\Entity\User
    {
        // Подготавливаем SQL-запрос для получения пользователя по id
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? new \Entity\User($result) : null;
    }

    // Метод для обновления данных пользователя
    public function update(int $id, string $name, string $lastName, string $email): void
    {
        // Подготавливаем SQL-запрос для обновления данных пользователя
        $stmt = $this->pdo->prepare('UPDATE users SET name = :name, last_name = :last_name, email = :email WHERE id = :id');
        // Выполняем запрос с переданными параметрами
        $stmt->execute([
            'name' => $name,
            'last_name' => $lastName,
            'email' => $email,
            'id' => $id
        ]);
    }
}
