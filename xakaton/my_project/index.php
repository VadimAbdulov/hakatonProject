<?php
include('../bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST["login"];
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];

    if (!$login || !$password || !$password_confirm) {
        die("Вы не ввели все данные!!!");
    }

    if ($password !== $password_confirm) {
        die("Пароли не совпадают!");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (login, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ss", $login, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        echo "Вы успешно создали пользователя";
    } else {
        die("Ошибка при регистрации: " . mysqli_error($link));
    }
}
?>