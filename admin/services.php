<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Обработка добавления услуги
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        $stmt = $pdo->prepare("INSERT INTO services (name, description, price, icon_path) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['icon_path']
        ]);
        $_SESSION['success'] = "Услуга добавлена";
    } elseif (isset($_POST['delete_service'])) {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$_POST['service_id']]);
        $_SESSION['success'] = "Услуга удалена";
    } elseif (isset($_POST['toggle_service'])) {
        $stmt = $pdo->prepare("UPDATE services SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$_POST['service_id']]);
        $_SESSION['success'] = "Статус услуги изменен";
    }
    header('Location: services.php');
    exit;
}

$services = $pdo->query("SELECT * FROM services ORDER BY id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Услуги - Админ-панель</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../access/css/admin.css">
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
        <h2>Управление услугами</h2>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="add-form">
            <h3>Добавить новую услугу</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Название услуги</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Цена (₽)</label>
                        <input type="number" name="price" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Путь к иконке</label>
                    <input type="text" name="icon_path" value="access/image/">
                </div>
                <button type="submit" name="add_service" class="btn btn-primary">Добавить услугу</button>
            </form>
        </div>
        
        <div class="services-table">
            <h3>Список услуг</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Цена</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo $service['id']; ?></td>
                        <td><?php echo htmlspecialchars($service['name']); ?></td>
                        <td><?php echo htmlspecialchars($service['description']); ?></td>
                        <td><?php echo $service['price']; ?> ₽</td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                <button type="submit" name="toggle_service" class="status-btn <?php echo $service['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $service['is_active'] ? 'Активна' : 'Неактивна'; ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Удалить услугу?')">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                <button type="submit" name="delete_service" class="delete-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>