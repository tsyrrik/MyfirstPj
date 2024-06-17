<?php
//Функция для валидации данных регистрации
function validate_registration_data($name, $email, $password, $confirm_password) {
    $errors = [];

    // Проверка поля name, -> проверка на количество символов
    if (empty($name)) {
        $errors['name'] = 'Поле "Имя" обязательно для заполнения.';
    } elseif (strlen($name) < 2 || strlen($name) > 255) {
        $errors['name'] = 'Имя должно содержать от 2 до 255 символов.';
    }

    // Проверка поля email, -> проверка на количество символов, -> проверка на наличие @ в email
    if (empty($email)) {
        $errors['email'] = 'Поле "Email" обязательно для заполнения.';
    } elseif (strlen($email) < 2 || strlen($email) > 255) {
        $errors['email'] = 'Email должен содержать от 2 до 255 символов.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email.';
    }

    // Проверка поля password, -> проверка на количество символов, -> проверка совпадения паролей
    if (empty($password)) {
        $errors['psw'] = 'Поле "Пароль" обязательно для заполнения.';
    } elseif (strlen($password) < 3 || strlen($password) > 255) {
        $errors['psw'] = 'Пароль должен содержать от 3 до 255 символов.';
    }
    if ($password !== $confirm_password) {
        $errors['psw-repeat'] = 'Пароли не совпадают.';
    }

    return $errors;
}

// Получение данных из POST-запроса
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['psw'];
$confirm_password = $_POST['psw-repeat'];

// Валидация данных
$errors = validate_registration_data($name, $email, $password, $confirm_password);

// Если нет ошибки, выполняем подключение к БД
if (empty($errors)) {
    try {
        $pdo = new PDO("pgsql:host=db; port=5432; dbname=dbname", 'dbuser', '123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Проверка уникальности email
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errors['email'] = 'Пользователь с таким email уже существует.';
        } else {
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
        }//Обработка исключений при подключении к базе данных:
    } catch (PDOException $e) { //Этот блок кода перехватывает исключения типа PDOException, которые могут возникнуть при работе с базой данных через PDO
        echo 'Ошибка подключения к базе данных: ' . $e->getMessage();
    }
} else {
    // Вывод ошибок валидации
    foreach ($errors as $error) {
        echo '<p>' . $error . '</p>';
    }
}

require_once './get_registration.php';
?>