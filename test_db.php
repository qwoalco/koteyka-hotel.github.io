<?php
require_once 'config/db.php';

echo "✅ Подключение к базе данных 'cats-hotel' успешно!<br>";

// Проверяем наличие таблиц
$tables = ['clients', 'pets', 'rooms', 'services', 'bookings'];
foreach ($tables as $table) {
    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Таблица $table существует<br>";
    } else {
        echo "✗ Таблица $table не найдена<br>";
    }
}
?>