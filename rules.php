<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// Получаем FAQ
try {
    $stmt = $pdo->prepare("SELECT * FROM faq WHERE is_active = 1 ORDER BY sort_order, id");
    $stmt->execute();
    $faq_items = $stmt->fetchAll();
} catch (PDOException $e) {
    $faq_items = [];
    error_log("Error fetching FAQ: " . $e->getMessage());
}
?>

<div class="rules-container">
    <h1 class="page-title">Правила и условия</h1>
    <p class="page-subtitle">Чтобы пребывание вашего питомца было комфортным и безопасным, пожалуйста, ознакомьтесь с нашими правилами.</p>

    <div class="rules-nav">
        <a href="#general">Общие правила</a>
        <a href="#safety">Правила безопасности</a>
        <a href="#payment">Оплата и отмена</a>
        <a href="#faq">Часто задаваемые вопросы</a>
        <a href="#questions">Остались вопросы?</a>
    </div>

    <!-- Общие правила -->
    <section id="general" class="rules-section">
        <div class="section-header">
            <h2>Общие правила</h2>
        </div>
        <div class="rules-grid">
            <div class="rule-card">
                <h3>Заезд и выезд</h3>
                <p>Заезд с 10:00 до 20:00, выезд до 12:00. Возможно согласование другого времени индивидуально.</p>
            </div>
            <div class="rule-card">
                <h3>Документы</h3>
                <p>При заезде необходим ветеринарный паспорт с отметками о прививках (бешенство, панлейкопения, ринотрахеит, калицивироз).</p>
            </div>
            <div class="rule-card">
                <h3>Возраст питомцев</h3>
                <p>Мы принимаем котят с 4 месяцев и взрослых кошек без ограничения возраста.</p>
            </div>
            <div class="rule-card">
                <h3>Договор</h3>
                <p>При первом обращении заключаем договор на оказание услуг.</p>
            </div>
        </div>
    </section>

    <!-- Правила безопасности -->
    <section id="safety" class="rules-section">
        <div class="section-header">
            <h2>Правила безопасности</h2>
        </div>
        <div class="rules-grid">
            <div class="rule-card">
                <h3>Окна и балконы</h3>
                <p>Во всех номерах установлены сетки "антикошка". Просьба не открывать окна без необходимости.</p>
            </div>
            <div class="rule-card">
                <h3>Электроприборы</h3>
                <p>Не оставляйте включённые нагревательные приборы без присмотра персонала.</p>
            </div>
            <div class="rule-card">
                <h3>Двери</h3>
                <p>Следите, чтобы дверь номера была плотно закрыта. Персонал проверяет каждые 2 часа.</p>
            </div>
            <div class="rule-card">
                <h3>Совместное проживание</h3>
                <p>Кошек из разных семей размещаем в разных номерах. Исключение — по запросу и после пробы.</p>
            </div>
        </div>
    </section>

    <!-- Оплата и отмена брони -->
    <section id="payment" class="rules-section">
        <div class="section-header">
            <h2>Оплата и отмена брони</h2>
        </div>
        <div class="payment-grid">
            <div class="payment-item">
                <div class="payment-label">Предоплата при бронировании</div>
                <div class="payment-value">30%</div>
            </div>
            <div class="payment-item">
                <div class="payment-label">Отмена за 7+ дней</div>
                <div class="payment-value">возврат 100%</div>
            </div>
            <div class="payment-item">
                <div class="payment-label">Отмена за 3–6 дней</div>
                <div class="payment-value">возврат 50%</div>
            </div>
            <div class="payment-item">
                <div class="payment-label">Отмена менее чем за 3 дня</div>
                <div class="payment-value">предоплата не возвращается</div>
            </div>
            <div class="payment-item">
                <div class="payment-label">Досрочный выезд</div>
                <div class="payment-value">перерасчёт за фактические дни</div>
            </div>
        </div>
    </section>

    <!-- Часто задаваемые вопросы (FAQ) -->
    <section id="faq" class="rules-section">
        <div class="section-header">
            <h2>Часто задаваемые вопросы</h2>
        </div>
        
        <?php if (empty($faq_items)): ?>
            <div class="faq-empty">
                <p>Вопросы скоро появятся. Если у вас есть вопрос, напишите нам!</p>
            </div>
        <?php else: ?>
            <div class="faq-grid">
                <?php foreach ($faq_items as $index => $item): ?>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-icon">❓</span>
                        <h3><?php echo htmlspecialchars($item['question']); ?></h3>
                        <span class="faq-toggle">▼</span>
                    </div>
                    <div class="faq-answer">
                        <p><?php echo nl2br(htmlspecialchars($item['answer'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Секция вопросов -->
    <section id="questions" class="questions-section">
        <div class="questions-content">
            <div class="questions-text">
                <h2>Остались вопросы?</h2>
                <p>Позвоните нам — мы ответим на все вопросы о проживании</p>
                <div class="phone-number">8 (800) 333-55-99</div>
                <div class="work-time">Ежедневно с 9:00 до 20:00</div>
                <button class="btn-orange" onclick="showModal('question-modal')">Задать вопрос</button>
            </div>
            <div class="questions-image">
                <img src="access/image/cat_help.png" alt="Кошка">
            </div>
        </div>
    </section>
</div>

<!-- Модальное окно для вопроса -->
<div id="question-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="hideModal('question-modal')">&times;</span>
        <h2>Задать вопрос</h2>
        
        <form id="question-form" action="question-process.php" method="POST">
            <div class="form-group">
                <label for="name">Ваше имя *</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Телефон *</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            
            <div class="form-group">
                <label for="question">Ваш вопрос *</label>
                <textarea id="question" name="question" rows="4" required></textarea>
            </div>
            
            <button type="submit" class="btn-orange modal-btn">Отправить</button>
        </form>
    </div>
</div>

<style>
/* Стили для FAQ */
.faq-grid {
    max-width: 1000px;
    margin: 0 auto;
}

.faq-item {
    background: var(--white);
    border-radius: 16px;
    margin-bottom: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.faq-item:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.faq-question {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px 25px;
    cursor: pointer;
    background: var(--white);
    transition: background 0.3s;
}

.faq-question:hover {
    background: #f9f9f9;
}

.faq-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.faq-question h3 {
    flex: 1;
    font-family: "Rubik", sans-serif;
    font-size: 18px;
    font-weight: 500;
    margin: 0;
    color: var(--dark);
}

.faq-toggle {
    font-size: 18px;
    color: var(--accent);
    transition: transform 0.3s;
    flex-shrink: 0;
}

.faq-item.active .faq-toggle {
    transform: rotate(180deg);
}

.faq-answer {
    display: none;
    padding: 0 25px 20px 64px;
    border-top: 1px solid var(--gray-medium);
    background: #fafafa;
}

.faq-item.active .faq-answer {
    display: block;
}

.faq-answer p {
    font-size: 16px;
    color: #4a5568;
    line-height: 1.6;
    margin: 20px 0;
}

.faq-empty {
    text-align: center;
    padding: 60px;
    background: #f9f9f9;
    border-radius: 20px;
    color: #666;
}

/* Адаптив для FAQ */
@media (max-width: 768px) {
    .faq-question {
        padding: 15px 20px;
        gap: 10px;
    }
    
    .faq-question h3 {
        font-size: 16px;
    }
    
    .faq-answer {
        padding: 0 20px 15px 55px;
    }
    
    .faq-icon {
        font-size: 20px;
    }
}
</style>

<script>
// Плавный скролл к секциям
document.querySelectorAll('.rules-nav a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Toggle FAQ
function toggleFaq(element) {
    const faqItem = element.closest('.faq-item');
    faqItem.classList.toggle('active');
}

// Открыть FAQ если в URL есть якорь
if (window.location.hash === '#faq') {
    document.querySelector('#faq').scrollIntoView({ behavior: 'smooth' });
}
</script>

<?php require_once 'includes/footer.php'; ?>