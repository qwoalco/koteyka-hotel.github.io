<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="access/css/privacy.css">

<div class="privacy-page">
    <!-- Hero секция -->
    <section class="privacy-hero">
        <div class="privacy-hero-content">
            <h1>Политика конфиденциальности</h1>
            <p>Как мы защищаем ваши персональные данные</p>
        </div>
    </section>

    <div class="privacy-container">
        <!-- Навигация по странице -->
        <div class="privacy-nav">
            <a href="#general">Общие положения</a>
            <a href="#data">Собираемые данные</a>
            <a href="#usage">Использование данных</a>
            <a href="#protection">Защита данных</a>
            <a href="#rights">Права пользователей</a>
            <a href="#cookies">Cookies</a>
            <a href="#contacts">Контакты</a>
        </div>

        <!-- 1. Общие положения -->
        <section id="general" class="privacy-section">
            <h2>1. Общие положения</h2>
            <p>Настоящая Политика конфиденциальности (далее — Политика) действует в отношении всей информации, которую гостиница для кошек «Котейка» (далее — Компания) может получить о пользователях во время использования сайта <strong>https://cats-hotel.local</strong> (далее — Сайт).</p>
            <p>Использование Сайта означает безоговорочное согласие пользователя с настоящей Политикой и указанными в ней условиями обработки его персональной информации. В случае несогласия с этими условиями пользователь должен воздержаться от использования Сайта.</p>
            <div class="info-card">
                <h4>📌 Основные принципы</h4>
                <p>• Обработка персональных данных осуществляется на законной и справедливой основе</p>
                <p>• Персональные данные обрабатываются только для достижения конкретных, заранее определенных целей</p>
                <p>• Содержание и объем обрабатываемых персональных данных соответствуют заявленным целям обработки</p>
                <p>• Обеспечивается точность, достаточность и актуальность персональных данных</p>
            </div>
        </section>

        <!-- 2. Какие данные мы собираем -->
        <section id="data" class="privacy-section">
            <h2>2. Какие данные мы собираем</h2>
            <p>При использовании Сайта мы можем собирать следующие персональные данные:</p>
            
            <h3>2.1. Данные, предоставляемые пользователем</h3>
            <ul>
                <li><strong>Контактная информация:</strong> ФИО, номер телефона, адрес электронной почты</li>
                <li><strong>Информация о питомцах:</strong> имя, порода, возраст, медицинские показания</li>
                <li><strong>Информация о бронированиях:</strong> даты заезда и выезда, выбранные номера и услуги</li>
                <li><strong>Платежная информация:</strong> данные об оплате (обрабатываются через защищенные платежные системы)</li>
            </ul>
            
            <h3>2.2. Данные, собираемые автоматически</h3>
            <ul>
                <li><strong>Технические данные:</strong> IP-адрес, тип браузера, операционная система</li>
                <li><strong>Данные о посещениях:</strong> дата и время посещения, просмотренные страницы, действия на сайте</li>
                <li><strong>Cookies:</strong> файлы cookie для аутентификации и аналитики</li>
            </ul>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Тип данных</th>
                        <th>Примеры</th>
                        <th>Цель сбора</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Контактные данные</td>
                        <td>Имя, телефон, email</td>
                        <td>Связь с клиентом, обработка бронирований</td>
                    </tr>
                    <tr>
                        <td>Данные о питомцах</td>
                        <td>Имя, порода, возраст</td>
                        <td>Обеспечение правильного ухода</td>
                    </tr>
                    <tr>
                        <td>Технические данные</td>
                        <td>IP-адрес, браузер</td>
                        <td>Обеспечение работы сайта, аналитика</td>
                    </tr>
                    <tr>
                        <td>Платежные данные</td>
                        <td>Номер карты (частично)</td>
                        <td>Обработка оплаты</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- 3. Как мы используем ваши данные -->
        <section id="usage" class="privacy-section">
            <h2>3. Как мы используем ваши данные</h2>
            <p>Ваши персональные данные используются для следующих целей:</p>
            <ul>
                <li><strong>Обработка бронирований:</strong> создание, подтверждение и управление бронированиями</li>
                <li><strong>Связь с клиентами:</strong> отправка уведомлений о статусе бронирования, ответы на вопросы</li>
                <li><strong>Улучшение сервиса:</strong> анализ предпочтений клиентов для улучшения качества услуг</li>
                <li><strong>Маркетинговые рассылки:</strong> только с вашего согласия (можно отписаться в любой момент)</li>
                <li><strong>Внутренняя аналитика:</strong> изучение поведения пользователей для оптимизации сайта</li>
            </ul>
            
            <div class="info-card">
                <h4>📧 Маркетинговые рассылки</h4>
                <p>Вы можете подписаться на наши новости и специальные предложения. Отказаться от рассылки можно в любой момент, нажав на ссылку «Отписаться» в конце каждого письма или в личном кабинете.</p>
            </div>
        </section>

        <!-- 4. Защита данных -->
        <section id="protection" class="privacy-section">
            <h2>4. Защита данных</h2>
            <p>Мы принимаем все необходимые организационные и технические меры для защиты ваших персональных данных от неправомерного доступа, уничтожения, изменения, блокирования, копирования, распространения.</p>
            
            <h3>4.1. Меры защиты</h3>
            <ul>
                <li>Использование SSL-сертификата для шифрования передачи данных</li>
                <li>Регулярное обновление программного обеспечения</li>
                <li>Ограниченный доступ к персональным данным сотрудников</li>
                <li>Регулярное резервное копирование данных</li>
                <li>Защита от DDoS-атак</li>
            </ul>
            
            <h3>4.2. Передача данных третьим лицам</h3>
            <p>Мы не передаем ваши персональные данные третьим лицам, за исключением случаев, предусмотренных законодательством РФ, а также в случаях, когда это необходимо для выполнения обязательств перед вами (например, передача данных платежной системе для обработки оплаты).</p>
        </section>

        <!-- 5. Права пользователей -->
        <section id="rights" class="privacy-section">
            <h2>5. Права пользователей</h2>
            <p>Вы имеете право:</p>
            <ul>
                <li><strong>На доступ к данным:</strong> запросить информацию о том, какие ваши данные хранятся</li>
                <li><strong>На исправление:</strong> потребовать исправления неточных или неполных данных</li>
                <li><strong>На удаление:</strong> потребовать удаления ваших данных (право на забвение)</li>
                <li><strong>На ограничение обработки:</strong> потребовать временного ограничения обработки данных</li>
                <li><strong>На отзыв согласия:</strong> отозвать согласие на обработку данных в любой момент</li>
                <li><strong>На переносимость данных:</strong> получить ваши данные в структурированном формате</li>
            </ul>
            
            <p>Для реализации своих прав вы можете обратиться к нам по контактным данным, указанным в разделе «Контакты».</p>
            
            <button class="consent-btn" onclick="showConsentModal()">Управление согласиями</button>
        </section>

        <!-- 6. Cookies и аналитика -->
        <section id="cookies" class="privacy-section">
            <h2>6. Cookies и аналитика</h2>
            <p>Сайт использует файлы cookie для обеспечения функционирования сайта, а также для сбора аналитической информации.</p>
            
            <h3>6.1. Типы используемых cookie</h3>
            <ul>
                <li><strong>Необходимые cookie:</strong> обеспечивают работу сайта (авторизация, корзина)</li>
                <li><strong>Функциональные cookie:</strong> запоминают ваши предпочтения (выбор языка, фильтры)</li>
                <li><strong>Аналитические cookie:</strong> помогают понять, как посетители используют сайт</li>
            </ul>
            
            <h3>6.2. Управление cookie</h3>
            <p>Вы можете управлять cookie в настройках вашего браузера. Отключение cookie может повлиять на функциональность сайта.</p>
            
            <div class="info-card">
                <h4>🍪 Настройки cookie</h4>
                <p>Вы можете изменить настройки cookie в любое время. Для этого нажмите на иконку 🍪 в правом нижнем углу экрана.</p>
            </div>
        </section>

        <!-- 7. Контактная информация -->
        <section id="contacts" class="privacy-section">
            <h2>7. Контактная информация</h2>
            <p>По всем вопросам, связанным с обработкой персональных данных, вы можете обратиться:</p>
            <ul>
                <li><strong>По телефону:</strong> 8 (800) 333-55-99</li>
                <li><strong>По электронной почте:</strong> privacy@cat-hotel.ru</li>
                <li><strong>По почте:</strong> 191186, г. Санкт-Петербург, ул. Большая Конюшенная, д. 19</li>
            </ul>
            
            <div class="info-card">
                <h4>👤 Ответственный за обработку данных</h4>
                <p><strong>Иванова Анна Сергеевна</strong><br>
                Должность: Руководитель службы безопасности<br>
                Email: a.ivanova@cat-hotel.ru<br>
                Время работы: пн-пт с 10:00 до 18:00</p>
            </div>
        </section>

        <!-- 8. Изменения в политике -->
        <section class="privacy-section">
            <h2>8. Изменения в политике конфиденциальности</h2>
            <p>Мы оставляем за собой право вносить изменения в настоящую Политику конфиденциальности. Новая редакция вступает в силу с момента ее размещения на Сайте, если иное не предусмотрено новой редакцией.</p>
            <p>Актуальная версия всегда доступна по адресу: <strong>https://cats-hotel.local/privacy.php</strong>. Рекомендуем периодически проверять эту страницу для ознакомления с актуальной версией.</p>
        </section>

        <div class="last-updated">
            <p>Дата последнего обновления: 26 марта 2026 года</p>
        </div>
    </div>
