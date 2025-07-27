<?php
global $BASE_URL;
$BASE_URL = '/dashboard/exchange-platform'; // корректный путь от корня веб-сервера
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Цифровая академическая платформа</title>
    <link rel="stylesheet" href="http://127.0.0.1/dashboard/exchange-platform/assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                МежВуз
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="<?= $BASE_URL ?>/index.php">Главная</a></li>
                    <li><a href="<?= $BASE_URL ?>/programs.php">Программы</a></li>
                    <li><a href="<?= $BASE_URL ?>/universities.php">Университеты</a></li>


                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Отображается, если пользователь авторизован -->
                    <div class="user-controls">
                        <a href="profile.php" class="btn btn-primary">Личный кабинет</a>
                        <a href="logout.php" class="btn btn-secondary">Выйти</a>
                    </div>
                <?php else: ?>
                    <!-- Отображается, если пользователь не авторизован -->
                    <div class="auth-buttons">
                        <a href="login.php" class="btn btn-outline-primary">Войти</a>
                        <a href="signup.php" class="btn btn-primary">Регистрация</a>
                    </div>
                <?php endif; ?>


            </div>
        </div>
    </div>
</header>

<script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Header scroll effect
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
        }
    });

    // Search functionality
    document.querySelector('.search-bar button').addEventListener('click', function() {
        const searchTerm = document.querySelector('.search-bar input').value;
        if (searchTerm.trim()) {
            window.location.href = `programs.php?search=${encodeURIComponent(searchTerm)}`;
        }
    });

    // Enter key search
    document.querySelector('.search-bar input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value;
            if (searchTerm.trim()) {
                window.location.href = `programs.php?search=${encodeURIComponent(searchTerm)}`;
            }
        }
    });

    // Animate stats on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    const finalValue = parseInt(stat.textContent.replace(/,/g, ''));
                    let currentValue = 0;
                    const increment = finalValue / 50;

                    const updateStat = () => {
                        currentValue += increment;
                        if (currentValue < finalValue) {
                            stat.textContent = Math.floor(currentValue).toLocaleString();
                            requestAnimationFrame(updateStat);
                        } else {
                            stat.textContent = finalValue.toLocaleString();
                        }
                    };

                    updateStat();
                });
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const statsSection = document.querySelector('.stats');
    if (statsSection) {
        observer.observe(statsSection);
    }
</script>

