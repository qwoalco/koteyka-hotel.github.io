<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Обработка добавления/редактирования номера
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_room'])) {
        $stmt = $pdo->prepare("INSERT INTO rooms (title, size_width, size_height, size_depth, area, price_per_day, amenities, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['title'],
            $_POST['size_width'],
            $_POST['size_height'],
            $_POST['size_depth'],
            $_POST['area'],
            $_POST['price_per_day'],
            $_POST['amenities'],
            $_POST['image_path']
        ]);
    } elseif (isset($_POST['delete_room'])) {
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->execute([$_POST['room_id']]);
    }
    header('Location: rooms.php');
    exit;
}

$rooms = $pdo->query("SELECT * FROM rooms ORDER BY price_per_day")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Номера - Админ-панель</title>
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
        .add-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary {
            background: #F7931E;
            color: white;
        }
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .room-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .room-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .room-card-content {
            padding: 15px;
        }
        .room-card h3 {
            margin-bottom: 10px;
        }
        .room-card .price {
            color: #F7931E;
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
        <h2 style="margin-bottom: 20px;">Управление номерами</h2>
        
        <div class="add-form">
            <h3>Добавить новый номер</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Название</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Размеры (ШхГхВ в см)</label>
                    <input type="number" name="size_width" placeholder="Ширина" required>
                    <input type="number" name="size_depth" placeholder="Глубина" required>
                    <input type="number" name="size_height" placeholder="Высота" required>
                </div>
                <div class="form-group">
                    <label>Площадь (м²)</label>
                    <input type="number" step="0.01" name="area" required>
                </div>
                <div class="form-group">
                    <label>Цена за сутки (₽)</label>
                    <input type="number" name="price_per_day" required>
                </div>
                <div class="form-group">
                    <label>Оснащение</label>
                    <textarea name="amenities" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Путь к изображению</label>
                    <input type="text" name="image_path" value="access/image/">
                </div>
                <button type="submit" name="add_room" class="btn btn-primary">Добавить номер</button>
            </form>
        </div>
        
        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
            <div class="room-card">
                <img src="../<?php echo htmlspecialchars($room['image_path']); ?>" alt="<?php echo htmlspecialchars($room['title']); ?>">
                <div class="room-card-content">
                    <h3><?php echo htmlspecialchars($room['title']); ?></h3>
                    <p>Площадь: <?php echo $room['area']; ?> м²</p>
                    <p>Размеры: <?php echo $room['size_width']; ?>x<?php echo $room['size_depth']; ?>x<?php echo $room['size_height']; ?> см</p>
                    <div class="price"><?php echo $room['price_per_day']; ?> ₽/сутки</div>
                    <p><small><?php echo htmlspecialchars($room['amenities']); ?></small></p>
                    <form method="POST" style="margin-top: 10px;">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        <button type="submit" name="delete_room" class="delete-btn" onclick="return confirm('Удалить номер?')">Удалить</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>