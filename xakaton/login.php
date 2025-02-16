<?php
session_start();
include('../bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];

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
        
        if ($password === $user['password']) {  // Простой вариант, лучше использовать хеширование!
            $_SESSION["user"] = $user["login"];
            header("Location: profile.php"); // Перенаправление в профиль
            exit;
        } else {
            echo "Неверный пароль!";
        }
    } else {
        echo "Пользователь не найден!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ url_for('static', filename='form.css') }}">
    <title>Вход</title>
</head>
<body>
    <div class="wrapper">
        <form id="loginForm">
            <h1>Войти</h1>
            <div class="input-box">
                <input type="text" placeholder="Введите имя" id="username" required>
                <i class='bx bx-user'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Введите пароль" id="password" required>
                <i class='bx bx-lock-alt'></i>
            </div>
            
            <button type="submit" class="btn">Войти</button>

            <div class="HaveAkk">
                <p>У вас нет аккаунта?</p> <br><a href="{{ url_for('form2') }}">Зарегистрироваться</a>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Предотвратить перезагрузку страницы

            // Получаем значения логина и пароля
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            // Правильные логин и пароль
            const correctUsername = "admin";
            const correctPassword = "admin";

            // Проверка логина и пароля
            if (username === correctUsername && password === correctPassword) {
                // Перенаправление на страницу администратора
                window.location.href ="{{ url_for('admin') }}"; // Замените 'admin.html' на нужную вам страницу
            } else {
                alert("Неправильный логин или пароль");
            }
        });
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');


*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Popins", sans-serif;
}
body{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: rgb(38, 39, 35);
    background-image: url('./image/back6.jpg');
    background-size: cover;
    background-position: center;

}
.wrapper{
    background: transparent;
    width: 420px;
    /* background-color: purple; */
    color: aliceblue;
    border-radius: 20px;
    padding: 30px 40px ;
}
.wrapper h1 {
    font-size: 36px;
    text-align: center;
}
.wrapper .input-box{
    width: 100%;
    height: 50px;
    margin: 30px 0;
    position: relative;

}
.input-box input{
    width: 100%;
    height: 100%;
    background-color: transparent;
    border: none;
    outline: none;
    border: 2px solid rgb(255, 255, 255, .2);
    border-radius: 40px;
    font-size: 16px;
    color: #fff;
    padding: 20px 45px 20px 20px ;  
}
.input-box input::placeholder{
    color: #fff;

}
.input-box i{
    position: absolute;
    right: 20px;
    transform: translateY(50%);
    font-size: 20px;

}
.btn{
height: 45px;
width: 100%;
background-color: #fff;
border: none;
outline: none;
border-radius: 40px;
box-shadow: 0 0 10px rgb(0, 0, 0, .1);
cursor: pointer;
font-size: 16px;
color: #333;
font-weight: 600;
}
.HaveAkk{
    font-size: 14.5px;
    text-align: center;
    margin-top: 20px;
}
.HaveAkk p{
    color: #fff;
    text-decoration: none;
    font-weight: 300;
}
.HaveAkk a{
    color: #fff;
    text-decoration: none;
    font-weight: 600;
}

.HaveAkk a:hover{
    color: blue;


}
    </style>
</body>
</html>
