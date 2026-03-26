<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// Получаем все номера
try {
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE is_active = TRUE ORDER BY price_per_day");
    $stmt->execute();
    $rooms = $stmt->fetchAll();
} catch (PDOException $e) {
    $rooms = [];
    error_log("Error fetching rooms: " . $e->getMessage());
}

// Получаем все услуги
try {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE is_active = TRUE");
    $stmt->execute();
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    $services = [];
    error_log("Error fetching services: " . $e->getMessage());
}

// Получаем уникальные площади для фильтра
try {
    $stmt = $pdo->prepare("SELECT DISTINCT area FROM rooms ORDER BY area");
    $stmt->execute();
    $areas = $stmt->fetchAll();
} catch (PDOException $e) {
    $areas = [];
    error_log("Error fetching areas: " . $e->getMessage());
}
?>

<div class="catalog-container">
    <h1 class="page-title">Наши номера</h1>

    <div class="catalog-layout">
        <!-- Боковая панель фильтра -->
        <aside class="filter-sidebar">
            <h3 class="filter-title">Фильтр</h3>
            
            <div class="filter-section">
                <h4>Цена за сутки, ₽</h4>
                <div class="price-range">
                    <div class="price-inputs">
                        <input type="number" id="min-price" value="0" min="0" max="600">
                        <span>—</span>
                        <input type="number" id="max-price" value="600" min="0" max="600">
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <h4>Площадь</h4>
                <?php foreach ($areas as $area): ?>
                <label class="filter-checkbox">
                    <input type="checkbox" class="area-filter" value="<?php echo $area['area']; ?>"> 
                    <span><?php echo $area['area']; ?> м²</span>
                </label>
                <?php endforeach; ?>
            </div>

            <button class="reset-filter-btn">Сбросить фильтр</button>
        </aside>

        <!-- Сетка карточек номеров -->
        <div class="rooms-grid" id="roomsGrid">
            <?php foreach ($rooms as $room): ?>
            <div class="room-card-catalog" data-price="<?php echo $room['price_per_day']; ?>" data-area="<?php echo $room['area']; ?>">
                <div class="room-card-image">
                    <img src="<?php echo htmlspecialchars($room['image_path']); ?>" alt="<?php echo htmlspecialchars($room['title']); ?>">
                </div>
                <div class="room-card-content">
                    <h3><?php echo htmlspecialchars($room['title']); ?></h3>
                    <ul class="room-card-specs">
                        <li>Размеры - <?php echo $room['size_width']; ?>x<?php echo $room['size_depth']; ?>x<?php echo $room['size_height']; ?> см</li>
                        <li>Площадь - <?php echo $room['area']; ?> м²</li>
                    </ul>
                    <div class="room-amenities">
                        <?php echo htmlspecialchars($room['amenities']); ?>
                    </div>
                    <div class="room-price"><?php echo $room['price_per_day']; ?> ₽/сутки</div>
                    <button class="btn-orange booking-btn" onclick="openBookingWithRoom(<?php echo $room['id']; ?>, '<?php echo htmlspecialchars($room['title']); ?>', <?php echo $room['price_per_day']; ?>)">
                        Забронировать
                        <img class="paw-icon-white" src="access/image/paw_white.svg" alt="">
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Секция дополнительных услуг -->
    <section class="services-section">
        <h2 class="services-title">Дополнительные услуги</h2>
        <p class="services-subtitle">Сделайте пребывание вашего питомца ещё комфортнее</p>

        <div class="services-grid">
            <?php foreach ($services as $service): ?>
            <div class="service-card">
                <div class="service-icon">
                    <img src="<?php echo htmlspecialchars($service['icon_path']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>">
                </div>
                <div class="service-card-content">
                    <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                    <p class="service-description"><?php echo htmlspecialchars($service['description']); ?></p>
                    <div class="service-price"><?php echo $service['price']; ?> ₽/день</div>
                    <button class="btn-orange booking-btn" onclick="openBookingModal()">
                        Забронировать
                        <img src="access/image/paw_white.svg" alt="">
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Секция "Как нас найти" -->
    <section class="section-map">
        <div class="container">
            <h2>Как нас найти</h2>
            <div class="map-wrapper">
                <div class="map-box">
                    <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A...&lang=ru_RU" width="100%" height="100%" frameborder="0"></iframe>
                </div>
                <div class="info-box">
                    <h3>Контакты</h3>
                    <p><strong>Адрес:</strong> Санкт-Петербург, ул Большая Конюшенная, д 19</p>
                    <p><strong>Режим работы:</strong> Ежедневно, с 9:00 до 20:00</p>
                    <p><strong>Телефон:</strong> 8 (800) 333-55-99</p>
                    <p><strong>E-mail:</strong> info@cat-hotel.ru</p>
                    <div class="social">
                        <a href="#"><img src="access/image/vk.svg" alt="VK"></a>
                        <a href="#"><img src="access/image/insta.svg" alt="Instagram"></a>
                        <a href="#"><img src="access/image/fb.svg" alt="Facebook"></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Модальное окно для бронирования -->
