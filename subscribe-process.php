<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$email = trim($_POST['email']);

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['subscribe_error'] = 'Пожалуйста, введите корректный email';
    header('Location: blog.php');
    exit;
}

try {
    // Проверяем, существует ли уже подписчик
    $stmt = $pdo->prepare("SELECT id FROM subscribers WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $_SESSION['subscribe_error'] = 'Этот email уже подписан на рассылку';
    } else {
        $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->execute([$email]);
        $_SESSION['subscribe_success'] = true;
    }
} catch (Exception $e) {
    error_log("Subscribe error: " . $e->getMessage());
    $_SESSION['subscribe_error'] = 'Произошла ошибка. Пожалуйста, попробуйте позже.';
}

header('Location: blog.php');
exit;
?>