<?php
// edit_profile.php
require 'header.php';    // в нём подключаются session_start(), <head>, навигация и style.css
require 'db.php';

// Убираем session_start() отсюда, так как он уже есть в header.php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = (int)$_SESSION['user_id'];

// 1) Получаем профиль (если есть)
$stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();

// Если профиль не найден, создаем пустой массив
if (!$profile) {
    $profile = [
        'id' => null,
        'photo' => '',
        'surname' => '',
        'first_name' => '',
        'patronymic' => '',
        'university_id' => '',
        'other_university' => '',
        'graduation_year' => '',
        'study_format' => '',
        'education_level' => '',
        'about' => ''
    ];
}

// 2) Список вузов
$universities = $conn
    ->query("SELECT id, name FROM universities ORDER BY name")
    ->fetch_all(MYSQLI_ASSOC);

// 3) Список загруженных достижений - ИСПРАВЛЕНО: используем правильное имя таблицы
$achievements = [];
if (!empty($profile['id'])) {
    $a = $conn->prepare("SELECT id, file_path FROM achievements WHERE profile_id = ? ORDER BY created_at DESC");
    $a->bind_param("i", $profile['id']);
    $a->execute();
    $achievements = $a->get_result()->fetch_all(MYSQLI_ASSOC);
    $a->close();
}
?>
    <style>
        /* Основные стили формы */
        .profile-card {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-form {
            margin-top: 1.5rem;
        }

        /* Группы полей */
        .form-group {
            margin-bottom: 1.5rem;
        }

        /* Строки с колонками */
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .col-4 {
            flex: 1;
        }

        /* Элементы формы */
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #4a5568;
        }

        .profile-input,
        .profile-select,
        .profile-textarea,
        .profile-file-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
            background-color: #f8fafc;
            box-sizing: border-box;
        }

        .profile-input:focus,
        .profile-select:focus,
        .profile-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .profile-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .mt-1 {
            margin-top: 0.5rem;
        }

        /* Превью фото */
        .photo-preview {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 1rem;
            border: 3px solid #e2e8f0;
        }

        /* Кнопка сохранения */
        .btn-save {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: #5a67d8;
        }

        /* Список достижений */
        .achievement-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .achievement-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .achievement-list a {
            color: #667eea;
            text-decoration: none;
        }

        .achievement-list a:hover {
            text-decoration: underline;
        }

        .delete-link {
            color: #e53e3e;
            font-size: 1.5rem;
            line-height: 1;
            text-decoration: none;
            margin-left: 1rem;
        }

        .delete-link:hover {
            color: #c53030;
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 2rem;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .col-4 {
                width: 100%;
            }
        }
    </style>

    <section class="section-white">
        <div class="profile-card" style="margin-top: 6%;">
            <h2>Редактирование профиля</h2>

            <form action="save_profile.php" method="post" enctype="multipart/form-data" class="profile-form">
                <!-- Скрытое поле для ID -->
                <input type="hidden" name="profile_id" value="<?= intval($profile['id'] ?? 0) ?>">

                <!-- Фото -->
                <div class="form-group">
                    <label for="photo">Фото</label><br>
                    <?php if (!empty($profile['photo'])): ?>
                        <img src="<?= htmlspecialchars($profile['photo']) ?>" class="photo-preview" alt=""><br>
                    <?php endif; ?>
                    <input type="file" name="photo" id="photo" accept="image/*" class="profile-file-input">
                </div>

                <!-- ФИО -->
                <div class="form-row">
                    <div class="form-group col-4">
                        <label for="surname">Фамилия</label>
                        <input type="text" name="surname" id="surname" value="<?= htmlspecialchars($profile['surname'] ?? '') ?>" class="profile-input">
                    </div>
                    <div class="form-group col-4">
                        <label for="first_name">Имя</label>
                        <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>" class="profile-input">
                    </div>
                    <div class="form-group col-4">
                        <label for="patronymic">Отчество</label>
                        <input type="text" name="patronymic" id="patronymic" value="<?= htmlspecialchars($profile['patronymic'] ?? '') ?>" class="profile-input">
                    </div>
                </div>

                <!-- Вуз -->
                <div class="form-group">
                    <label for="university_id">Вуз</label>
                    <select name="university_id" id="university_id" class="profile-select">
                        <option value="">— Выберите вуз —</option>
                        <?php foreach ($universities as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= (($profile['university_id'] ?? '') == $u['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['name']) ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="other" <?= (empty($profile['university_id']) && !empty($profile['other_university'])) ? 'selected' : '' ?>>
                            Другой вуз
                        </option>
                    </select>
                    <input
                            type="text"
                            name="other_university"
                            id="other_university"
                            placeholder="Укажите свой вуз"
                            class="profile-input mt-1"
                            style="display: <?= (empty($profile['university_id']) && !empty($profile['other_university'])) ? 'block' : 'none' ?>;"
                            value="<?= htmlspecialchars($profile['other_university'] ?? '') ?>"
                    >
                </div>

                <!-- Год, формат, уровень -->
                <div class="form-row">
                    <div class="form-group col-4">
                        <label for="graduation_year">Год окончания</label>
                        <input
                                type="number"
                                name="graduation_year"
                                id="graduation_year"
                                min="1900"
                                max="2050"
                                value="<?= htmlspecialchars($profile['graduation_year'] ?? '') ?>"
                                class="profile-input"
                        >
                    </div>
                    <div class="form-group col-4">
                        <label for="study_format">Формат обучения</label>
                        <select name="study_format" id="study_format" class="profile-select">
                            <option value="">— Выберите формат —</option>
                            <?php foreach (['очный', 'заочный', 'очно-заочный'] as $fmt): ?>
                                <option value="<?= $fmt ?>" <?= (($profile['study_format'] ?? '') == $fmt) ? 'selected' : '' ?>>
                                    <?= ucfirst($fmt) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <label for="education_level">Уровень обучения</label>
                        <select name="education_level" id="education_level" class="profile-select">
                            <option value="">— Выберите уровень —</option>
                            <?php foreach (['бакалавриат', 'магистратура', 'аспирантура', 'интернатура'] as $lvl): ?>
                                <option value="<?= $lvl ?>" <?= (($profile['education_level'] ?? '') == $lvl) ? 'selected' : '' ?>>
                                    <?= ucfirst($lvl) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- О себе -->
                <div class="form-group">
                    <label for="about">О себе</label>
                    <textarea name="about" id="about" rows="4" class="profile-textarea"><?= htmlspecialchars($profile['about'] ?? '') ?></textarea>
                </div>

                <!-- Добавление достижений -->
                <div class="form-group">
                    <label for="achievements">Добавить достижения</label>
                    <input
                            type="file"
                            name="achievements[]"
                            id="achievements"
                            multiple
                            accept=".pdf,.doc,.docx,image/*"
                            class="profile-file-input"
                    >
                </div>

                <!-- Существующие достижения -->
                <?php if (count($achievements)): ?>
                    <div class="form-group">
                        <label>Загруженные достижения</label>
                        <ul class="achievement-list">
                            <?php foreach ($achievements as $a): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($a['file_path']) ?>" target="_blank">
                                        <?= htmlspecialchars(basename($a['file_path'])) ?>
                                    </a>
                                    <a
                                            href="delete_achievement.php?ach_id=<?= $a['id'] ?>"
                                            class="delete-link"
                                            onclick="return confirm('Удалить этот файл?');"
                                            title="Удалить"
                                    >
                                        &times;
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Кнопка сохранения -->
                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn-save">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Скрипт показа/скрытия поля "Другой вуз" -->
    <script>
        document.getElementById('university_id').addEventListener('change', function() {
            const other = document.getElementById('other_university');
            if (this.value === 'other') {
                other.style.display = 'block';
                other.required = true;
            } else {
                other.style.display = 'none';
                other.required = false;
            }
        });
    </script>

<?php require 'includes/footer.php'; ?>