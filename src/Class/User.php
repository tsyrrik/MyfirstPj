<?php
session_start();

class User {
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    public function __construct(int $id, string $name, string $email, string $password) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    // Геттеры и сеттеры для каждого свойства
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }
}

function getDatabaseConnection(): \PDO {
    try {
        $pdo = new \PDO("pgsql:host=db; port=5432; dbname=dbname", 'dbuser', '123');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\PDOException $e) {
        die('Ошибка подключения к базе данных: ' . $e->getMessage());
    }
}

// Инициализация массива ошибок
$errors = [];

// Проверка наличия ключей в POST-запросе
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Получение данных из POST-запроса
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

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
                    $_SESSION['userId'] = $user['id'];
                    $_SESSION['userName'] = $user['name'];
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
    require_once __DIR__ . '/../View/get_login.php';
}





function validateRegistration(string $name, string $email, string $password, string $confirmPassword): array {
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
    if ($password !== $confirmPassword) {
        $errors['psw-repeat'] = 'Пароли не совпадают.';
    }

    return $errors;
}

// Проверка наличия ключей в POST-запросе для регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['psw']) && isset($_POST['psw-repeat'])) {
        // Получение данных из POST-запроса
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'psw', FILTER_SANITIZE_SPECIAL_CHARS);
        $confirmPassword = filter_input(INPUT_POST, 'psw-repeat', FILTER_SANITIZE_SPECIAL_CHARS);

        // Валидация данных
        $errors = validateRegistration($name, $email, $password, $confirmPassword);

        // Если нет ошибки, выполняем подключение к БД
        if (empty($errors)) {
            $pdo = getDatabaseConnection();

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
            }
        }
        require_once __DIR__ . '/../View/get_registration.php';
    }
}