<div class="footer-container">
    <div class="footer-navbar">
        <img src="access/image/logo.svg" alt="Котейка">
        <nav class="footer-nav">
            <ul>
                <li><a href="index.php#why">Почему мы?</a></li>
                <li><a href="cataloge.php">Номера</a></li>
                <li><a href="rules.php">Правила</a></li>
                <li><a href="blog.php">Блог</a></li>
                <ul><a href="about.php">О нас</a></ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php" class="footer-profile-link">Мой профиль</a></li>
                    <li><a href="logout.php" class="footer-logout-link">Выйти</a></li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="admin/index.php" class="footer-admin-link">Админ-панель</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="login.php" class="footer-login-link">Вход</a></li>
                    <li><a href="register.php" class="footer-register-link">Регистрация</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<hr>

<!-- Анимированный котик -->
<div class="cat-mascot" style="position: fixed; bottom: 20px; right: 20px; width: 80px; height: 80px; cursor: pointer; z-index: 999;">
    <svg viewBox="0 0 100 100" style="width: 100%; height: 100%;">
        <circle cx="50" cy="50" r="45" fill="#FF9800"/>
        <circle cx="30" cy="40" r="5" fill="white"/>
        <circle cx="70" cy="40" r="5" fill="white"/>
        <circle cx="32" cy="42" r="2" fill="black"/>
        <circle cx="72" cy="42" r="2" fill="black"/>
        <path d="M40 65 Q50 75 60 65" stroke="white" stroke-width="3" fill="none"/>
        <path d="M20 30 L30 25 L25 35 Z" fill="white"/>
        <path d="M80 30 L70 25 L75 35 Z" fill="white"/>
        <path d="M50 55 L50 70" stroke="white" stroke-width="2"/>
    </svg>
</div>

<footer>
    <div class="footer-bottom">
        <p>© 2026 Все права защищены</p>
        <div class="text-conf">
            <p class="text-p2" onclick="window.location.href='privacy.php'" style="cursor: pointer;">Политика конфиденциальности</p>
        </div>
    </div>
</footer>

<script>
// Функции модальных окон
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

document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal');
    const closeBtns = document.querySelectorAll('.close-modal');
    
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            modals.forEach(modal => modal.style.display = 'none');
            document.body.style.overflow = 'auto';
        });
    });
    
    window.addEventListener('click', function(e) {
        modals.forEach(modal => {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    });
});
// Анимация котика при клике
document.querySelector('.cat-mascot').addEventListener('click', function() {
    this.style.animation = 'catJump 0.5s ease';
    setTimeout(() => {
        this.style.animation = '';
    }, 500);
    
    // Показываем сообщение
    alert('Мяу! Спасибо, что заглянули в Котейку! 🐱');
});

document.addEventListener('DOMContentLoaded', function() {
    const burger = document.getElementById('burgerMenu');
    const nav = document.getElementById('mainNav');
    
    // Создаем оверлей
    const overlay = document.createElement('div');
    overlay.className = 'menu-overlay';
    document.body.appendChild(overlay);
    
    // Функция открытия/закрытия меню
    function toggleMenu() {
        burger.classList.toggle('active');
        nav.classList.toggle('active');
        overlay.classList.toggle('active');
        
        // Блокируем прокрутку body при открытом меню
        if (nav.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
    
    // Открытие/закрытие по клику на бургер
    burger.addEventListener('click', toggleMenu);
    
    // Закрытие по клику на оверлей
    overlay.addEventListener('click', toggleMenu);
    
    // Закрытие по клику на ссылку в меню
    const navLinks = nav.querySelectorAll('a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (nav.classList.contains('active')) {
                toggleMenu();
            }
        });
    });
    
    // Закрытие при изменении размера окна (если меню открыто)
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992 && nav.classList.contains('active')) {
            toggleMenu();
        }
    });
    
    // Закрытие по кнопке ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && nav.classList.contains('active')) {
            toggleMenu();
        }
    });
});
// Параллакс эффект при скролле
document.addEventListener('DOMContentLoaded', function() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(element => {
            const speed = 0.5;
            const yPos = -(scrolled * speed);
            element.style.backgroundPositionY = yPos + 'px';
        });
    });
});
</script>
<script src="js/particles.js"></script>
</body>
</html>