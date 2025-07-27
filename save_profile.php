<?php
// save_profile.php

// Включаем буферизацию вывода в самом начале
ob_start();

// Отключаем вывод ошибок на страницу (для продакшена)
// error_reporting(0);

require 'header.php';
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Получаем данные из формы
$profile_id = (int)($_POST['profile_id'] ?? 0);
$surname = trim($_POST['surname'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$patronymic = trim($_POST['patronymic'] ?? '');
$university_id = ($_POST['university_id'] === 'other' || empty($_POST['university_id'])) ? null : (int)$_POST['university_id'];
$other_university = trim($_POST['other_university'] ?? '');
$graduation_year = !empty($_POST['graduation_year']) ? (int)$_POST['graduation_year'] : null;
$study_format = trim($_POST['study_format'] ?? '');
$education_level = trim($_POST['education_level'] ?? '');
$about = trim($_POST['about'] ?? '');

// Если выбран "другой вуз", обнуляем university_id
if ($_POST['university_id'] === 'other') {
    $university_id = null;
}

// Обработка загрузки фото
$photo_path = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/photos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($file_extension, $allowed_extensions)) {
        $new_filename = $user_id . '_' . time() . '.' . $file_extension;
        $photo_path = $upload_dir . $new_filename;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
            $photo_path = '';
        }
    }
}

// Начинаем транзакцию
$conn->begin_transaction();

try {
    if ($profile_id > 0) {
        // Обновляем существующий профиль
        $sql = "UPDATE user_profiles SET 
                surname = ?, 
                first_name = ?, 
                patronymic = ?, 
                university_id = ?, 
                other_university = ?, 
                graduation_year = ?, 
                study_format = ?, 
                education_level = ?, 
                about = ?";

        $params = [$surname, $first_name, $patronymic, $university_id, $other_university,
            $graduation_year, $study_format, $education_level, $about];
        $types = "sssisssss";

        // Добавляем фото если загружено
        if (!empty($photo_path)) {
            $sql .= ", photo = ?";
            $params[] = $photo_path;
            $types .= "s";
        }

        $sql .= " WHERE id = ? AND user_id = ?";
        $params[] = $profile_id;
        $params[] = $user_id;
        $types .= "ii";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();

    } else {
        // Создаем новый профиль
        $sql = "INSERT INTO user_profiles 
                (user_id, surname, first_name, patronymic, university_id, other_university, 
                 graduation_year, study_format, education_level, about, photo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssissssss",
            $user_id, $surname, $first_name, $patronymic, $university_id,
            $other_university, $graduation_year, $study_format, $education_level,
            $about, $photo_path
        );
        $stmt->execute();
        $profile_id = $conn->insert_id;
        $stmt->close();
    }

    // Обработка загрузки достижений
    if (isset($_FILES['achievements']) && is_array($_FILES['achievements']['tmp_name'])) {
        $upload_dir = 'uploads/achievements/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'];

        foreach ($_FILES['achievements']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['achievements']['error'][$key] === UPLOAD_ERR_OK) {
                $original_name = $_FILES['achievements']['name'][$key];
                $file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

                if (in_array($file_extension, $allowed_extensions)) {
                    $new_filename = $user_id . '_' . time() . '_' . $key . '.' . $file_extension;
                    $file_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($tmp_name, $file_path)) {
                        // Сохраняем информацию о файле в БД
                        $stmt = $conn->prepare("INSERT INTO achievements (profile_id, file_path) VALUES (?, ?)");
                        $stmt->bind_param("is", $profile_id, $file_path);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }
    }

    // Подтверждаем транзакцию
    $conn->commit();

    // Очищаем буфер вывода перед редиректом
    ob_clean();

    // Перенаправляем на страницу профиля
    header('Location: profile.php?success=1');
    exit;

} catch (Exception $e) {
    // Откатываем транзакцию в случае ошибки
    $conn->rollback();

    // Очищаем буфер вывода
    ob_clean();

    // Логируем ошибку (лучше в файл, а не выводить пользователю)
    error_log("Ошибка при сохранении профиля: " . $e->getMessage());

    // Перенаправляем на страницу с ошибкой
    header('Location: profile.php?error=1');
    exit;
}

// Завершаем буферизацию (на всякий случай)
ob_end_flush();
?>