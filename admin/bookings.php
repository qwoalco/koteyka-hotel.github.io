<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Обработка изменения статуса
if (isset($_POST['update_status'])) {
    $booking_id = (int)$_POST['booking_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $booking_id]);
    
    header('Location: bookings.php');
    exit;
}

// Получаем все бронирования
$stmt = $pdo->query("
    SELECT b.*, c.full_name, c.phone, c.email, p.name as pet_name, r.title as room_title
    FROM bookings b
    JOIN clients c ON b.client_id = c.id
    JOIN pets p ON b.pet_id = p.id
    JOIN rooms r ON b.room_id = r.id
    ORDER BY b.created_at DESC
");
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирования - Админ-панель</title>
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
            flex-wrap: wrap;
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #ddd;
        }
        .admin-nav a {
            color: #333;
            text-decoration: none;
            padding: 5px 10px;
        }
        .admin-nav a:hover {
            color: #F7931E;
        }
        .admin-container {
            padding: 30px;
        }
        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f9f9f9;
        }
        .status-select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background: #F7931E;
            color: white;
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
        <a href="faq.php">FAQ</a>
    </div>
    
    <div class="admin-container">
        <h2 style="margin-bottom: 20px;">Управление бронированиями</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Питомец</th>
                    <th>Номер</th>
                    <th>Заезд</th>
                    <th>Выезд</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td>#<?php echo $booking['id']; ?></td>
                    <td>
                        <?php echo htmlspecialchars($booking['full_name']); ?><br>
                        <small><?php echo htmlspecialchars($booking['phone']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($booking['pet_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['room_title']); ?></td>
                    <td><?php echo date('d.m.Y', strtotime($booking['check_in_date'])); ?></td>
                    <td><?php echo date('d.m.Y', strtotime($booking['check_out_date'])); ?></td>
                    <td><?php echo $booking['total_price']; ?> ₽</td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="new" <?php echo $booking['status'] == 'new' ? 'selected' : ''; ?>>Новое</option>
                                <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Подтверждено</option>
                                <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>Завершено</option>
                                <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Отменено</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="viewBooking(<?php echo $booking['id']; ?>)">Детали</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script>
    function viewBooking(id) {
        alert('Просмотр деталей бронирования #' + id);
        // Здесь можно открыть модальное окно или перейти на страницу деталей
    }
    </script>
</body>
</html>