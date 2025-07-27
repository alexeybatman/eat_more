<?php
$host = 'localhost';
$db = 'exchange_platform';
$user = 'root'; // или другой пользователь
$pass = '';     // или твой пароль

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
