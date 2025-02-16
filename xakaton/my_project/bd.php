<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'memorybookdb'; // Убедитесь, что эта база существует

// Подключение к MySQL
$link = new mysqli($host, $user, $password, $dbname);

// Проверка соединения
if ($link->connect_error) {
    die("Ошибка подключения: " . $link->connect_error);
}

?>
