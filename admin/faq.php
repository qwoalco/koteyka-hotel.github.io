<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Обработка добавления/редактирования/удаления
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_faq'])) {
        $stmt = $pdo->prepare("INSERT INTO faq (question, answer, sort_order, is_active) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['question'],
            $_POST['answer'],
            (int)$_POST['sort_order'],
            isset($_POST['is_active']) ? 1 : 0
        ]);
        $_SESSION['success'] = "Вопрос добавлен";
    } elseif (isset($_POST['edit_faq'])) {
        $stmt = $pdo->prepare("UPDATE faq SET question = ?, answer = ?, sort_order = ?, is_active = ? WHERE id = ?");
        $stmt->execute([
            $_POST['question'],
            $_POST['answer'],
            (int)$_POST['sort_order'],
            isset($_POST['is_active']) ? 1 : 0,
            $_POST['faq_id']
        ]);
        $_SESSION['success'] = "Вопрос обновлен";
    } elseif (isset($_POST['delete_faq'])) {
        $stmt = $pdo->prepare("DELETE FROM faq WHERE id = ?");
        $stmt->execute([$_POST['faq_id']]);
        $_SESSION['success'] = "Вопрос удален";
    } elseif (isset($_POST['toggle_faq'])) {
        $stmt = $pdo->prepare("UPDATE faq SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$_POST['faq_id']]);
        $_SESSION['success'] = "Статус вопроса изменен";
    } elseif (isset($_POST['update_order'])) {
        $orders = $_POST['order'];
        foreach ($orders as $id => $order) {
            $stmt = $pdo->prepare("UPDATE faq SET sort_order = ? WHERE id = ?");
            $stmt->execute([(int)$order, (int)$id]);
        }
        $_SESSION['success'] = "Порядок обновлен";
    }
    header('Location: faq.php');
    exit;
}

// Получаем все вопросы
$faq_items = $pdo->query("SELECT * FROM faq ORDER BY sort_order, id")->fetchAll();

