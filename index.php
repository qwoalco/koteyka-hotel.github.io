<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// Получаем последние отзывы
try {
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE is_approved = TRUE ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $reviews = $stmt->fetchAll();
} catch (PDOException $e) {
    $reviews = [];
}

// Получаем номера для слайдера
try {
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE is_active = TRUE ORDER BY id");
    $stmt->execute();
    $rooms = $stmt->fetchAll();
} catch (PDOException $e) {
    $rooms = [];
}

// Получаем услуги для формы бронирования
try {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE is_active = TRUE");
    $stmt->execute();
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    $services = [];
}
?>

<div class="content-container">
    <!-- 1. Главный экран -->
    <section class="hero">
        <div class="hero-text">
            <p class="city">Санкт-Петербург</p>
            <h1 class="hotel-name">Котейка</h1>
            <p class="hotel-desc">Уютная гостиница для котов и кошек</p>
            <button class="btn-book booking-btn" onclick="showModal('booking-modal')">
                Забронировать
                <img src="access/image/paw.svg" alt="">
            </button>
        </div>
    </section>

    <!-- 2. Почему мы? -->
    <section id="why" class="section-why">
        <div class="container">
            <h2>Почему мы?</h2>
            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon"><img src="access/image/icon_temperature.png" alt="температура"></div>
                    <h3>Комфортная температура</h3>
                    <p>Во всех номерах поддерживается комфортная температура в пределах 23–25 градусов.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><img src="access/image/icon_video.png" alt="видеонаблюдение"></div>
                    <h3>Видеонаблюдение</h3>
                    <p>Вы сможете следить за своим питомцем со своего смартфона или компьютера.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><img src="access/image/taxi.png" alt="зоотакси"></div>
                    <h3>Услуги Зоотакси</h3>
                    <p>Мы приедем за вашим питомцем в любой район Санкт-Петербурга.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><img src="access/image/icon_pitanie.png" alt="питание"></div>
                    <h3>Сбалансированное питание</h3>
                    <p>Вы можете привезти свой корм или доверить рацион профессионалам.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><img src="access/image/cat_progulka.png" alt="прогулки"></div>
                    <h3>Ежедневные прогулки</h3>
                    <p>По вашему желанию мы выгуливаем вашего питомца два раза в день.</p>
                </div>
                <div class="why-card">
                    <div class="why-icon"><img src="access/image/help_veter.png" alt="ветеринар"></div>
                    <h3>Лучшие ветеринары</h3>
                    <p>В гостинице 24 часа дежурит ветеринарный врач.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Номера - СЛАЙДЕР -->
    <section class="section-rooms">
        <div class="container">
            <h2>Номера</h2>
            
            <div class="rooms-slider" id="roomsSlider">
                <div class="rooms-track" id="roomsTrack">
                    <?php foreach ($rooms as $room): ?>
                    <div class="room-slide">
                        <div class="room-slide-image">
                            <img src="<?php echo htmlspecialchars($room['image_path']); ?>" alt="<?php echo htmlspecialchars($room['title']); ?>">
                        </div>
                        <div class="room-slide-content">
                            <h3><?php echo htmlspecialchars($room['title']); ?></h3>
                            <ul>
                                <li>Площадь - <?php echo $room['area']; ?> м²</li>
                                <li>Размеры (ШхГхВ) - <?php echo $room['size_width']; ?>x<?php echo $room['size_depth']; ?>x<?php echo $room['size_height']; ?> см</li>
                                <li>Цена за сутки: <?php echo $room['price_per_day']; ?>₽</li>
                            </ul>
                            <button class="btn-orange booking-btn" onclick="openBookingWithRoom(<?php echo $room['id']; ?>, '<?php echo htmlspecialchars($room['title']); ?>', <?php echo $room['price_per_day']; ?>)">
                                Забронировать
                                <img class="paw-icon-white" src="access/image/paw_white.svg" alt="">
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="slider-controls">
                    <button class="slider-arrow prev" id="roomsPrev">←</button>
                    <div class="slider-dots" id="roomsDots">
                        <?php for ($i = 0; $i < count($rooms); $i++): ?>
                            <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>"></span>
                        <?php endfor; ?>
                    </div>
                    <button class="slider-arrow next" id="roomsNext">→</button>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Отзывы - СЛАЙДЕР -->
    <section class="section-reviews">
        <div class="container">
            <h2>Отзывы</h2>
            
            <div class="reviews-slider" id="reviewsSlider">
                <div class="reviews-track" id="reviewsTrack">
                    <?php foreach ($reviews as $review): ?>
                    <div class="review-slide">
                        <div class="review-card">
                            <p class="review-text">"<?php echo htmlspecialchars($review['review_text']); ?>"</p>
                            <p class="review-author"><?php echo htmlspecialchars($review['author_name']); ?></p>
                            <p class="review-date"><?php echo date('d F, Y', strtotime($review['created_at'])); ?></p>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $review['rating']): ?>
                                        ★
                                    <?php else: ?>
                                        ☆
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="slider-controls">
                    <button class="slider-arrow prev" id="reviewsPrev">←</button>
                    <div class="slider-dots" id="reviewsDots">
                        <?php for ($i = 0; $i < count($reviews); $i++): ?>
                            <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>"></span>
                        <?php endfor; ?>
                    </div>
                    <button class="slider-arrow next" id="reviewsNext">→</button>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Как мы работаем -->
    <section class="section-process">
        <div class="container">
            <h2>Как мы работаем</h2>
            <p class="process-subtitle">Простой и понятный процесс: от бронирования до встречи питомца</p>

            <div class="process-steps">
                <div class="process-step">
                    <div class="step-circle">1</div>
                    <h3>Заявка</h3>
                    <p>Вы оставляете заявку на сайте или по телефону</p>
                </div>
                <div class="process-step">
                    <div class="step-circle">2</div>
                    <h3>Трансфер</h3>
                    <p>Привозите питомца сами или вызываете наше зоотакси</p>
                </div>
                <div class="process-step">
                    <div class="step-circle">3</div>
                    <h3>Адаптация</h3>
                    <p>Осмотр ветврачом, знакомство с номером и игрушками</p>
                </div>
                <div class="process-step">
                    <div class="step-circle">4</div>
                    <h3>Наблюдение</h3>
                    <p>Видео 24/7, ежедневные фото и отчёты в мессенджере</p>
                </div>
                <div class="process-step">
                    <div class="step-circle">5</div>
                    <h3>Возвращение</h3>
                    <p>Забираете счастливого и ухоженного питомца</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Акции и спецпредложения -->
    <section class="section-promo">
        <div class="container">
            <h2>Акции и спецпредложения</h2>

            <div class="promo-grid">
                <div class="promo-card">
                    <span class="promo-label">Для новых клиентов</span>
                    <h3>Скидка 15%</h3>
                    <p>При бронировании от 10 суток — скидка 15% на любой номер</p>
                    <p class="promo-code">ПРОМОКОД: NEW15</p>
                    <button class="btn-orange-promo booking-btn" onclick="showModal('booking-modal')">Забронировать</button>
                </div>

                <div class="promo-card">
                    <span class="promo-label">Приведи друга</span>
                    <h3>500 ₽ в подарок</h3>
                    <p>Приведите друга — получите скидку 500₽ на следующий заезд</p>
                    <button class="btn-orange-promo" onclick="showModal('participate-modal')">Участвовать</button>
                </div>
            </div>

            <div class="promo-banner">
                <span class="promo-label">Действует всегда</span>
                <h3>Бесплатное зоотакси</h3>
                <p>При бронировании от 7 ночей — привезём питомца бесплатно</p>
                <button class="btn-dark" onclick="showModal('details-modal')">
                    <span class="banner-link">Подробнее</span>
                </button>
            </div>
        </div>
    </section>

    <!-- 7. Как нас найти -->
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

