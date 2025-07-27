<?php
// db.php: Database connection using PDO
// Настройте параметры подключения к вашей БД
$host = '127.0.0.1';
$db   = 'exchange_platform';
$user = 'root';      // замените на вашего пользователя БД
$pass = '';  // замените на ваш пароль БД
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// register.php: Страница регистрации для пользователей вузов
session_start();
require __DIR__ . '/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $university_id     = $_POST['university_id'] ?? '';
    $email             = trim($_POST['email'] ?? '');
    $password          = $_POST['password'] ?? '';
    $password_confirm  = $_POST['password_confirm'] ?? '';

    // Валидация
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введите корректный email.';
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Пароль должен содержать не менее 6 символов.';
    }
    if ($password !== $password_confirm) {
        $errors[] = 'Пароли не совпадают.';
    }
    if (!ctype_digit($university_id)) {
        $errors[] = 'Выберите университет.';
    }

    // Проверка уникальности email
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM university_users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Пользователь с таким email уже существует.';
        }
    }

    // Если ошибок нет — создаем запись
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare(
            'INSERT INTO university_users (university_id, email, password) VALUES (?, ?, ?)'
        );
        $stmt->execute([$university_id, $email, $password_hash]);

        $_SESSION['success'] = 'Регистрация прошла успешно!';
        header('Location: login.php');
        exit;
    }
}

// Получаем список вузов для выпадающего списка
$stmt = $pdo->query('SELECT id, name FROM universities');
$universities = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <!-- Подключите CSS-фреймворк по желанию, например Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4">Регистрация</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="mb-3">
            <label for="university_id" class="form-label">Университет</label>
            <select name="university_id" id="university_id" class="form-select" required>
                <option value="">-- Выберите университет --</option>
                <?php foreach ($universities as $uni): ?>
                    <option value="<?= $uni['id'] ?>" <?= (isset($university_id) && $university_id == $uni['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($uni['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label">Повторите пароль</label>
            <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
    </form>
</div>
</body>
</html>
