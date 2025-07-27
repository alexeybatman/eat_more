<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['uni_logged_in']) || !$_SESSION['uni_logged_in']) {
    header('Location: uni_login.php');
    exit;
}

$university_id = $_SESSION['uni_university_id'] ?? 0;
$message = '';
$error_message = '';
$redirect_success = false;

try {
    // Подключение к базе данных
    require_once 'config/database.php';

    if (!isset($pdo)) {
        $host = 'localhost';
        $dbname = 'exchange_platform';
        $username = 'root';
        $db_password = '';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Получаем текущие данные университета
    $stmt = $pdo->prepare("SELECT * FROM universities WHERE id = ?");
    $stmt->execute([$university_id]);
    $university = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$university) {
        $error_message = "Университет не найден";
    }

    // Обработка формы
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $logo_url = trim($_POST['logo_url'] ?? '');

        // Валидация
        if (empty($name)) {
            $error_message = "Название университета обязательно";
        } elseif (empty($country)) {
            $error_message = "Страна обязательна";
        } else {
            // Проверяем URL логотипа
            if (!empty($logo_url) && !filter_var($logo_url, FILTER_VALIDATE_URL)) {
                $error_message = "Некорректный URL логотипа";
            } else {
                // Обновляем данные
                $update_stmt = $pdo->prepare("
                    UPDATE universities 
                    SET name = ?, country = ?, description = ?, logo_url = ?, updated_at = NOW()
                    WHERE id = ?
                ");

                if ($update_stmt->execute([$name, $country, $description, $logo_url, $university_id])) {
                    // Обновляем сессию
                    $_SESSION['uni_university_name'] = $name;
                    $_SESSION['uni_country'] = $country;
                    $_SESSION['uni_logo_url'] = $logo_url;

                    // Сохраняем сообщение об успехе в сессии для показа на dashboard
                    $_SESSION['success_message'] = "Профиль успешно обновлен";

                    // Устанавливаем флаг для JavaScript редиректа
                    $redirect_success = true;
                } else {
                    $error_message = "Ошибка при обновлении профиля";
                }
            }
        }
    }

} catch (PDOException $e) {
    error_log("Database error in uni_edit_profile.php: " . $e->getMessage());
    $error_message = "Ошибка соединения с базой данных";
}

require 'header.php';
?>

    <style>
        .edit-profile-container {
            max-width: 800px;
            margin: 5% auto;
            padding: 2rem;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #2d3748;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .logo-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .logo-preview img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e2e8f0;
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f7fafc;
            border: 3px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a0aec0;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #f0fff4;
            border: 1px solid #9ae6b4;
            color: #276749;
        }

        .alert-danger {
            background: #fed7d7;
            border: 1px solid #feb2b2;
            color: #c53030;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .help-text {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .edit-profile-container {
                padding: 1rem;
            }

            .button-group {
                flex-direction: column;
            }

            .logo-preview {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        .user-controls{
            display: none;
        }
    </style>

    <div class="edit-profile-container">
        <div class="page-header">
            <h1><i class="fas fa-edit"></i> Редактирование профиля</h1>
        </div>

        <div class="form-container">
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <?php if ($university): ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-university"></i> Название университета *
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="<?= htmlspecialchars($university['name']) ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="country">
                            <i class="fas fa-map-marker-alt"></i> Страна *
                        </label>
                        <input type="text"
                               id="country"
                               name="country"
                               value="<?= htmlspecialchars($university['country']) ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-info-circle"></i> Описание университета
                        </label>
                        <textarea id="description"
                                  name="description"
                                  placeholder="Расскажите о своем университете, его особенностях, достижениях..."><?= htmlspecialchars($university['description'] ?? '') ?></textarea>
                        <div class="help-text">
                            Опишите преимущества вашего университета, доступные программы обмена, условия обучения
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="logo_url">
                            <i class="fas fa-image"></i> URL логотипа
                        </label>
                        <input type="url"
                               id="logo_url"
                               name="logo_url"
                               value="<?= htmlspecialchars($university['logo_url'] ?? '') ?>"
                               placeholder="https://example.com/logo.png">
                        <div class="help-text">
                            Ссылка на изображение логотипа университета (рекомендуемый размер: 200x200px)
                        </div>

                        <div class="logo-preview">
                            <?php if (!empty($university['logo_url'])): ?>
                                <img src="<?= htmlspecialchars($university['logo_url']) ?>"
                                     alt="Логотип"
                                     id="logo-preview-img">
                            <?php else: ?>
                                <div class="logo-placeholder" id="logo-placeholder">
                                    <i class="fas fa-university"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <strong>Предварительный просмотр</strong>
                                <div class="help-text">Логотип будет отображаться в профиле университета</div>
                            </div>
                        </div>
                    </div>

                    <div class="button-group">
                        <a href="uni_dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Сохранить изменения
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Предварительный просмотр логотипа
        document.getElementById('logo_url').addEventListener('input', function() {
            const url = this.value;
            const previewImg = document.getElementById('logo-preview-img');
            const placeholder = document.getElementById('logo-placeholder');

            if (url && isValidUrl(url)) {
                if (previewImg) {
                    previewImg.src = url;
                } else {
                    // Создаем новый элемент изображения
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Логотип';
                    img.id = 'logo-preview-img';
                    img.style.width = '80px';
                    img.style.height = '80px';
                    img.style.borderRadius = '50%';
                    img.style.objectFit = 'cover';
                    img.style.border = '3px solid #e2e8f0';

                    if (placeholder) {
                        placeholder.parentNode.replaceChild(img, placeholder);
                    }
                }
            } else {
                if (previewImg && placeholder) {
                    previewImg.parentNode.replaceChild(placeholder, previewImg);
                }
            }
        });

        function isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }

        // Редирект после успешного обновления
        <?php if ($redirect_success): ?>
        window.location.href = 'uni_dashboard.php';
        <?php endif; ?>
    </script>

<?php require 'includes/footer.php'; ?>