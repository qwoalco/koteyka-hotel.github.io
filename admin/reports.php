<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Получаем данные для отчетов
$stmt = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
           COUNT(*) as bookings_count,
           SUM(total_price) as revenue
    FROM bookings 
    WHERE status = 'completed'
    GROUP BY month
    ORDER BY month DESC
    LIMIT 12
");
$monthly_stats = $stmt->fetchAll();

// Популярные номера
$stmt = $pdo->query("
    SELECT r.title, COUNT(b.id) as bookings_count
    FROM rooms r
    LEFT JOIN bookings b ON r.id = b.room_id
    GROUP BY r.id
    ORDER BY bookings_count DESC
");
$popular_rooms = $stmt->fetchAll();

// Популярные услуги
$stmt = $pdo->query("
    SELECT s.name, COUNT(bs.id) as times_ordered
    FROM services s
    LEFT JOIN booking_services bs ON s.id = bs.service_id
    GROUP BY s.id
    ORDER BY times_ordered DESC
");
$popular_services = $stmt->fetchAll();

// Клиенты с наибольшим количеством бронирований
$stmt = $pdo->query("
    SELECT c.full_name, COUNT(b.id) as bookings_count, SUM(b.total_price) as total_spent
    FROM clients c
    JOIN bookings b ON c.id = b.client_id
    GROUP BY c.id
    ORDER BY total_spent DESC
    LIMIT 10
");
$top_clients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчеты - Админ-панель</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Lato', sans-serif;
            background: #f5f5f5;
        }
        .admin-header {
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            color: #F7931E;
            font-size: 24px;
        }
        .admin-nav {
            display: flex;
            gap: 20px;
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #ddd;
        }
        .admin-nav a {
            color: #333;
            text-decoration: none;
        }
        .admin-nav a:hover {
            color: #F7931E;
        }
        .admin-container {
            padding: 30px;
        }
        .report-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .report-section h2 {
            margin-bottom: 20px;
            color: #F7931E;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f9f9f9;
        }
        .btn-print {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>Котейка - Админ-панель</h1>
        <div>
            Привет, <?php echo htmlspecialchars($_SESSION['admin_name']); ?> | 
            <a href="logout.php" style="color: #F7931E;">Выйти</a>
        </div>
    </div>
    
    <div class="admin-nav">
        <a href="index.php">Главная</a>
        <a href="bookings.php">Бронирования</a>
        <a href="rooms.php">Номера</a>
        <a href="services.php">Услуги</a>
        <a href="blog.php">Блог</a>
        <a href="clients.php">Клиенты</a>
        <a href="reports.php">Отчеты</a>
    </div>
    
    <div class="admin-container">
        <button class="btn-print" onclick="window.print()">Печать отчетов</button>
        
        <div class="report-section">
            <h2>📊 Месячная статистика</h2>
            <table>
                <thead>
                    <tr>
                        <th>Месяц</th>
                        <th>Кол-во бронирований</th>
                        <th>Выручка</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthly_stats as $stat): ?>
                    <tr>
                        <td><?php echo date('F Y', strtotime($stat['month'] . '-01')); ?></td>
                        <td><?php echo $stat['bookings_count']; ?></td>
                        <td><?php echo number_format($stat['revenue'], 0, '', ' '); ?> ₽</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="report-section">
            <h2>🏠 Популярные номера</h2>
            <table>
                <thead>
                    <tr>
                        <th>Номер</th>
                        <th>Кол-во бронирований</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($popular_rooms as $room): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['title']); ?></td>
                        <td><?php echo $room['bookings_count']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="report-section">
            <h2>⭐ Популярные услуги</h2>
            <table>
                <thead>
                    <tr>
                        <th>Услуга</th>
                        <th>Кол-во заказов</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($popular_services as $service): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($service['name']); ?></td>
                        <td><?php echo $service['times_ordered']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="report-section">
            <h2>👥 Топ-10 клиентов по суммарным тратам</h2>
            <table>
                <thead>
                    <tr>
                        <th>Клиент</th>
                        <th>Кол-во бронирований</th>
                        <th>Всего потрачено</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_clients as $client): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($client['full_name']); ?></td>
                        <td><?php echo $client['bookings_count']; ?></td>
                        <td><?php echo number_format($client['total_spent'], 0, '', ' '); ?> ₽</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>