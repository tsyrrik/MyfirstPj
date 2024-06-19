<?php
// Функция для подключения к базе данных
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


// Если нет ошибок, выполняем подключение к БД и проверку пользователя
if (empty($errors)) {
    $pdo = getDatabaseConnection();

    // Проверка существования пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Проверка пароля
        if (password_verify($password, $user['password'])) {
            // Успешный вход
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            echo "OK, " . htmlspecialchars($user['name']) . "!";
        } else {
            $errors['password'] = 'Введен неверный логин или пароль';
        }
    } else {
        $errors['email'] = 'Введен неверный логин или пароль';
    }
}

require_once './get_login.php';
?>