</div>

<!-- Модальное окно управления согласиями -->
<div id="consent-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeConsentModal()">&times;</span>
        <h2>Управление согласиями</h2>
        
        <div class="consent-option">
            <label class="consent-checkbox">
                <input type="checkbox" id="consent-necessary" checked disabled>
                <span>Необходимые cookie</span>
            </label>
            <p>Обеспечивают работу сайта. Отключить невозможно.</p>
        </div>
        
        <div class="consent-option">
            <label class="consent-checkbox">
                <input type="checkbox" id="consent-functional">
                <span>Функциональные cookie</span>
            </label>
            <p>Запоминают ваши предпочтения для удобства использования.</p>
        </div>
        
        <div class="consent-option">
            <label class="consent-checkbox">
                <input type="checkbox" id="consent-analytics">
                <span>Аналитические cookie</span>
            </label>
            <p>Помогают нам улучшать работу сайта.</p>
        </div>
        
        <div class="consent-option">
            <label class="consent-checkbox">
                <input type="checkbox" id="consent-marketing">
                <span>Маркетинговые cookie</span>
            </label>
            <p>Используются для персонализации рекламы.</p>
        </div>
        
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeConsentModal()">Отмена</button>
            <button class="btn-save" onclick="saveConsent()">Сохранить настройки</button>
        </div>
    </div>
