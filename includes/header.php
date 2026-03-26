<?php
// Получаем текущую страницу
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Котейка — Уютная гостиница для кошек</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;700&family=Rubik:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="access/css/style.css">
    <link rel="stylesheet" href="access/css/header.css">
    <link rel="stylesheet" href="access/css/footer.css">
    <link rel="stylesheet" href="access/css/animations.css">
    <link rel="stylesheet" href="access/css/visual-effects.css">
    <?php if ($current_page == 'cataloge.php'): ?>
        <link rel="stylesheet" href="access/css/cataloge.css">
    <?php elseif ($current_page == 'blog.php'): ?>
        <link rel="stylesheet" href="access/css/blog.css">
    <?php elseif ($current_page == 'rules.php'): ?>
        <link rel="stylesheet" href="access/css/rules.css">
    <?php elseif ($current_page == 'about.php'): ?>
        <link rel="stylesheet" href="access/css/about.css">
    <?php elseif ($current_page == 'privacy.php'): ?>
        <link rel="stylesheet" href="access/css/privacy.css">
    <?php endif; ?>
</head>
<body>

    <div class="header-wrapper">
        <div class="container header-container">
            <div class="navbar">
                <a href="index.php" class="logo">
                    <img src="access/image/logo.svg" alt="Котейка">
                </a>
                
                <!-- Кнопка бургер-меню -->
                <div class="burger-menu" id="burgerMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
                <!-- Навигация -->
                <nav class="main-nav" id="mainNav">
                    <ul>
                        <li><a href="index.php#why">Почему мы?</a></li>
                        <li><a href="cataloge.php">Номера</a></li>
                        <li><a href="rules.php">Правила</a></li>
                        <li><a href="blog.php">Блог</a></li>
                        <li><a href="about.php">О нас</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="profile.php" class="profile-link">Мой профиль</a></li>
                            <li><a href="logout.php" class="logout-link">Выйти</a></li>
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <li><a href="admin/index.php" class="admin-link">Админ-панель</a></li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li><a href="login.php" class="login-link">Вход</a></li>
                            <li><a href="register.php" class="register-link">Регистрация</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <script>
// Определение типа устройства для оптимизации
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
const isTablet = /iPad|Android/i.test(navigator.userAgent) && window.innerWidth >= 768 && window.innerWidth <= 1024;

if (isMobile) {
    document.documentElement.classList.add('is-mobile');
    document.documentElement.classList.remove('is-desktop');
} else if (isTablet) {
    document.documentElement.classList.add('is-tablet');
} else {
    document.documentElement.classList.add('is-desktop');
}
</script>