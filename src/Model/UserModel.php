<?php

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = new \PDO("pgsql:host=db; port=5432; dbname=dbname", 'dbuser', '123');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getByEmail(string $email): array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: [];
    }

    public function create(string $name, string $email, string $password): void {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function getById(int $id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: [];
    }

    public function update(int $id, string $name, string $lastName, string $email): void {
        $stmt = $this->pdo->prepare('UPDATE users SET name = :name, last_name = :last_name, email = :email WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'last_name' => $lastName,
            'email' => $email,
            'id' => $id
        ]);
    }

    public function checkEmailExists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }
}
