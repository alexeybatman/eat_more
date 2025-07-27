

<?php
include 'header.php';

?>

<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <h1>Откройте мир академических возможностей</h1>
            <p>Найдите идеальную программу обмена среди более чем 1,580 образовательных программ в 240 университетах мира</p>

            <div class="hero-buttons">
                <a href="programs.php" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Найти программу
                </a>
                <a href="#how-it-works" class="btn btn-secondary2">
                    <i class="fas fa-play-circle"></i>
Спросить ИИ                </a>
            </div>

            <div class="search-bar">
                <input type="text" placeholder="Поиск по программам, университетам, странам...">
                <button type="submit">
                    <i class="fas fa-search"></i>
                    Найти
                </button>
            </div>

            <div class="stats">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">240</div>
                        <div class="stat-label">Университетов</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">1,580</div>
                        <div class="stat-label">Программ</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">8,450</div>
                        <div class="stat-label">Студентов</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">15</div>
                        <div class="stat-label">Стран</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
<?php
include 'includes/footer.php';

?>
</body>
</html>