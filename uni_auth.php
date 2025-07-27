<?php
session_start();

// Проверяем, что запрос POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: uni_login.php?error=Неверный метод запроса');
    exit;
}

// Получаем данные из формы
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Валидация данных
if (empty($email) || empty($password)) {
    header('Location: uni_login.php?error=Пожалуйста, заполните все поля');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: uni_login.php?error=Неверный формат email');
    exit;
}

try {
    // Подключение к базе данных
    require __DIR__ . '/db.php';   // тот же файл, что и в register.php

    // Если файла конфигурации нет, создаем подключение напрямую
    if (!isset($pdo)) {
        $host = 'localhost';
        $dbname = 'exchange_platform';
        $username = 'root';
        $db_password = '';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Поиск пользователя-университета по email
    $stmt = $pdo->prepare("
        SELECT uu.*, u.name as university_name, u.country, u.logo_url 
        FROM university_users uu 
        JOIN universities u ON uu.university_id = u.id 
        WHERE uu.email = ? AND uu.is_active = 1
    ");
    $stmt->execute([$email]);
    $uni_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$uni_user) {
        header('Location: uni_login.php?error=Неверный email или пароль');
        exit;
    }

    // Проверка пароля
    if (!password_verify($password, $uni_user['password'])) {
        header('Location: uni_login.php?error=Неверный email или пароль');
        exit;
    }

    // Успешная авторизация - создаем сессию
    $_SESSION['uni_user_id'] = $uni_user['id'];
    $_SESSION['uni_university_id'] = $uni_user['university_id'];
    $_SESSION['uni_email'] = $uni_user['email'];
    $_SESSION['uni_university_name'] = $uni_user['university_name'];
    $_SESSION['uni_country'] = $uni_user['country'];
    $_SESSION['uni_logo_url'] = $uni_user['logo_url'];
    $_SESSION['uni_logged_in'] = true;

    // Обновляем время последнего входа
    $update_stmt = $pdo->prepare("UPDATE university_users SET updated_at = NOW() WHERE id = ?");
    $update_stmt->execute([$uni_user['id']]);

    // Перенаправляем на панель управления
    header('Location: uni_dashboard.php');
    exit;

} catch (PDOException $e) {
    // Логируем ошибку (в реальном проекте используйте логирование)
    error_log("Database error in uni_auth.php: " . $e->getMessage());
    header('Location: uni_login.php?error=Ошибка базы данных. Попробуйте позже.');
    exit;
} catch (Exception $e) {
    // Логируем общую ошибку
    error_log("General error in uni_auth.php: " . $e->getMessage());
    header('Location: uni_login.php?error=Произошла ошибка. Попробуйте позже.');
    exit;
}
?>