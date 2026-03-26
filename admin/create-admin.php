<?php
require_once 'config/db.php';

$email = 'admin@koteyka.ru';
$password = 'admin123'; // Пароль, который хотите установить
$full_name = 'Администратор';
$phone = '+79990001122';

$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Проверяем, существует ли уже админ
$stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
$stmt->execute([$email]);
$exists = $stmt->fetch();

if ($exists) {
    // Обновляем существующего
    $stmt = $pdo->prepare("UPDATE clients SET password_hash = ?, role = 'admin', is_admin = 1 WHERE email = ?");
    $stmt->execute([$password_hash, $email]);
    echo "✅ Администратор обновлен!<br>";
} else {
    // Создаем нового
    $stmt = $pdo->prepare("INSERT INTO clients (full_name, phone, email, password_hash, role, is_admin) VALUES (?, ?, ?, ?, 'admin', 1)");
    $stmt->execute([$full_name, $phone, $email, $password_hash]);
    echo "✅ Администратор создан!<br>";
}

echo "<br>📧 Email: $email<br>";
echo "🔑 Пароль: $password<br>";
echo "<br><a href='admin/login.php'>Перейти к входу в админ-панель</a>";
?>