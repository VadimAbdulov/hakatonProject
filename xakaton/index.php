<?php
include('bd.php');

if (isset($_POST["ok"])) {
    $login = $_POST["login"];
    $password = $_POST["password"];

    if(!$login || !$password){
        $error = "Вы не ввели все данные!!!";
        exit;
    }

    if(!$error){
        $query = "INSERT INTO users (login, password) VALUES ('$login','$password')";
        mysqli_query($link, $query);
        echo "Вы успешно создали пользователя";
    }
    else{
        echo $error, exit;
    }
}


?>

<html>
<head>
    <title>Главная страница</title>
</head>
<body>
    <form method="POST">
        <p>Логин: <input type="text" name="login"></p>
        <p>Пароль: <input type="password" name="password"></p>
        <p><input type="submit" name="ok" value="зарегистрироваться"></p>
    </form>
</body>
</html>
