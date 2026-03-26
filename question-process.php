<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$question = trim($_POST['question']);

if (empty($name) || empty($phone) || empty($question)) {
    $_SESSION['question_error'] = 'Пожалуйста, заполните все поля';
    header('Location: rules.php');
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO questions (name, phone, question) VALUES (?, ?, ?)");
    $stmt->execute([$name, $phone, $question]);
    
    $_SESSION['question_success'] = true;
} catch (Exception $e) {
    error_log("Question error: " . $e->getMessage());
    $_SESSION['question_error'] = 'Произошла ошибка. Пожалуйста, попробуйте позже.';
}

header('Location: rules.php');
exit;
?>