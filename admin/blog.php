<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Обработка добавления/редактирования/удаления постов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_post'])) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title'])));
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, content, excerpt, created_date, is_published) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['title'],
            $slug,
            $_POST['content'],
            $_POST['excerpt'],
            $_POST['created_date'],
            isset($_POST['is_published']) ? 1 : 0
        ]);
        $_SESSION['success'] = "Статья добавлена";
    } elseif (isset($_POST['edit_post'])) {
        $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, content = ?, excerpt = ?, created_date = ?, is_published = ? WHERE id = ?");
        $stmt->execute([
            $_POST['title'],
            $_POST['content'],
            $_POST['excerpt'],
            $_POST['created_date'],
            isset($_POST['is_published']) ? 1 : 0,
            $_POST['post_id']
        ]);
        $_SESSION['success'] = "Статья обновлена";
    } elseif (isset($_POST['delete_post'])) {
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$_POST['post_id']]);
        $_SESSION['success'] = "Статья удалена";
    } elseif (isset($_POST['toggle_publish'])) {
        $stmt = $pdo->prepare("UPDATE blog_posts SET is_published = NOT is_published WHERE id = ?");
        $stmt->execute([$_POST['post_id']]);
        $_SESSION['success'] = "Статус статьи изменен";
    }
    header('Location: blog.php');
    exit;
}

// Получаем все посты
$posts = $pdo->query("SELECT * FROM blog_posts ORDER BY created_date DESC")->fetchAll();

// Для редактирования
$edit_post = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_post = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление блогом - Админ-панель</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;700&family=Rubik:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../access/css/admin-blog.css">
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
        <a href="reports.php">Отчеты</a>
    </div>
    
    <div class="admin-container">
        <div class="admin-blog-header">
            <h2>Управление блогом</h2>
            <button class="btn-add" onclick="showAddForm()">+ Новая статья</button>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <!-- Форма добавления/редактирования -->
        <div id="blog-form-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="form-title">Добавить новую статью</h3>
                    <button class="modal-close" onclick="closeForm()">&times;</button>
                </div>
                <form method="POST" id="blog-form">
                    <input type="hidden" name="post_id" id="post_id">
                    <div class="form-group">
                        <label>Заголовок</label>
                        <input type="text" name="title" id="title" required placeholder="Введите заголовок статьи">
                    </div>
                    <div class="form-group">
                        <label>Краткое описание (анонс)</label>
                        <textarea name="excerpt" id="excerpt" rows="3" required placeholder="Краткое описание статьи для анонса..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Дата публикации</label>
                        <input type="date" name="created_date" id="created_date" required>
                    </div>
                    <div class="form-group">
                        <label>Содержание статьи (HTML)</label>
                        <textarea name="content" id="content" rows="15" required placeholder="Полный текст статьи... Поддерживается HTML: &lt;p&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;li&gt;"></textarea>
                        <small>Поддерживается HTML: &lt;p&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;li&gt;</small>
                    </div>
                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" name="is_published" id="is_published" value="1" checked>
                            Опубликовать сразу
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeForm()">Отмена</button>
                        <button type="submit" name="add_post" id="submit-btn" class="btn-primary">Добавить статью</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Список статей -->
        <div class="posts-list">
            <h3>Все статьи</h3>
            <div class="posts-grid">
                <?php if (empty($posts)): ?>
                    <div style="text-align: center; padding: 60px; color: #718096;">
                        <p>Нет статей. Создайте первую статью!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <div class="post-card-header">
                            <div class="post-date">
                                📅 <?php echo date('d.m.Y', strtotime($post['created_date'])); ?>
                            </div>
                            <div class="post-status <?php echo $post['is_published'] ? 'published' : 'draft'; ?>">
                                <?php echo $post['is_published'] ? '✓ Опубликовано' : '📝 Черновик'; ?>
                            </div>
                        </div>
                        <h4 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h4>
                        <p class="post-excerpt"><?php echo htmlspecialchars(substr(strip_tags($post['excerpt']), 0, 100)) . '...'; ?></p>
                        <div class="post-stats">
                            <span>📅 <?php echo date('d.m.Y', strtotime($post['created_at'])); ?></span>
                        </div>
                        <div class="post-actions">
                            <button class="btn-edit" onclick='editPost(<?php echo json_encode($post); ?>)'>Редактировать</button>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Удалить статью «<?php echo addslashes($post['title']); ?>»?')">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <button type="submit" name="delete_post" class="btn-delete">Удалить</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <button type="submit" name="toggle_publish" class="btn-toggle">
                                    <?php echo $post['is_published'] ? '📌 Снять с публикации' : '🚀 Опубликовать'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function showAddForm() {
            document.getElementById('form-title').textContent = 'Добавить новую статью';
            document.getElementById('post_id').value = '';
            document.getElementById('title').value = '';
            document.getElementById('excerpt').value = '';
            document.getElementById('content').value = '';
            document.getElementById('created_date').value = new Date().toISOString().split('T')[0];
            document.getElementById('is_published').checked = true;
            document.getElementById('submit-btn').name = 'add_post';
            document.getElementById('submit-btn').textContent = 'Добавить статью';
            document.getElementById('blog-form-modal').classList.add('active');
        }
        
        function editPost(post) {
            document.getElementById('form-title').textContent = 'Редактировать статью';
            document.getElementById('post_id').value = post.id;
            document.getElementById('title').value = post.title;
            document.getElementById('excerpt').value = post.excerpt;
            document.getElementById('content').value = post.content;
            document.getElementById('created_date').value = post.created_date;
            document.getElementById('is_published').checked = post.is_published == 1;
            document.getElementById('submit-btn').name = 'edit_post';
            document.getElementById('submit-btn').textContent = 'Сохранить изменения';
            document.getElementById('blog-form-modal').classList.add('active');
        }
        
        function closeForm() {
            document.getElementById('blog-form-modal').classList.remove('active');
        }
        
        // Закрытие модального окна по клику вне его
        document.getElementById('blog-form-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeForm();
            }
        });
        
        // Закрытие по ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('blog-form-modal').classList.contains('active')) {
                closeForm();
            }
        });
    </script>
</body>
</html>