<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="access/css/about.css">

<div class="about-page">
    <!-- Hero секция -->
    <section class="about-hero">
        <div class="about-hero-content">
            <h1>О нас</h1>
            <p>Уютная гостиница для кошек с 2018 года</p>
        </div>
    </section>

    <div class="about-container">
        <!-- История -->
        <section class="about-history">
            <div class="history-content">
                <h2>Наша история</h2>
                <div class="history-text">
                    <p>Гостиница "Котейка" открыла свои двери в 2018 году. Идея создания комфортного пространства для кошек возникла у основательницы гостиницы — Анны Петровой, когда она столкнулась с проблемой: некуда было оставить своего любимца во время отпуска.</p>
                    <p>Начинали мы с 4 небольших номеров, а сегодня это уже 12 просторных и уютных номеров разных категорий. За 8 лет работы мы приняли более 5000 гостей и получили сотни благодарных отзывов.</p>
                    <p>Наша миссия — сделать так, чтобы каждая кошка чувствовала себя как дома, а владельцы могли спокойно уезжать, зная, что их питомец в надежных руках.</p>
                </div>
            </div>
            <div class="history-image">
                <img src="access/image/about-history.jpg" alt="История Котейки">
            </div>
        </section>

        <!-- Наши преимущества -->
        <section class="about-features">
            <h2>Наши преимущества</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🏠</div>
                    <h3>Комфортные номера</h3>
                    <p>6 категорий номеров на любой вкус и бюджет. Каждый номер оборудован всем необходимым для комфортного проживания.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🩺</div>
                    <h3>Ветеринар 24/7</h3>
                    <p>Круглосуточное наблюдение специалиста. При необходимости — консультация узких специалистов.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📹</div>
                    <h3>Видеонаблюдение</h3>
                    <p>Доступ к онлайн-камерам через личный кабинет. Смотрите за питомцем в любой момент.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚕</div>
                    <h3>Зоотакси</h3>
                    <p>Бесплатный трансфер при бронировании от 7 дней. Встретим и проводим питомца.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🍗</div>
                    <h3>Сбалансированное питание</h3>
                    <p>Корма премиум-класса. Индивидуальный подход к каждому питомцу.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎮</div>
                    <h3>Игровая зона</h3>
                    <p>Ежедневные игры и развлечения. Комплексы, игрушки, лабиринты.</p>
                </div>
            </div>
        </section>

        <!-- Наша команда -->
        <section class="about-team">
            <h2>Наша команда</h2>
            <p class="team-subtitle">Люди, которые любят кошек так же, как вы</p>
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-photo">
                        <img src="access/image/team-1.jpg" alt="Анна Петрова">
                    </div>
                    <h3>Анна Петрова</h3>
                    <p class="team-position">Основатель и директор</p>
                    <p class="team-desc">Любовь к кошкам передалась от бабушки. 15 лет опыта работы с животными.</p>
                </div>
                <div class="team-card">
                    <div class="team-photo">
                        <img src="access/image/team-2.jpg" alt="Мария Соколова">
                    </div>
                    <h3>Мария Соколова</h3>
                    <p class="team-position">Ветеринарный врач</p>
                    <p class="team-desc">Стаж 10 лет. Специализация — терапия и кардиология кошек.</p>
                </div>
                <div class="team-card">
                    <div class="team-photo">
                        <img src="access/image/team-3.jpg" alt="Екатерина Волкова">
                    </div>
                    <h3>Екатерина Волкова</h3>
                    <p class="team-position">Администратор</p>
                    <p class="team-desc">Найдет подход к любому питомцу. Отвечает на все вопросы владельцев.</p>
                </div>
                <div class="team-card">
                    <div class="team-photo">
                        <img src="access/image/team-4.jpg" alt="Дмитрий Морозов">
                    </div>
                    <h3>Дмитрий Морозов</h3>
                    <p class="team-position">Зоопсихолог</p>
                    <p class="team-desc">Помогает адаптироваться тревожным кошкам. Работает с поведенческими проблемами.</p>
                </div>
            </div>
        </section>

        <!-- Наши питомцы -->
        <section class="about-pets">
            <h2>Наши постояльцы</h2>
            <p class="pets-subtitle">Некоторые из наших счастливых гостей</p>
            <div class="pets-gallery">
                <div class="pet-item">
                    <img src="access/image/pet-1.jpg" alt="Барсик">
                    <div class="pet-info">
                        <h4>Барсик</h4>
                        <p>Сиамский кот, 3 года</p>
                        <span>Проживал: 7 дней</span>
                    </div>
                </div>
                <div class="pet-item">
                    <img src="access/image/pet-2.jpg" alt="Мурка">
                    <div class="pet-info">
                        <h4>Мурка</h4>
                        <p>Британская, 5 лет</p>
                        <span>Проживал: 14 дней</span>
                    </div>
                </div>
                <div class="pet-item">
                    <img src="access/image/pet-3.jpg" alt="Снежок">
                    <div class="pet-info">
                        <h4>Снежок</h4>
                        <p>Персидский, 7 лет</p>
                        <span>Проживал: 10 дней</span>
                    </div>
                </div>
                <div class="pet-item">
                    <img src="access/image/pet-4.jpg" alt="Матильда">
                    <div class="pet-info">
                        <h4>Матильда</h4>
                        <p>Мейн-кун, 4 года</p>
                        <span>Проживал: 21 день</span>
                    </div>
                </div>
                <div class="pet-item">
                    <img src="access/image/pet-5.jpg" alt="Тигр">
                    <div class="pet-info">
                        <h4>Тигр</h4>
                        <p>Сибирский, 1 год</p>
                        <span>Проживал: 5 дней</span>
                    </div>
                </div>
                <div class="pet-item">
                    <img src="access/image/pet-6.jpg" alt="Люся">
                    <div class="pet-info">
                        <h4>Смоки</h4>
                        <p>Сфинкс, 1 год</p>
                        <span>Проживал: 12 дней</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Цифры -->
        <section class="about-stats">
            <div class="stats-wrapper">
                <div class="stat-item">
                    <div class="stat-number">8+</div>
                    <div class="stat-label">лет работы</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5000+</div>
                    <div class="stat-label">довольных гостей</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">12</div>
                    <div class="stat-label">уютных номеров</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">ветеринар</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">положительных отзывов</div>
                </div>
            </div>
        </section>

        <!-- Отзывы -->
        <section class="about-reviews">
            <h2>Что говорят наши клиенты</h2>
            <div class="reviews-slider-about">
                <div class="review-card-about">
                    <div class="review-text">"Первый раз оставляли котика в гостинице, очень переживали. Администратор Мария каждый день высылала фото, рассказывала как он себя чувствует. Мы и кот остались очень довольны!"</div>
                    <div class="review-author">— Валерия Гришаева</div>
                    <div class="review-rating">★★★★★</div>
                </div>
                <div class="review-card-about">
                    <div class="review-text">"Гостиницу нам посоветовали друзья. В "Котейке" очень хорошо заботятся о питомцах, в гостинице очень чисто. Всем рекомендую! Будем обращаться еще."</div>
                    <div class="review-author">— Екатерина Минаева</div>
                    <div class="review-rating">★★★★★</div>
                </div>
                <div class="review-card-about">
                    <div class="review-text">"Мой кот — настоящая привереда. Но в "Котейке" ему очень понравилось! Персонал нашел подход к моему питомцу. Очень благодарен!"</div>
                    <div class="review-author">— Павел Нечаев</div>
                    <div class="review-rating">★★★★★</div>
                </div>
            </div>
        </section>

        <!-- Лицензии и сертификаты -->
        <section class="about-certificates">
            <h2>Лицензии и сертификаты</h2>
            <div class="certificates-grid">
                <div class="cert-item">
                    <img src="access/image/cert-1.jpg" alt="Сертификат 1">
                    <p>Лицензия на ветеринарную деятельность</p>
                </div>
                <div class="cert-item">
                    <img src="access/image/cert-2.jpg" alt="Сертификат 2">
                    <p>Сертификат соответствия</p>
                </div>
                <div class="cert-item">
                    <img src="access/image/cert-3.jpg" alt="Сертификат 3">
                    <p>Благодарственное письмо</p>
                </div>
                <div class="cert-item">
                    <img src="access/image/cert-4.jpg" alt="Сертификат 4">
                    <p>Диплом "Лучшая гостиница 2023"</p>
                </div>
            </div>
        </section>

        <!-- Партнеры -->
        <section class="about-partners">
            <h2>Наши партнеры</h2>
            <div class="partners-grid">
                <div class="partner-logo">
                    <img src="access/image/partner-1.png" alt="Royal Canin">
                </div>
                <div class="partner-logo">
                    <img src="access/image/partner-2.png" alt="Hill's">
                </div>
                <div class="partner-logo">
                    <img src="access/image/partner-3.png" alt="Acana">
                </div>
                <div class="partner-logo">
                    <img src="access/image/partner-4.png" alt="Зоозащита">
                </div>
                <div class="partner-logo">
                    <img src="access/image/partner-5.png" alt="Ветклиника №1">
                </div>
            </div>
        </section>

        <!-- Карта и контакты -->
        <section class="about-map">
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
                        <a href="#"><img src="access/image/tg.svg" alt="Telegram"></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Форма обратной связи -->
        <section class="about-contact">
            <h2>Остались вопросы?</h2>
            <p>Заполните форму, и мы свяжемся с вами в ближайшее время</p>
            <form class="contact-form" action="contact-process.php" method="POST">
                <div class="form-row">
                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="tel" name="phone" placeholder="Телефон" required>
                </div>
                <div class="form-row">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="text" name="subject" placeholder="Тема">
                </div>
                <textarea name="message" rows="5" placeholder="Ваше сообщение" required></textarea>
                <button type="submit" class="btn-submit">Отправить сообщение</button>
            </form>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>