<!-- Модальные окна -->
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
                <?php foreach ($services as $service): ?>
                <label class="service-checkbox">
                    <input type="checkbox" name="services[]" value="<?php echo $service['id']; ?>" data-price="<?php echo $service['price']; ?>">
                    <?php echo htmlspecialchars($service['name']); ?> (<?php echo $service['price']; ?> ₽)
                </label>
                <?php endforeach; ?>
            </div>
            
            <div class="form-group">
                <label>Комментарий</label>
                <textarea name="notes" rows="3"></textarea>
            </div>
            
            <div class="total-price-info">
                <strong>Итого: <span id="total-price-display">0</span> ₽</strong>
            </div>
            
            <button type="submit" class="btn-orange modal-btn">Отправить заявку</button>
        </form>
    </div>
</div>

<div id="thanks-modal" class="modal">
    <div class="modal-content thanks-content">
        <span class="close-modal" onclick="hideModal('thanks-modal')">&times;</span>
        <div class="thanks-icon">✓</div>
        <h2>Спасибо за заявку!</h2>
        <p>Мы свяжемся с вами в ближайшее время</p>
        <button class="btn-orange modal-btn" onclick="hideModal('thanks-modal')">Ок</button>
    </div>
</div>

<div id="participate-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="hideModal('participate-modal')">&times;</span>
        <h2>Приведи друга</h2>
        <div class="promo-detail">
            <div class="promo-icon">🎁</div>
            <h3>500 ₽ в подарок</h3>
            <p>Приведите друга — получите скидку 500₽ на следующий заезд</p>
            <p class="promo-code">ПРОМОКОД: FRIEND500</p>
            <p class="promo-info">Промокод действует при бронировании от 5 суток</p>
            <button class="btn-orange modal-btn" onclick="hideModal('participate-modal')">Понятно</button>
        </div>
    </div>
