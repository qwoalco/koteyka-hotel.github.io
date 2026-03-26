<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Обработка редактирования профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $full_name = trim($_POST['full_name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        
        if (empty($full_name) || empty($phone) || empty($email)) {
            $error_message = 'Пожалуйста, заполните все поля';
        } else {
            $stmt = $pdo->prepare("UPDATE clients SET full_name = ?, phone = ?, email = ? WHERE id = ?");
            $stmt->execute([$full_name, $phone, $email, $user_id]);
            $_SESSION['user_name'] = $full_name;
            $success_message = 'Профиль успешно обновлен';
        }
    }
    
    // Смена пароля
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        $stmt = $pdo->prepare("SELECT password_hash FROM clients WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!password_verify($current_password, $user['password_hash'])) {
            $error_message = 'Текущий пароль неверен';
        } elseif (strlen($new_password) < 6) {
            $error_message = 'Новый пароль должен быть не менее 6 символов';
        } elseif ($new_password !== $confirm_password) {
            $error_message = 'Пароли не совпадают';
        } else {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE clients SET password_hash = ? WHERE id = ?");
            $stmt->execute([$new_hash, $user_id]);
            $success_message = 'Пароль успешно изменен';
        }
    }
    
    // Добавление питомца
    if (isset($_POST['add_pet'])) {
        $name = trim($_POST['pet_name']);
        $breed = trim($_POST['pet_breed']);
        $age = (int)$_POST['pet_age'];
        $medical_notes = trim($_POST['medical_notes']);
        
        if (empty($name)) {
            $error_message = 'Введите имя питомца';
        } else {
            $stmt = $pdo->prepare("INSERT INTO pets (client_id, name, breed, age, medical_notes) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $breed, $age, $medical_notes]);
            $success_message = 'Питомец добавлен';
        }
    }
    
    // Редактирование питомца
    if (isset($_POST['edit_pet'])) {
        $pet_id = (int)$_POST['pet_id'];
        $name = trim($_POST['pet_name']);
        $breed = trim($_POST['pet_breed']);
        $age = (int)$_POST['pet_age'];
        $medical_notes = trim($_POST['medical_notes']);
        
        $stmt = $pdo->prepare("UPDATE pets SET name = ?, breed = ?, age = ?, medical_notes = ? WHERE id = ? AND client_id = ?");
        $stmt->execute([$name, $breed, $age, $medical_notes, $pet_id, $user_id]);
        $success_message = 'Питомец обновлен';
    }
    
    // Удаление питомца
    if (isset($_POST['delete_pet'])) {
        $pet_id = (int)$_POST['pet_id'];
        $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ? AND client_id = ?");
        $stmt->execute([$pet_id, $user_id]);
        $success_message = 'Питомец удален';
    }
    
    // Отмена бронирования
    if (isset($_POST['cancel_booking'])) {
        $booking_id = (int)$_POST['booking_id'];
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND client_id = ? AND status IN ('new', 'confirmed')");
        $stmt->execute([$booking_id, $user_id]);
        $success_message = 'Бронирование отменено';
    }
}

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Получаем питомцев пользователя
$stmt = $pdo->prepare("SELECT * FROM pets WHERE client_id = ? ORDER BY id");
$stmt->execute([$user_id]);
$pets = $stmt->fetchAll();

