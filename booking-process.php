<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Получаем данные из формы
$name = trim($_POST['name']);
$pet_name = trim($_POST['pet_name']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
$date_from = $_POST['date_from'];
$date_to = $_POST['date_to'];
$room_id = (int)$_POST['room_id'];
$notes = trim($_POST['notes'] ?? '');
$services = $_POST['services'] ?? [];

// Валидация
if (empty($name) || empty($pet_name) || empty($phone) || empty($email) || empty($date_from) || empty($date_to) || $room_id <= 0) {
    $_SESSION['error'] = 'Пожалуйста, заполните все обязательные поля';
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
    exit;
}

// Проверка дат
if (strtotime($date_from) < strtotime(date('Y-m-d'))) {
    $_SESSION['error'] = 'Дата заезда не может быть раньше сегодняшнего дня';
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
    exit;
}

if (strtotime($date_to) <= strtotime($date_from)) {
    $_SESSION['error'] = 'Дата выезда должна быть позже даты заезда';
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
    exit;
}

// Сохраняем данные в cookies (на 30 дней)
setcookie('user_name', $name, time() + 3600 * 24 * 30, '/');
setcookie('user_phone', $phone, time() + 3600 * 24 * 30, '/');
setcookie('user_email', $email, time() + 3600 * 24 * 30, '/');

try {
    $pdo->beginTransaction();
    
    // 1. Найти или создать клиента
    $stmt = $pdo->prepare("SELECT id, full_name, phone, email FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch();
    
    if (!$client) {
        // Создаем нового клиента
        $stmt = $pdo->prepare("INSERT INTO clients (full_name, phone, email) VALUES (?, ?, ?)");
        $stmt->execute([$name, $phone, $email]);
        $client_id = $pdo->lastInsertId();
    } else {
        $client_id = $client['id'];
        
        // Обновляем данные, если они изменились
        if ($client['full_name'] !== $name || $client['phone'] !== $phone) {
            $stmt = $pdo->prepare("UPDATE clients SET full_name = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $client_id]);
        }
    }
    
    // Автоматически авторизуем пользователя, если он еще не авторизован
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = $client_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = 'user';
    }
    
    // 2. Найти или создать питомца
    $stmt = $pdo->prepare("SELECT id FROM pets WHERE name = ? AND client_id = ?");
    $stmt->execute([$pet_name, $client_id]);
    $pet = $stmt->fetch();
    
    if (!$pet) {
        $stmt = $pdo->prepare("INSERT INTO pets (client_id, name) VALUES (?, ?)");
        $stmt->execute([$client_id, $pet_name]);
        $pet_id = $pdo->lastInsertId();
    } else {
        $pet_id = $pet['id'];
    }
    
    // 3. Получить цену номера
    $stmt = $pdo->prepare("SELECT price_per_day FROM rooms WHERE id = ? AND is_active = 1");
    $stmt->execute([$room_id]);
    $room_price = $stmt->fetchColumn();
    
    if (!$room_price) {
        throw new Exception('Номер не найден');
    }
    
    // 4. Рассчитать стоимость
    $days = (strtotime($date_to) - strtotime($date_from)) / (60 * 60 * 24);
    $rooms_total = $room_price * $days;
    
    // 5. Рассчитать стоимость услуг
    $services_total = 0;
    $selected_services = [];
    if (!empty($services)) {
        $placeholders = str_repeat('?,', count($services) - 1) . '?';
        $stmt = $pdo->prepare("SELECT id, price FROM services WHERE id IN ($placeholders) AND is_active = 1");
        $stmt->execute($services);
        $service_data = $stmt->fetchAll();
        
        foreach ($service_data as $service) {
            $services_total += $service['price'];
            $selected_services[] = $service['id'];
        }
    }
    
    $total_price = $rooms_total + $services_total;
    
    // 6. Создать бронирование
    $stmt = $pdo->prepare("INSERT INTO bookings (client_id, pet_id, room_id, check_in_date, check_out_date, total_price, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'new')");
    $stmt->execute([$client_id, $pet_id, $room_id, $date_from, $date_to, $total_price, $notes]);
    $booking_id = $pdo->lastInsertId();
    
    // 7. Добавить услуги в бронирование (если есть)
    foreach ($selected_services as $service_id) {
        $stmt = $pdo->prepare("SELECT price FROM services WHERE id = ?");
        $stmt->execute([$service_id]);
        $service_price = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("INSERT INTO booking_services (booking_id, service_id, quantity, price_at_booking) VALUES (?, ?, 1, ?)");
        $stmt->execute([$booking_id, $service_id, $service_price]);
    }
    
    $pdo->commit();
    
    // Сохраняем в сессию успех
    $_SESSION['booking_success'] = true;
    $_SESSION['booking_id'] = $booking_id;
    
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
    
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Booking error: " . $e->getMessage());
    $_SESSION['error'] = 'Произошла ошибка при бронировании. Пожалуйста, попробуйте позже.';
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
}
exit;