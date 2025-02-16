в папке my_project находится основной backend сайта, для использования: 
Установите XAMPP с сайта apachefriends.org (выберите Apache, MySQL, PHP).  
Распакуйте архив проекта в C:\xampp\htdocs\ваша_папка.  
Запустите XAMPP, включите Apache и MySQL.  
Откройте http://localhost/phpmyadmin → Создайте новую базу данных (например, my_db).  
В файле bd.php укажите: $link = mysqli_connect("localhost", "root", "", "my_db");  
Если есть файл dump.sql → В phpMyAdmin выберите вашу БД → Импорт → Загрузите dump.sql.  
Перейдите на сайт: http://localhost/ваша_папка.  

Ошибки:  
Если не подключается к БД → проверьте логин/пароль в bd.php  
Если файлы не найдены → проверьте пути (../bd.php должен вести к файлу)  
Для тестового пользователя в SQL:  
INSERT INTO users (login, password) VALUES ('test', '$2y$10$SxX...Wj0K') (пароль: test123).

Для того чтобы запустить проект, нужно открыть файл app.py запустить его и перейти по ссылке локального хоста (http://127.0.0.1:17091)