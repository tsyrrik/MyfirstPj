<?php

function validateLogin($email, $password) {
    $errors = [];

    // Проверка поля email, -> проверка на наличие @ в email
    if (empty($email)) {
        $errors['email'] = 'Поле "Email" обязательно для заполнения.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email.';
    }

    // Проверка поля password
    if (empty($password)) {
        $errors['password'] = 'Поле "Пароль" обязательно для заполнения.';
    }


// Подключение к базе данных
function getDatabaseConnection() {
    try {
        $pdo = new PDO("pgsql:host=db; port=5432; dbname=dbname", 'dbuser', '123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Ошибка подключения к базе данных: ' . $e->getMessage());
    }
}
// Получение данных из POST-запроса
    $email = $_POST['email'];
    $password = $_POST['password'];

// Валидация данных
    $errors = validateRegistration($email, $password);

// Если нет ошибки, выполняем подключение к БД
    if (empty($errors)) {
        $pdo = getDatabaseConnection();