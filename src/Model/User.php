<?php

namespace Model;

class User extends Model
{
    // Метод для получения пользователя по email
    public function getByEmail(string $email): array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: [];
    }

    // Метод для создания нового пользователя
    public function create(string $name, string $email, string $password): void {
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
    public function getById(int $id): array {
        // Подготавливаем SQL-запрос для получения пользователя по id
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        // Выполняем запрос с переданным параметром id
        $stmt->execute(['id' => $id]);
        // Получаем результат запроса в виде ассоциативного массива
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Возвращаем результат или пустой массив, если пользователь не найден
        return $result ?: [];
    }

    // Метод для обновления данных пользователя
    public function update(int $id, string $name, string $lastName, string $email): void {
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

    // Метод для проверки существования пользователя с указанным email
    public function checkEmailExists(string $email): bool {
        // Подготавливаем SQL-запрос для подсчета количества пользователей с указанным email
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        // Выполняем запрос с переданным параметром email
        $stmt->execute([':email' => $email]);
        // Получаем количество найденных записей
        $count = $stmt->fetchColumn();

        // Возвращаем true, если количество больше 0, иначе false
        return $count > 0;
    }
}
