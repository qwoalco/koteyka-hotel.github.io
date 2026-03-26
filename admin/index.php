<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Получаем статистику
$stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'new'");
$new_bookings = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'");
$active_bookings = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM questions WHERE is_answered = FALSE");
$new_questions = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT SUM(total_price) FROM bookings WHERE status = 'completed' AND MONTH(created_at) = MONTH(CURRENT_DATE())");
$month_revenue = $stmt->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Котейка</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../access/css/admin.css">
</head>
<body>
    <div class="admin-header">
        <h1>Котейка - Админ-панель</h1>
        <div>
            Привет, <?php echo htmlspecialchars($_SESSION['admin_name']); ?> | 
            <a href="logout.php">Выйти</a>
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
        <a href="faq.php">FAQ</a>
    </div>
    
    <div class="admin-container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Новые бронирования</h3>
                <div class="value"><?php echo $new_bookings; ?></div>
            </div>
            <div class="stat-card">
                <h3>Активные проживания</h3>
                <div class="value"><?php echo $active_bookings; ?></div>
            </div>
            <div class="stat-card">
                <h3>Новые вопросы</h3>
                <div class="value"><?php echo $new_questions; ?></div>
            </div>
            <div class="stat-card">
                <h3>Выручка за месяц</h3>
                <div class="value"><?php echo number_format($month_revenue, 0, '', ' '); ?> ₽</div>
            </div>
        </div>
        
        <div class="recent-bookings">
            <h2>Последние бронирования</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Клиент</th>
                            <th>Питомец</th>
                            <th>Номер</th>
                            <th>Даты</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("
                            SELECT b.*, c.full_name, p.name as pet_name, r.title as room_title
                            FROM bookings b
                            JOIN clients c ON b.client_id = c.id
                            JOIN pets p ON b.pet_id = p.id
                            JOIN rooms r ON b.room_id = r.id
                            ORDER BY b.created_at DESC
                            LIMIT 10
                        ");
                        while ($booking = $stmt->fetch()):
                        ?>
                        <tr>
                            <td>#<?php echo $booking['id']; ?></td>
                            <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['pet_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['room_title']); ?></td>
                            <td><?php echo date('d.m.Y', strtotime($booking['check_in_date'])); ?> - <?php echo date('d.m.Y', strtotime($booking['check_out_date'])); ?></td>
                            <td><?php echo $booking['total_price']; ?> ₽</td>
                            <td><span class="status-<?php echo $booking['status']; ?>"><?php echo $booking['status']; ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>