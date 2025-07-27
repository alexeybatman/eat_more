<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'exchange_platform');

try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("SET NAMES utf8");
} catch (PDOException $e) {
    die("Не удалось подключиться к БД: " . $e->getMessage());
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (!$email || !$pass) {
        $error = 'Пожалуйста, заполните все поля.';
    } else {
        $stmt = $db->prepare("SELECT id, name, password FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($pass, $user['password'])) {
            $error = 'Неверный e-mail или пароль.';
        } else {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header('Location: index.php');
            exit;
        }
    }
}

include 'header.php';
?>
<style>
    .auth-container{
    margin-top: 10% !important;
    }
    body{
        background: #ffffff !important;
    }
</style>
<main class="main-content">
    <div class="container">
        <div class="auth-container">
            <h2 class="section-title text-center">Вход</h2>

            <?php if (isset($_GET['registered'])): ?>
                <div class="success-message">Регистрация прошла успешно! Войдите в аккаунт.</div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="login.php" class="auth-form">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input
                            type="password"
                            id="password"
                            name="password"
                            required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Войти
                </button>

                <div class="form-footer">
                    Нет аккаунта?
                    <a href="signup.php" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user-plus"></i> Зарегистрироваться
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
<style>

    .auth-container {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        padding: 40px 30px;
        animation: fadeIn 0.5s ease-in-out;
    }

    .auth-form .form-group {
        margin-bottom: 20px;
    }

    .auth-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .auth-form input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 10px;
        transition: 0.3s;
    }

    .auth-form input:focus {
        border-color: #7c4dff;
        box-shadow: 0 0 0 3px rgba(124, 77, 255, 0.2);
        outline: none;
    }

    .auth-form .btn-primary {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .auth-form .btn-primary:hover {
        opacity: 0.9;
    }

    .auth-form .form-footer {
        margin-top: 20px;
        text-align: center;
    }

    .auth-form .form-footer a.btn {
        margin-top: 10px;
    }

    .success-message, .error-message {
        margin-bottom: 20px;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
    }

    .success-message {
        background-color: #e0f7e9;
        color: #2e7d32;
    }

    .error-message {
        background-color: #ffebee;
        color: #c62828;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }

</style>
<?php include 'includes/footer.php'; ?>
