<?php
// delete_achievement.php
require 'db.php';
session_start();

// 1) Получаем ID из GET или POST
$ach_id = null;
if (!empty($_REQUEST['ach_id']) && is_numeric($_REQUEST['ach_id'])) {
    $ach_id = (int)$_REQUEST['ach_id'];
}

// 2) Если нет ID — выходим (JSON или редирект)
if (!$ach_id) {
    // Если запрос из AJAX, вернём JSON
    if (
        !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Не указан ID достижения']);
        exit;
    }
    // иначе — вернём на форму с флешкой
    $_SESSION['flash_error'] = 'Не указан ID достижения';
    header('Location: edit_profile.php');
    exit;
}

// 3) Достаём путь к файлу
$stmt = $conn->prepare("SELECT file_path FROM user_achievements WHERE id = ?");
$stmt->bind_param('i', $ach_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($res && file_exists($res['file_path'])) {
    @unlink($res['file_path']);
}

// 4) Удаляем из БД
$stmt = $conn->prepare("DELETE FROM user_achievements WHERE id = ?");
$stmt->bind_param('i', $ach_id);
$stmt->execute();
$stmt->close();

// 5) Ответ
if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

// для обычного перехода
header('Location: edit_profile.php');
exit;
