<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user'])) {
    header('Location: form.html'); // Перенаправляем на страницу входа
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Книга памяти Оренбургской области</title>
    <link rel="stylesheet" href="/static/styles/style_xak.css">
    <link rel="stylesheet" href="styles/style_xak.css"> <!-- Убрали Flask-синтаксис -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="#">Книга памяти</a>
            </div>
            <nav class="desktop-menu">
                <ul>
                    <li><a href="#">Главная</a></li>
                    <li><a href="map.html">Интерактивная карта</a></li>
                    <li><a href="form.html">Регистрация</a></li>
                    <li><a href="logout.php">Выйти</a></li> <!-- Добавил кнопку выхода -->
                </ul>
            </nav>
        </div>
    </header>

    <main class="welcome">
        <div class="container">
            <div class="wrapper">
                <div class="textwelcome">
                    <h1>Книга Памяти Оренбургской области</h1>
                    <h2>Увековечиваем подвиг защитников Отечества</h2>
                </div>
            </div>
        </div>
    </main>

    <section class="card-container container">
        <a href="cards/card1.html"> 
            <div class="card">
                <img src="image/osnova.jpg" alt="Герой 1">
                <h2>Иван Иванов</h2>
                <p>Звание: Герой Советского Союза</p>
                <p>Описание: Принял участие в нескольких ключевых сражениях, проявив выдающееся мужество.</p>
            </div>
        </a>
    </section>

    <script>
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
        });
    </script>
    <script src="script_xak.js"></script>
</body>
<footer>
    <p> Хакатон 2025</p>
</footer>
</html>
