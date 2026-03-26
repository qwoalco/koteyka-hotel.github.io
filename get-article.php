<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false]);
    exit;
}

try {
    // Увеличиваем счетчик просмотров
    $stmt = $pdo->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?");
    $stmt->execute([$id]);
    
    $stmt = $pdo->prepare("SELECT title, content, image_url, created_date FROM blog_posts WHERE id = ? AND is_published = 1");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    
    if ($post) {
        echo json_encode([
            'success' => true,
            'title' => $post['title'],
            'content' => $post['content'],
            'image_url' => $post['image_url'],
            'created_date' => date('d F Y', strtotime($post['created_date']))
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}
?>