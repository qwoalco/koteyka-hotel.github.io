<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Получаем всех клиентов с их питомцами и бронированиями
$clients = $pdo->query("
    SELECT c.*, 
           COUNT(DISTINCT p.id) as pets_count,
           COUNT(DISTINCT b.id) as bookings_count,
           SUM(b.total_price) as total_spent
    FROM clients c
    LEFT JOIN pets p ON c.id = p.client_id
    LEFT JOIN bookings b ON c.id = b.client_id
    GROUP BY c.id
    ORDER BY c.created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Клиенты - Админ-панель</title>
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
        <h2>Управление клиентами</h2>
        
        <div class="clients-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>Питомцев</th>
                        <th>Бронирований</th>
                        <th>Потрачено</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client['id']; ?></td>
                        <td><?php echo htmlspecialchars($client['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($client['phone']); ?></td>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                        <td><?php echo $client['pets_count']; ?></td>
                        <td><?php echo $client['bookings_count']; ?></td>
                        <td><?php echo number_format($client['total_spent'] ?? 0, 0, '', ' '); ?> ₽</td>
                        <td><?php echo date('d.m.Y', strtotime($client['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-primary" onclick="viewClient(<?php echo $client['id']; ?>)">Детали</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div id="client-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Информация о клиенте</h2>
            <div id="client-details"></div>
        </div>
    </div>
    
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 20px;
            width: 90%;
            max-width: 600px;
            border-radius: 10px;
        }
        .close-modal {
            float: right;
            font-size: 28px;
            cursor: pointer;
        }
        .client-info {
            margin-top: 20px;
        }
        .client-info p {
            margin: 10px 0;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .pets-list {
            margin-top: 20px;
        }
        .pet-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
    </style>
    
    <script>
        function viewClient(clientId) {
            fetch(`get-client.php?id=${clientId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let html = '<div class="client-info">';
                        html += `<p><strong>ФИО:</strong> ${data.full_name}</p>`;
                        html += `<p><strong>Телефон:</strong> ${data.phone}</p>`;
                        html += `<p><strong>Email:</strong> ${data.email}</p>`;
                        html += `<p><strong>Дата регистрации:</strong> ${data.created_at}</p>`;
                        html += '</div>';
                        
                        html += '<div class="pets-list"><h3>Питомцы:</h3>';
                        if (data.pets && data.pets.length > 0) {
                            data.pets.forEach(pet => {
                                html += `<div class="pet-item">`;
                                html += `<strong>${pet.name}</strong> - ${pet.breed || 'Порода не указана'}, ${pet.age || '?'} лет`;
                                if (pet.medical_notes) {
                                    html += `<br><small>Примечания: ${pet.medical_notes}</small>`;
                                }
                                html += `</div>`;
                            });
                        } else {
                            html += '<p>Нет питомцев</p>';
                        }
                        html += '</div>';
                        
                        document.getElementById('client-details').innerHTML = html;
                        document.getElementById('client-modal').style.display = 'block';
                    }
                });
        }
        
        document.querySelector('.close-modal').onclick = function() {
            document.getElementById('client-modal').style.display = 'none';
        }
    </script>
</body>
</html>