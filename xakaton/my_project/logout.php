<?php
session_start();
session_destroy();
header("Location: form.html"); // Перенаправление на страницу входа
exit();
?>