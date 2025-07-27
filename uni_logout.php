<?php
session_start();

// Очищаем все данные сессии университета
unset($_SESSION['uni_user_id']);
unset($_SESSION['uni_university_id']);
unset($_SESSION['uni_email']);
unset($_SESSION['uni_university_name']);
unset($_SESSION['uni_country']);
unset($_SESSION['uni_logo_url']);
unset($_SESSION['uni_logged_in']);

// Если в сессии остались только данные университета, уничтожаем сессию полностью
$uni_keys = ['uni_user_id', 'uni_university_id', 'uni_email', 'uni_university_name', 'uni_country', 'uni_logo_url', 'uni_logged_in'];
$has_other_data = false;

foreach ($_SESSION as $key => $value) {
    if (!in_array($key, $uni_keys)) {
        $has_other_data = true;
        break;
    }
}

if (!$has_other_data) {
    session_destroy();
}

// Перенаправляем на страницу входа
header('Location: uni_login.php');
exit;
?>