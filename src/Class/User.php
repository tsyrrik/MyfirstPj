<?php
session_start(); // Начало сессии

class User
{
    // Метод для валидации данных регистрации
    private function validateRegistration(array $data): array {
        $errors = [];

        // Проверка поля name, -> проверка на количество символов
        if (empty($data['name'])) {
            $errors['name'] = 'Поле "Имя" обязательно для заполнения.';
        } elseif (strlen($data['name']) < 2 || strlen($data['name']) > 255) {
            $errors['name'] = 'Имя должно содержать от 2 до 255 символов.';
        }

        // Проверка поля email, -> проверка на количество символов, -> проверка на наличие @ в email
        if (empty($data['email'])) {
            $errors['email'] = 'Поле "Email" обязательно для заполнения.';
        } elseif (strlen($data['email']) < 2 || strlen($data['email']) > 255) {
            $errors['email'] = 'Email должен содержать от 2 до 255 символов.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный email.';
        }

        // Проверка поля password, -> проверка на количество символов, -> проверка совпадения паролей
        if (empty($data['psw'])) {
            $errors['psw'] = 'Поле "Пароль" обязательно для заполнения.';
        } elseif (strlen($data['psw']) < 3 || strlen($data['psw']) > 255) {
            $errors['psw'] = 'Пароль должен содержать от 3 до 255 символов.';
        }
        if ($data['psw'] !== $data['psw-repeat']) {
            $errors['psw-repeat'] = 'Пароли не совпадают.';
        }

        return $errors;
    }

    // Функция регистрации
    public function registrate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['psw']) && isset($_POST['psw-repeat'])) {
                // Получение данных из POST-запроса
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = filter_input(INPUT_POST, 'psw', FILTER_SANITIZE_SPECIAL_CHARS);
                $confirmPassword = filter_input(INPUT_POST, 'psw-repeat', FILTER_SANITIZE_SPECIAL_CHARS);

                // Валидация данных
                $errors = $this->validateRegistration([
                    'name' => $name,
                    'email' => $email,
                    'psw' => $password,
                    'psw-repeat' => $confirmPassword
                ]);

                // Если нет ошибки, выполняем подключение к БД
                if (empty($errors)) {
                    $pdo = $this->getDatabaseConnection();

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
    }

    // Функция авторизации
    public function login()
    {
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
                    $pdo = $this->getDatabaseConnection();

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
    }

    // Метод для получения подключения к базе данных
    private function getDatabaseConnection(): \PDO {
        try {
            $pdo = new \PDO("pgsql:host=db; port=5432; dbname=dbname", 'dbuser', '123');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            die('Ошибка подключения к базе данных: ' . $e->getMessage());
        }
    }

    // Новый метод для отображения профиля пользователя
    public function showProfile()
    {
        // Проверка, авторизован ли пользователь
        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once __DIR__ . '/../View/403.php';
            return;
        }

        $pdo = $this->getDatabaseConnection();
        $userId = $_SESSION['userId'];
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        if (!$user) {
            die('Пользователь не найден');
        }

        // Обработка POST-запроса для обновления профиля
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';

            // Валидация данных
            if (!preg_match("/^[a-zA-Z\s]+$/", $name) || !preg_match("/^[a-zA-Z\s]+$/", $lastName)) {
                die('Имя и фамилия должны содержать только буквы.');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                die('Неверный формат email.');
            }

            // Обновление данных пользователя в базе данных
            $stmt = $pdo->prepare('UPDATE users SET name = :name, last_name = :last_name, email = :email WHERE id = :id');
            $stmt->execute([
                'name' => $name,
                'last_name' => $lastName,
                'email' => $email,
                'id' => $userId
            ]);

            // Получение обновленных данных пользователя
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            // Вывод сообщения об успешном обновлении профиля
            echo '<div class="thanks"><h4>Thank you!</h4><p><small>Your profile has been successfully updated.</small></p></div>';
        }

        // Подключение шаблона профиля пользователя
        require_once __DIR__ . '/../View/my_profile.php';
    }
}