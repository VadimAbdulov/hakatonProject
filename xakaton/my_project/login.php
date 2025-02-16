<?php
session_start();
include('../bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST["login"]);
    $password = trim($_POST["password"]);

    if (!$login || !$password) {
        echo "Заполните все поля!";
        exit;
    }

    $sql = "SELECT * FROM users WHERE login = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION["user"] = $user["login"];
            header("Location: profile.php");
            exit;
        } else {
            echo "Неверный пароль!";
        }
    } else {
        echo "Пользователь не найден!";
    }
}
?>