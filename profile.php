<?php
// profile.php
require 'header.php';    // подключает <head>, навигацию и ваш style.css
require 'db.php';

// Убираем session_start() отсюда, так как он уже есть в header.php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = (int)$_SESSION['user_id'];

// 1) Профиль + название вуза
$stmt = $conn->prepare("
  SELECT p.*, u.name AS uni_name
  FROM user_profiles p
  LEFT JOIN universities u ON u.id = p.university_id
  WHERE p.user_id = ?
");
$stmt->bind_param('i', $user_id);
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
        'uni_name' => '',
        'other_university' => '',
        'graduation_year' => '',
        'study_format' => '',
        'education_level' => '',
        'about' => ''
    ];
}

// 2) Список достижений - ИСПРАВЛЕНО: используем правильное имя таблицы
$achievements = [];
if (!empty($profile['id'])) {
    $q = $conn->prepare("
      SELECT id, file_path
      FROM achievements
      WHERE profile_id = ?
      ORDER BY created_at DESC
    ");
    $q->bind_param('i', $profile['id']);
    $q->execute();
    $achievements = $q->get_result()->fetch_all(MYSQLI_ASSOC);
    $q->close();
}
?>
    <style>
        /* Основные стили секции */
        .section-white {
            background-color: #ffffff;
            padding: 2rem 0;
            min-height: calc(100vh - 120px);
        }

        /* Карточка профиля */
        .profile-card {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }

        /* Фото профиля */
        .photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #e9ecef;
            display: block;
            margin: 0 auto 1.5rem;
        }

        /* Заголовок с ФИО */
        h2 {
            text-align: center;
            color: #212529;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        /* Список характеристик */
        dl {
            display: grid;
            grid-template-columns: max-content 1fr;
            gap: 0.8rem 1.5rem;
            margin-bottom: 2rem;
        }

        dt {
            font-weight: 600;
            color: #495057;
        }

        dd {
            margin: 0;
            color: #6c757d;
        }

        /* Подзаголовки */
        h3 {
            color: #212529;
            font-size: 1.3rem;
            margin: 1.8rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        /* Текст "О себе" */
        p {
            color: #495057;
            line-height: 1.6;
        }

        /* Список достижений */
        .achievement-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0 2rem;
        }

        .achievement-list li {
            margin-bottom: 0.8rem;
            padding: 0.8rem;
            background-color: #f1f3f5;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .achievement-list li:hover {
            background-color: #e9ecef;
        }

        .achievement-list a {
            color: #0d6efd;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .achievement-list a:hover {
            text-decoration: underline;
        }

        .achievement-list a::before {
            content: "📄";
            margin-right: 0.5rem;
        }

        /* Кнопка редактирования */
        .btn-save {
            margin-top: 2%;
            display: inline-block;
            padding: 0.7rem 1.5rem;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-save:hover {
            background-color: #0b5ed7;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .profile-card {
                padding: 1.5rem;
            }

            dl {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            dt {
                font-weight: 600;
                margin-top: 0.5rem;
            }
        }

        /* Сообщение о пустом профиле */
        .empty-profile {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .empty-profile h3 {
            color: #495057;
            margin-bottom: 1rem;
        }
    </style>

    <section class="section-white">
        <div class="profile-card" style="margin-top: 6%;margin-bottom: 5%;">
            <?php if (empty($profile['surname']) && empty($profile['first_name'])): ?>
                <!-- Если профиль пустой -->
                <div class="empty-profile">
                    <h3>Профиль не заполнен</h3>
                    <p>Для начала заполните свой профиль</p>
                    <a href="edit_profile.php" class="btn-save">Заполнить профиль</a>
                </div>
            <?php else: ?>
                <!-- Если профиль заполнен -->
                <?php if (!empty($profile['photo'])): ?>
                    <img src="<?= htmlspecialchars($profile['photo']) ?>" alt="Фото" class="photo">
                <?php endif; ?>

                <h2>
                    <?= htmlspecialchars(trim(
                        ($profile['surname'] ?? '') . ' ' . ($profile['first_name'] ?? '') . ' ' . ($profile['patronymic'] ?? '')
                    )) ?>
                </h2>

                <dl>
                    <?php if (!empty($profile['uni_name']) || !empty($profile['other_university'])): ?>
                        <dt>Вуз:</dt>
                        <dd><?= htmlspecialchars(($profile['uni_name'] ?? '') ?: ($profile['other_university'] ?? '')) ?></dd>
                    <?php endif; ?>

                    <?php if (!empty($profile['graduation_year'])): ?>
                        <dt>Год окончания:</dt>
                        <dd><?= htmlspecialchars($profile['graduation_year']) ?></dd>
                    <?php endif; ?>

                    <?php if (!empty($profile['study_format'])): ?>
                        <dt>Формат обучения:</dt>
                        <dd><?= htmlspecialchars($profile['study_format']) ?></dd>
                    <?php endif; ?>

                    <?php if (!empty($profile['education_level'])): ?>
                        <dt>Уровень обучения:</dt>
                        <dd><?= htmlspecialchars($profile['education_level']) ?></dd>
                    <?php endif; ?>
                </dl>

                <?php if (!empty($profile['about'])): ?>
                    <h3>О себе</h3>
                    <p><?= nl2br(htmlspecialchars($profile['about'])) ?></p>
                <?php endif; ?>

                <!-- Блок достижений -->
                <?php if (count($achievements)): ?>
                    <h3>Достижения</h3>
                    <ul class="achievement-list">
                        <?php foreach ($achievements as $achievement): ?>
                            <li>
                                <a href="<?= htmlspecialchars($achievement['file_path']) ?>" target="_blank">
                                    <?= htmlspecialchars(basename($achievement['file_path'])) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <!-- Кнопка редактирования -->
                <div style="text-align: center;">
                    <a href="edit_profile.php" class="btn-save">Редактировать профиль</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php require 'includes/footer.php'; ?>