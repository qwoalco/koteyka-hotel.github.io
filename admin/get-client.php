<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false]);
    exit;
}

try {
    // Получаем данные клиента
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch();
    
    if (!$client) {
        echo json_encode(['success' => false]);
        exit;
    }
    
    // Получаем питомцев клиента
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE client_id = ?");
    $stmt->execute([$id]);
    $pets = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'full_name' => $client['full_name'],
        'phone' => $client['phone'],
        'email' => $client['email'],
        'created_at' => date('d.m.Y', strtotime($client['created_at'])),
        'pets' => $pets
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}
?>