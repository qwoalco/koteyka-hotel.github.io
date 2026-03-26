<?php
session_start();

// Очищаем все сессии
$_SESSION = array();

// Удаляем сессионные куки
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Уничтожаем сессию
session_destroy();

// Перенаправляем на страницу входа
header('Location: login.php');
exit;