// Для редактирования
$edit_faq = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM faq WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_faq = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление FAQ - Админ-панель</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;700&family=Rubik:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/access/css/admin-faq.css">
</head>
<body>
    <div class="admin-header">
        <h1>Котейка - Админ-панель</h1>
        <div>
            Привет, <?php echo htmlspecialchars($_SESSION['admin_name']); ?> | 
            <a href="logout.php">Выйти</a>
        </div>
    </div>
    
    <div class="admin-nav">
        <a href="index.php">Главная</a>
        <a href="bookings.php">Бронирования</a>
        <a href="rooms.php">Номера</a>
        <a href="services.php">Услуги</a>
        <a href="blog.php">Блог</a>
        <a href="clients.php">Клиенты</a>
        <a href="faq.php">FAQ</a>
        <a href="reports.php">Отчеты</a>
    </div>
    
    <div class="admin-container">
        <div class="admin-faq-header">
            <h2>Управление часто задаваемыми вопросами</h2>
            <button class="btn-add" onclick="showAddForm()">+ Новый вопрос</button>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <!-- Форма добавления/редактирования -->
        <div id="faq-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="form-title">Добавить новый вопрос</h3>
                    <button class="modal-close" onclick="closeForm()">&times;</button>
                </div>
                <form method="POST" id="faq-form">
                    <input type="hidden" name="faq_id" id="faq_id">
                    <div class="form-group">
                        <label>Вопрос</label>
                        <input type="text" name="question" id="question" required placeholder="Введите вопрос">
                    </div>
                    <div class="form-group">
                        <label>Ответ</label>
                        <textarea name="answer" id="answer" rows="5" required placeholder="Введите ответ на вопрос"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Порядок сортировки</label>
                            <input type="number" name="sort_order" id="sort_order" value="0" min="0">
                            <small>Меньшее число = выше в списке</small>
                        </div>
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                                Активен (показывать на сайте)
                            </label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeForm()">Отмена</button>
                        <button type="submit" name="add_faq" id="submit-btn" class="btn-primary">Добавить вопрос</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Список FAQ -->
        <div class="faq-list">
            <h3>Все вопросы</h3>
            
            <form method="POST" id="order-form" onsubmit="return confirm('Сохранить порядок сортировки?')">
                <div class="sort-controls">
                    <label>Сортировка:</label>
                    <span>Перетащите или измените числа</span>
                </div>
                
                <div class="faq-items">
                    <?php if (empty($faq_items)): ?>
                        <div class="empty-message">
                            <p>Нет вопросов. Создайте первый вопрос!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($faq_items as $item): ?>
                        <div class="faq-card" data-id="<?php echo $item['id']; ?>">
                            <div class="faq-card-header" onclick="toggleCard(this)">
                                <div class="faq-question">
                                    <div class="faq-order">
                                        <input type="number" name="order[<?php echo $item['id']; ?>]" value="<?php echo $item['sort_order']; ?>" style="width: 50px; text-align: center; background: transparent; border: none; color: white; font-weight: bold;" onchange="updateOrder(this)">
                                    </div>
                                    <span class="faq-question-text"><?php echo htmlspecialchars($item['question']); ?></span>
                                </div>
                                <div class="faq-status <?php echo $item['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $item['is_active'] ? '✓ Активен' : '✗ Неактивен'; ?>
                                </div>
                                <div class="faq-toggle-icon">▼</div>
                            </div>
                            <div class="faq-card-body">
                                <div class="faq-answer">
                                    <?php echo nl2br(htmlspecialchars($item['answer'])); ?>
                                </div>
                                <div class="faq-actions">
                                    <button class="btn-edit" onclick='editFaq(<?php echo json_encode($item); ?>)'>Редактировать</button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Удалить вопрос?')">
                                        <input type="hidden" name="faq_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="delete_faq" class="btn-delete">Удалить</button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="faq_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="toggle_faq" class="btn-toggle">
                                            <?php echo $item['is_active'] ? '📌 Снять с публикации' : '🚀 Опубликовать'; ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($faq_items)): ?>
                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit" name="update_order" class="btn-primary">Сохранить порядок</button>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <script>
        function showAddForm() {
            document.getElementById('form-title').textContent = 'Добавить новый вопрос';
            document.getElementById('faq_id').value = '';
            document.getElementById('question').value = '';
            document.getElementById('answer').value = '';
            document.getElementById('sort_order').value = '0';
            document.getElementById('is_active').checked = true;
            document.getElementById('submit-btn').name = 'add_faq';
            document.getElementById('submit-btn').textContent = 'Добавить вопрос';
            document.getElementById('faq-modal').classList.add('active');
        }
        
        function editFaq(item) {
            document.getElementById('form-title').textContent = 'Редактировать вопрос';
            document.getElementById('faq_id').value = item.id;
            document.getElementById('question').value = item.question;
            document.getElementById('answer').value = item.answer;
            document.getElementById('sort_order').value = item.sort_order;
            document.getElementById('is_active').checked = item.is_active == 1;
            document.getElementById('submit-btn').name = 'edit_faq';
            document.getElementById('submit-btn').textContent = 'Сохранить изменения';
            document.getElementById('faq-modal').classList.add('active');
        }
        
        function closeForm() {
            document.getElementById('faq-modal').classList.remove('active');
        }
        
        function toggleCard(header) {
            const card = header.closest('.faq-card');
            card.classList.toggle('active');
        }
        
        function updateOrder(input) {
            const card = input.closest('.faq-card');
            const value = input.value;
            // Можно добавить автоматическое сохранение, если нужно
        }
        
        // Закрытие модального окна по клику вне его
        document.getElementById('faq-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeForm();
            }
        });
        
        // Закрытие по ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('faq-modal').classList.contains('active')) {
                closeForm();
            }
        });
    </script>
</body>
</html>