<?php
// Конфигурация базы данных
$servername = "MySql-8.0";
$dbname = 'cat-hotel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Настройка сессии
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Возвращаем объект PDO
return $pdo;
?>