// Получаем бронирования пользователя
$stmt = $pdo->prepare("
    SELECT b.*, r.title as room_title, r.price_per_day
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    WHERE b.client_id = ? 
    ORDER BY b.created_at DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();

// Подключаем CSS для профиля
echo '<link rel="stylesheet" href="access/css/profile.css">';
?>

<div class="profile-wrapper">
    <div class="profile-header">
        <h1>Личный кабинет</h1>
        <p>Управляйте своими данными, питомцами и бронированиями</p>
    </div>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <div class="profile-grid">
        <!-- Боковая панель -->
        <aside class="profile-sidebar">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <?php echo mb_substr($user['full_name'], 0, 1); ?>
                </div>
                <div class="profile-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($pets); ?></div>
                    <div class="stat-label">Питомцев</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($bookings); ?></div>
                    <div class="stat-label">Бронирований</div>
                </div>
            </div>
            <div class="profile-menu">
                <div class="menu-item active" data-tab="profile">
                    📋 Мои данные
                </div>
                <div class="menu-item" data-tab="pets">
                    🐱 Мои питомцы
                </div>
                <div class="menu-item" data-tab="bookings">
                    📅 Мои бронирования
                </div>
                <div class="menu-item" data-tab="password">
                    🔒 Сменить пароль
                </div>
            </div>
        </aside>
        
        <!-- Основной контент -->
        <div class="profile-content">
            <!-- Вкладка: Профиль -->
            <div id="tab-profile" class="tab-content active">
                <h2>Мои данные</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>ФИО *</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Телефон *</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn-save">Сохранить изменения</button>
                </form>
            </div>
            
            <!-- Вкладка: Питомцы -->
            <div id="tab-pets" class="tab-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Мои питомцы</h2>
                    <button class="btn-add" onclick="openAddPetModal()">+ Добавить питомца</button>
                </div>
                
                <?php if (empty($pets)): ?>
                    <div class="empty-message">
                        <p>У вас пока нет питомцев. Добавьте первого!</p>
                    </div>
                <?php else: ?>
                    <div class="pets-grid">
                        <?php foreach ($pets as $pet): ?>
                        <div class="pet-card">
                            <div class="pet-name"><?php echo htmlspecialchars($pet['name']); ?></div>
                            <div class="pet-detail"><strong>Порода:</strong> <?php echo htmlspecialchars($pet['breed'] ?: 'Не указана'); ?></div>
                            <div class="pet-detail"><strong>Возраст:</strong> <?php echo $pet['age']; ?> лет</div>
                            <?php if ($pet['medical_notes']): ?>
                            <div class="pet-detail"><strong>Примечания:</strong> <?php echo htmlspecialchars($pet['medical_notes']); ?></div>
                            <?php endif; ?>
                            <div class="pet-actions">
                                <button class="btn-edit-pet" onclick="editPet(<?php echo $pet['id']; ?>, '<?php echo htmlspecialchars($pet['name']); ?>', '<?php echo htmlspecialchars($pet['breed']); ?>', <?php echo $pet['age']; ?>, '<?php echo htmlspecialchars($pet['medical_notes']); ?>')">Редактировать</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Удалить питомца?')">
                                    <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
                                    <button type="submit" name="delete_pet" class="btn-delete-pet">Удалить</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Вкладка: Бронирования -->
            <div id="tab-bookings" class="tab-content">
                <h2>Мои бронирования</h2>
                
                <?php if (empty($bookings)): ?>
                    <div class="empty-message">
                        <p>У вас пока нет бронирований. <a href="cataloge.php">Забронировать номер</a></p>
                    </div>
                <?php else: ?>
                    <div class="bookings-list">
                        <?php foreach ($bookings as $booking): ?>
                        <div class="booking-card">
                            <div class="booking-header">
                                <span class="booking-id">Бронирование #<?php echo $booking['id']; ?></span>
                                <span class="booking-status status-<?php echo $booking['status']; ?>">
                                    <?php 
                                        $statuses = ['new' => 'Новое', 'confirmed' => 'Подтверждено', 'completed' => 'Завершено', 'cancelled' => 'Отменено'];
                                        echo $statuses[$booking['status']];
                                    ?>
                                </span>
                            </div>
                            <div class="booking-details">
                                <div class="booking-detail-item"><strong>Номер:</strong> <?php echo htmlspecialchars($booking['room_title']); ?></div>
                                <div class="booking-detail-item"><strong>Заезд:</strong> <?php echo date('d.m.Y', strtotime($booking['check_in_date'])); ?></div>
                                <div class="booking-detail-item"><strong>Выезд:</strong> <?php echo date('d.m.Y', strtotime($booking['check_out_date'])); ?></div>
                                <div class="booking-detail-item"><strong>Дней:</strong> <?php echo (strtotime($booking['check_out_date']) - strtotime($booking['check_in_date'])) / 86400; ?></div>
                            </div>
                            <div class="booking-footer">
                                <div class="booking-price"><?php echo $booking['total_price']; ?> ₽</div>
                                <?php if (in_array($booking['status'], ['new', 'confirmed'])): ?>
                                <form method="POST" onsubmit="return confirm('Отменить бронирование?')">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <button type="submit" name="cancel_booking" class="btn-cancel-booking">Отменить бронь</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Вкладка: Смена пароля -->
            <div id="tab-password" class="tab-content">
                <h2>Смена пароля</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Текущий пароль *</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>Новый пароль *</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Подтверждение пароля *</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn-save">Сменить пароль</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно добавления/редактирования питомца -->
<div id="pet-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closePetModal()">&times;</span>
        <h2 id="pet-modal-title">Добавить питомца</h2>
        <form method="POST" id="pet-form">
            <input type="hidden" name="pet_id" id="pet-id">
            <div class="form-group">
                <label>Имя питомца *</label>
                <input type="text" name="pet_name" id="pet-name" required>
            </div>
            <div class="form-group">
                <label>Порода</label>
                <input type="text" name="pet_breed" id="pet-breed">
            </div>
            <div class="form-group">
                <label>Возраст (лет)</label>
                <input type="number" name="pet_age" id="pet-age" min="0" max="30">
            </div>
            <div class="form-group">
                <label>Медицинские заметки</label>
                <textarea name="medical_notes" id="pet-medical-notes" rows="3"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closePetModal()">Отмена</button>
                <button type="submit" name="add_pet" id="pet-submit-btn" class="btn-save">Добавить</button>
            </div>
        </form>
    </div>
</div>

<script>
// Переключение вкладок
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function() {
        const tab = this.dataset.tab;
        
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.getElementById(`tab-${tab}`).classList.add('active');
    });
});

// Модальное окно для добавления питомца
function openAddPetModal() {
    document.getElementById('pet-modal-title').textContent = 'Добавить питомца';
    document.getElementById('pet-id').value = '';
    document.getElementById('pet-name').value = '';
    document.getElementById('pet-breed').value = '';
    document.getElementById('pet-age').value = '';
    document.getElementById('pet-medical-notes').value = '';
    document.getElementById('pet-submit-btn').name = 'add_pet';
    document.getElementById('pet-submit-btn').textContent = 'Добавить';
    document.getElementById('pet-modal').style.display = 'block';
}

function editPet(id, name, breed, age, medical_notes) {
    document.getElementById('pet-modal-title').textContent = 'Редактировать питомца';
    document.getElementById('pet-id').value = id;
    document.getElementById('pet-name').value = name;
    document.getElementById('pet-breed').value = breed || '';
    document.getElementById('pet-age').value = age;
    document.getElementById('pet-medical-notes').value = medical_notes || '';
    document.getElementById('pet-submit-btn').name = 'edit_pet';
    document.getElementById('pet-submit-btn').textContent = 'Сохранить';
    document.getElementById('pet-modal').style.display = 'block';
}

function closePetModal() {
    document.getElementById('pet-modal').style.display = 'none';
}

// Закрытие модалки по клику вне её
window.onclick = function(event) {
    const modal = document.getElementById('pet-modal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>