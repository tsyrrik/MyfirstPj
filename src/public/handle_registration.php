
COPY
<?php

// Получение данных из POST-запроса
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['psw'];
$confirm_password = $_POST['psw-repeat'];

// Массив для хранения ошибок
$errors = [];

// Проверка поля name
if (empty($name)) {
    $errors[] = 'Поле "Имя" обязательно для заполнения.';
}

// Проверка поля email
if (empty($email)) {
    $errors[] = 'Поле "Email" обязательно для заполнения.';
}

// Проверка поля password
if (empty($password)) {
    $errors[] = 'Поле "Пароль" обязательно для заполнения.';
}

// Проверка поля confirm_password
if (empty($confirm_password)) {
    $errors[] = 'Поле "Подтверждение пароля" обязательно для заполнения.';
}

// Проверка на количество символов
if (strlen($name) < 3 || strlen($name) > 255) {
    $errors[] = 'Имя должно содержать от 3 до 50 символов.';
}

if (strlen($email) < 5 || strlen($email) > 255) {
    $errors[] = 'Email должен содержать от 5 до 100 символов.';
}

if (strlen($password) < 3 || strlen($password) > 255) {
    $errors[] = 'Пароль должен содержать от 3 до 50 символов.';
}

// Проверка на наличие @ в email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Некорректный email.';
}

// Проверка совпадения паролей
if ($password !== $confirm_password) {
    $errors[] = 'Пароли не совпадают.';
}

// Если есть ошибки, отображаем их и прекращаем выполнение скрипта
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    exit;
}

// Подключение к базе данных
$pdo = new PDO("pgsql:host=db; port=5432; dbname=dbname", 'dbuser', '123');

// Проверка уникальности email
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    die('Пользователь с таким email уже существует.');
}

// Подготовка и выполнение запроса на вставку данных
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
$stmt->execute([':name' => $name, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);

// Получение ID последней вставленной записи
$lastInsertId = $pdo->lastInsertId();

// Выполнение запроса на выборку данных только что сохраненного пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $lastInsertId]);
$result = $stmt->fetch();

// Вывод результата
print_r($result);