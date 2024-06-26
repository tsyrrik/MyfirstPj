<?php
session_start(); // Начало сессии
require_once '../Model/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

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
    public function registrate() {
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
                    // Проверка уникальности email
                    if ($this->userModel->checkEmailExists($email)) {
                        $errors['email'] = 'Пользователь с таким email уже существует.';
                    } else {
                        // Создание пользователя
                        $this->userModel->create($name, $email, $password);

                        // Получение данных только что сохраненного пользователя
                        $user = $this->userModel->getByEmail($email);

                        // Вывод результата
                        print_r($user);
                    }
                }
                require_once '../View/get_registration.php';
            }
        }
    }

    // Функция авторизации
    public function login() {
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
                    // Проверка существования пользователя
                    $user = $this->userModel->getByEmail($email);

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
                $errors['form'] = 'Заполните форму для входа.';
            }
            require_once '../View/get_login.php';
        }
    }

    // Новый метод для отображения профиля пользователя
    public function showProfile() {
        // Проверка, авторизован ли пользователь
        if (!isset($_SESSION['userId'])) {
            http_response_code(403);
            require_once '../View/403.php';
            return;
        }

        $userId = $_SESSION['userId'];
        $user = $this->userModel->getById($userId);

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
            $this->userModel->update($userId, $name, $lastName, $email);

            // Получение обновленных данных пользователя
            $user = $this->userModel->getById($userId);

            // Вывод сообщения об успешном обновлении профиля
            echo '<div class="thanks"><h4>Thank you!</h4><p><small>Your profile has been successfully updated.</small></p></div>';
        }

        // Подключение шаблона профиля пользователя
        require_once '../View/my_profile.php';
    }
}