<div id="booking-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="hideModal('booking-modal')">&times;</span>
        <h2>Забронировать номер</h2>
        
        <form id="booking-form" action="booking-process.php" method="POST">
            <input type="hidden" name="room_id" id="booking-room-id" value="">
            <input type="hidden" name="room_price" id="booking-room-price" value="">
            
            <div class="form-group">
                <label for="name">Ваше имя *</label>
                <input type="text" id="name" name="name" required value="<?php echo isset($_COOKIE['user_name']) ? htmlspecialchars($_COOKIE['user_name']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="pet-name">Имя Питомца *</label>
                <input type="text" id="pet-name" name="pet_name" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Телефон *</label>
                <input type="tel" id="phone" name="phone" required value="<?php echo isset($_COOKIE['user_phone']) ? htmlspecialchars($_COOKIE['user_phone']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : ''; ?>">
            </div>
            
            <div class="form-group dates">
                <label>Дата заезда *</label>
                <div class="date-inputs">
                    <input type="date" id="date-from" name="date_from" required>
                    <span>—</span>
                    <input type="date" id="date-to" name="date_to" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Дополнительные услуги</label>
                <div class="services-checkboxes">
                    <?php foreach ($services as $service): ?>
                    <label class="service-checkbox">
                        <input type="checkbox" name="services[]" value="<?php echo $service['id']; ?>" data-price="<?php echo $service['price']; ?>">
                        <span><?php echo htmlspecialchars($service['name']); ?> (<?php echo $service['price']; ?> ₽)</span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label>Комментарий</label>
                <textarea name="notes" rows="3" placeholder="Особые пожелания, аллергии, режим питания..."></textarea>
            </div>
            
            <div class="total-price-info">
                <strong>Итого: <span id="total-price-display">0</span> ₽</strong>
            </div>
            
            <button type="submit" class="modal-btn">Отправить заявку</button>
        </form>
    </div>
</div>

<!-- Модальное окно "Спасибо" -->
<div id="thanks-modal" class="modal">
    <div class="modal-content thanks-content">
        <span class="close-modal" onclick="hideModal('thanks-modal')">&times;</span>
        <div class="thanks-icon">✓</div>
        <h2>Спасибо за заявку!</h2>
        <p>Мы свяжемся с вами в ближайшее время</p>
        <button class="modal-btn" onclick="hideModal('thanks-modal')">Ок</button>
    </div>
</div>

<script>
// Функция для открытия модалки с выбранным номером
function openBookingWithRoom(roomId, roomTitle, roomPrice) {
    document.getElementById('booking-room-id').value = roomId;
    document.getElementById('booking-room-price').value = roomPrice;
    
    // Удаляем старую информацию о номере, если есть
    const existingInfo = document.querySelector('.selected-room-info');
    if (existingInfo) existingInfo.remove();
    
    // Добавляем информацию о выбранном номере
    const roomInfo = document.createElement('div');
    roomInfo.className = 'selected-room-info';
    roomInfo.innerHTML = `<strong>Выбранный номер:</strong> ${roomTitle} (${roomPrice} ₽/сутки)`;
    
    const form = document.getElementById('booking-form');
    form.insertBefore(roomInfo, form.firstChild);
    
    showModal('booking-modal');
    calculateTotal();
}

