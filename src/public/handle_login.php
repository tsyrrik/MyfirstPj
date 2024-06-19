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

// Инициализация массива ошибок
$errors = [];

// Проверка наличия ключей в POST-запросе
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Получение данных из POST-запроса
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Валидация данных (например, проверка на пустые поля)
    if (empty($email)) {
        $errors['email'] = 'Поле "Email" обязательно для заполнения.';
    }
    if (empty($password)) {
        $errors['password'] = 'Поле "Пароль" обязательно для заполнения.';
    }

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
                $_SESSION['userId'] = $user['id'];
                $_SESSION['userName'] = $user['name'];
                // Установка куки
                setcookie("userId", $user['id']);
                setcookie("userName", $user['name']);
                echo "OK, " . htmlspecialchars($user['name']) . "!";
            } else {
                $errors['password'] = 'Введен неверный логин или пароль';
            }
        } else {
            $errors['email'] = 'Введен неверный логин или пароль';
        }
    }
} else {
    $errors['form'] = 'Пожалуйста, заполните форму для входа.';
}
require_once './get_login.php';
?>