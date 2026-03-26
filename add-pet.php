<?php
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $breed = trim($_POST['breed'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $medical_notes = trim($_POST['medical_notes'] ?? '');
    $client_id = $_SESSION['user_id'];
    
    if (empty($name)) {
        $_SESSION['error'] = 'Введите имя питомца';
    } else {
        $stmt = $pdo->prepare("INSERT INTO pets (client_id, name, breed, age, medical_notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$client_id, $name, $breed, $age, $medical_notes]);
        $_SESSION['success'] = 'Питомец добавлен';
    }
    
    header('Location: profile.php');
    exit;
}