// Функция для открытия модалки без выбранного номера (из услуг)
function openBookingModal() {
    // Очищаем информацию о номере
    const existingInfo = document.querySelector('.selected-room-info');
    if (existingInfo) existingInfo.remove();
    
    document.getElementById('booking-room-id').value = '';
    document.getElementById('booking-room-price').value = '';
    
    showModal('booking-modal');
}

// Расчет общей стоимости
function calculateTotal() {
    const dateFrom = document.getElementById('date-from').value;
    const dateTo = document.getElementById('date-to').value;
    const roomPrice = parseFloat(document.getElementById('booking-room-price').value) || 0;
    const serviceCheckboxes = document.querySelectorAll('input[name="services[]"]:checked');
    
    let total = 0;
    
    // Расчет стоимости номера
    if (dateFrom && dateTo) {
        const start = new Date(dateFrom);
        const end = new Date(dateTo);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        if (days > 0) {
            total += roomPrice * days;
        }
    }
    
    // Расчет стоимости услуг
    serviceCheckboxes.forEach(cb => {
        total += parseFloat(cb.dataset.price);
    });
    
    document.getElementById('total-price-display').textContent = total;
}

// Фильтрация номеров
function initFilter() {
    const minPriceInput = document.getElementById('min-price');
    const maxPriceInput = document.getElementById('max-price');
    const areaCheckboxes = document.querySelectorAll('.area-filter');
    const resetBtn = document.querySelector('.reset-filter-btn');
    const rooms = document.querySelectorAll('.room-card-catalog');

    function filterRooms() {
        const minPrice = parseInt(minPriceInput.value) || 0;
        const maxPrice = parseInt(maxPriceInput.value) || 600;
        
        const selectedAreas = [];
        areaCheckboxes.forEach(cb => {
            if (cb.checked) selectedAreas.push(parseFloat(cb.value));
        });

        rooms.forEach(room => {
            const price = parseInt(room.dataset.price);
            const area = parseFloat(room.dataset.area);
            
            const priceMatch = price >= minPrice && price <= maxPrice;
            const areaMatch = selectedAreas.length === 0 || selectedAreas.includes(area);
            
            room.style.display = (priceMatch && areaMatch) ? 'flex' : 'none';
        });
    }

    if (minPriceInput) minPriceInput.addEventListener('input', filterRooms);
    if (maxPriceInput) maxPriceInput.addEventListener('input', filterRooms);
    areaCheckboxes.forEach(cb => cb.addEventListener('change', filterRooms));
    
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            if (minPriceInput) minPriceInput.value = 0;
            if (maxPriceInput) maxPriceInput.value = 600;
            areaCheckboxes.forEach(cb => cb.checked = false);
            filterRooms();
        });
    }

    filterRooms();
}

// Обработчики событий
document.addEventListener('DOMContentLoaded', function() {
    initFilter();
    
    const dateFrom = document.getElementById('date-from');
    const dateTo = document.getElementById('date-to');
    const serviceCheckboxes = document.querySelectorAll('input[name="services[]"]');
    
    if (dateFrom) dateFrom.addEventListener('change', calculateTotal);
    if (dateTo) dateTo.addEventListener('change', calculateTotal);
    serviceCheckboxes.forEach(cb => cb.addEventListener('change', calculateTotal));
});

<?php if (isset($_SESSION['booking_success']) && $_SESSION['booking_success'] === true): ?>
    showModal('thanks-modal');
    <?php unset($_SESSION['booking_success']); ?>
<?php endif; ?>
</script>

<?php require_once 'includes/footer.php'; ?>