</div>

<div id="details-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="hideModal('details-modal')">&times;</span>
        <h2>Бесплатное зоотакси</h2>
        <div class="promo-detail">
            <div class="promo-icon">🚕</div>
            <h3>Действует всегда</h3>
            <p>При бронировании от 7 ночей — привезём питомца бесплатно</p>
            <p class="promo-info">Акция распространяется на все районы Санкт-Петербурга</p>
            <ul class="promo-list">
                <li>✓ Бесплатный трансфер в обе стороны</li>
                <li>✓ Автомобиль с климат-контролем</li>
                <li>✓ Опытный водитель-зооняня</li>
            </ul>
            <button class="btn-orange modal-btn" onclick="hideModal('details-modal')">Хорошо</button>
        </div>
    </div>
</div>

<script>
// Слайдер для номеров
function initRoomsSlider() {
    const track = document.getElementById('roomsTrack');
    const prevBtn = document.getElementById('roomsPrev');
    const nextBtn = document.getElementById('roomsNext');
    const dots = document.querySelectorAll('#roomsDots .dot');
    
    if (!track || !prevBtn || !nextBtn || !dots.length) return;
    
    const slides = track.children;
    const slideCount = slides.length;
    let currentIndex = 0;
    
    function updateSlider() {
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }
    
    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + slideCount) % slideCount;
        updateSlider();
    });
    
    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slideCount;
        updateSlider();
    });
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            updateSlider();
        });
    });
}

// Слайдер для отзывов
function initReviewsSlider() {
    const track = document.getElementById('reviewsTrack');
    const prevBtn = document.getElementById('reviewsPrev');
    const nextBtn = document.getElementById('reviewsNext');
    const dots = document.querySelectorAll('#reviewsDots .dot');
    
    if (!track || !prevBtn || !nextBtn || !dots.length) return;
    
    const slides = track.children;
    const slideCount = slides.length;
    let currentIndex = 0;
    
    function updateSlider() {
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }
    
    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + slideCount) % slideCount;
        updateSlider();
    });
    
    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % slideCount;
        updateSlider();
    });
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            updateSlider();
        });
    });
}

// Функция для открытия модалки с выбранным номером
function openBookingWithRoom(roomId, roomTitle, roomPrice) {
    document.getElementById('booking-room-id').value = roomId;
    document.getElementById('booking-room-price').value = roomPrice;
    
    const roomInfo = document.createElement('div');
    roomInfo.className = 'selected-room-info';
    roomInfo.innerHTML = `<strong>Выбранный номер:</strong> ${roomTitle} (${roomPrice} ₽/сутки)`;
    
    const existingInfo = document.querySelector('.selected-room-info');
    if (existingInfo) existingInfo.remove();
    
    const form = document.getElementById('booking-form');
    form.insertBefore(roomInfo, form.firstChild);
    
    showModal('booking-modal');
    calculateTotal();
}

// Расчет общей стоимости
function calculateTotal() {
    const dateFrom = document.getElementById('date-from').value;
    const dateTo = document.getElementById('date-to').value;
    const roomPrice = parseFloat(document.getElementById('booking-room-price').value) || 0;
    const serviceCheckboxes = document.querySelectorAll('input[name="services[]"]:checked');
    
    let total = 0;
    
    if (dateFrom && dateTo) {
        const start = new Date(dateFrom);
        const end = new Date(dateTo);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        if (days > 0) {
            total += roomPrice * days;
        }
    }
    
    serviceCheckboxes.forEach(cb => {
        total += parseFloat(cb.dataset.price);
    });
    
    document.getElementById('total-price-display').textContent = total;
}

// Обработчики событий
document.addEventListener('DOMContentLoaded', function() {
    initRoomsSlider();
    initReviewsSlider();
    
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>