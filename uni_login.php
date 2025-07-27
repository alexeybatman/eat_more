<?php
require 'header.php';    // head, навигация, style.css
session_start();
if (isset($_SESSION['uni_user_id'])) {
    header('Location: uni_dashboard.php'); exit;
}
$error = $_GET['error'] ?? '';
?>
<style>
    /* Стили только для формы входа ВУЗа (не затрагивают шапку) */
    .main-content.section-white {
        background: #f8f9fa;
        padding: 60px 0;
        min-height: calc(100vh - 120px);
        display: flex;
        align-items: center;
    }

    .container-card.profile-edit {
        max-width: 500px;
        width: 100%;
        margin: 0 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 40px;
        animation: fadeIn 0.4s ease-out;
    }

    .profile-edit h2 {
        text-align: center;
        color: #2d3748;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .profile-edit .form-group {
        margin-bottom: 1.5rem;
    }

    .profile-edit label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
    }

    .profile-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
        background-color: #f8fafc;
    }

    .profile-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        outline: none;
        background-color: #fff;
    }


    .error-message {
        background-color: #fff5f5;
        color: #e53e3e;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
        text-align: center;
        border: 1px solid #fed7d7;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Адаптивность */
    @media (max-width: 640px) {
        .container-card.profile-edit {
            padding: 2rem 1.5rem;
            margin: 0 1rem;
        }

        .profile-edit h2 {
            font-size: 1.5rem;
        }
    }
</style>
<section class="main-content section-white">
    <div class="container-card profile-edit">
        <h2>Вход для ВУЗа</h2>
        <?php if ($error): ?>
            <div class="error-message" style="color:#e63946; margin-bottom:15px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form action="uni_auth.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    class="profile-input"
                    placeholder="пример@университет.ру"
                >
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="profile-input"
                    placeholder="••••••••"
                >
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Войти
            </button>        </form>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