</div>

<style>
/* Дополнительные стили для модалки согласий */
.consent-option {
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
}

.consent-checkbox {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-weight: 500;
    margin-bottom: 8px;
}

.consent-checkbox input {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #FF9800;
}

.consent-option p {
    font-size: 13px;
    color: #666;
    margin-left: 30px;
}

.modal-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn-cancel {
    background: #e2e8f0;
    color: #333;
    border: none;
    padding: 10px 24px;
    border-radius: 30px;
    cursor: pointer;
}

.btn-save {
    background: #FF9800;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 30px;
    cursor: pointer;
}

.btn-save:hover {
    background: #e68900;
}
</style>

<script>
function showConsentModal() {
    // Загружаем сохраненные настройки
    document.getElementById('consent-functional').checked = localStorage.getItem('cookie_functional') === 'true';
    document.getElementById('consent-analytics').checked = localStorage.getItem('cookie_analytics') === 'true';
    document.getElementById('consent-marketing').checked = localStorage.getItem('cookie_marketing') === 'true';
    document.getElementById('consent-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeConsentModal() {
    document.getElementById('consent-modal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function saveConsent() {
    localStorage.setItem('cookie_functional', document.getElementById('consent-functional').checked);
    localStorage.setItem('cookie_analytics', document.getElementById('consent-analytics').checked);
    localStorage.setItem('cookie_marketing', document.getElementById('consent-marketing').checked);
    localStorage.setItem('cookie_consent_date', new Date().toISOString());
    
    closeConsentModal();
    alert('Настройки сохранены!');
}

// Закрытие по клику вне модалки
document.getElementById('consent-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConsentModal();
    }
});

// Закрытие по ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConsentModal();
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>