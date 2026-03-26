<?php
// Подключаем конфигурацию БД
$pdo = require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// Получаем все опубликованные статьи
try {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY created_date DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
    error_log("Error fetching posts: " . $e->getMessage());
}
?>

<div class="blog-container">
    <h1 class="page-title">Блог</h1>
    <p class="page-subtitle">Полезные статьи о кошках и новости отеля</p>

    <div class="blog-grid">
        <?php if (empty($posts)): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px;">
                <p>Статьи временно отсутствуют. Скоро появятся новые публикации!</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
            <article class="blog-card">
                <?php if (!empty($post['image_url'])): ?>
                <div class="blog-card-image">
                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
                <?php endif; ?>
                <div class="blog-card-content">
                    <div class="blog-date"><?php echo date('d/m/Y', strtotime($post['created_date'])); ?></div>
                    <h2 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="blog-excerpt"><?php echo htmlspecialchars(substr(strip_tags($post['content']), 0, 120)) . '...'; ?></p>
                    <button class="btn-read-more" onclick="showArticle(<?php echo $post['id']; ?>)">Читать далее</button>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Секция "Будь в курсе" -->
    <section class="subscribe-section">
        <div class="subscribe-content">
            <h2>Будь в курсе</h2>
            <p>Подпишитесь на нашу рассылку и получайте полезные статьи о кошках, новости отеля и специальные предложения</p>
            
            <form class="subscribe-form" action="subscribe-process.php" method="POST">
                <input type="email" name="email" placeholder="Ваш e-mail" required>
                <button type="submit" class="btn-subscribe">Подписаться</button>
            </form>
            
            <?php if (isset($_SESSION['subscribe_success']) && $_SESSION['subscribe_success'] === true): ?>
                <div style="color: #4caf50; margin-top: 10px; text-align: center;">
                    ✓ Спасибо за подписку!
                </div>
                <?php unset($_SESSION['subscribe_success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['subscribe_error'])): ?>
                <div style="color: #f44336; margin-top: 10px; text-align: center;">
                    ⚠ <?php echo $_SESSION['subscribe_error']; ?>
                </div>
                <?php unset($_SESSION['subscribe_error']); ?>
            <?php endif; ?>
            
            <p class="subscribe-note">Нажимая на кнопку, вы соглашаетесь с политикой конфиденциальности</p>
        </div>
        <div class="subscribe-image">
            <img src="access/image/cat_subscribe.png" alt="Кошка">
        </div>
    </section>
</div>

<!-- Модальное окно для статьи -->
<div id="article-modal" class="modal">
    <div class="modal-content article-content">
        <span class="close-modal" onclick="hideModal('article-modal')">&times;</span>
        <div class="article-full">
            <div class="article-image" id="modal-image-container">
                <img id="modal-image" src="" alt="">
            </div>
            <h2 id="modal-title"></h2>
            <div class="article-meta" id="modal-date"></div>
            <div class="article-body" id="modal-body"></div>
            <button class="btn-orange close-article" onclick="hideModal('article-modal')">Закрыть</button>
        </div>
    </div>
</div>

<script>
// Показ статьи
function showArticle(postId) {
    fetch(`get-article.php?id=${postId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modal-title').textContent = data.title;
                document.getElementById('modal-date').textContent = data.created_date;
                document.getElementById('modal-body').innerHTML = data.content;
                if (data.image_url) {
                    const modalImg = document.getElementById('modal-image');
                    modalImg.src = data.image_url;
                    document.getElementById('modal-image-container').style.display = 'block';
                } else {
                    document.getElementById('modal-image-container').style.display = 'none';
                }
                showModal('article-modal');
            } else {
                alert('Не удалось загрузить статью');
            }
        })
        .catch(err => {
            console.error('Ошибка:', err);
            alert('Произошла ошибка при загрузке статьи');
        });
}

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Закрытие модального окна по клику вне его
document.getElementById('article-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideModal('article-modal');
    }
});

// Закрытие по ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideModal('article-modal');
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>