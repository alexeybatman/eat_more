<?php
require 'header.php';
require 'db.php';

// Получаем список университетов из базы данных
$universities = $conn->query("SELECT * FROM universities ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>

    <style>
        /* Стили для страницы universities.php */
        .universities-page {
            padding: 60px 0;
            background-color: #f8f9fa;
            margin-top: 4%;
        }

        .universities-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .page-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 40px;
            color: #2d3748;
            position: relative;
        }

        .page-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #667eea, #764ba2);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        .universities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .university-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .university-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .university-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .university-content {
            padding: 25px;
        }

        .university-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .university-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #f0f0f0;
        }

        .university-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .university-country {
            font-size: 0.9rem;
            color: #718096;
            display: flex;
            align-items: center;
        }

        .university-country::before {
            content: '📍';
            margin-right: 5px;
        }

        .university-description {
            color: #4a5568;
            line-height: 1.6;
            margin: 15px 0;
        }

        .university-rating {
            display: flex;
            align-items: center;
            color: #f6ad55;
            font-weight: 600;
        }

        .university-rating::before {
            content: '★';
            margin-right: 5px;
        }

        .search-filter {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .search-filter input {
            width: 100%;
            padding: 12px 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            outline: none;
        }

        .no-results {
            text-align: center;
            grid-column: 1 / -1;
            padding: 40px;
            color: #718096;
            font-size: 1.2rem;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .universities-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 2rem;
            }
        }
    </style>

    <section class="universities-page">
        <div class="universities-container">
            <h1 class="page-title">Университеты-партнеры</h1>

            <div class="search-filter">
                <input type="text" id="university-search" placeholder="Поиск по названию или стране...">
            </div>

            <div class="universities-grid" id="universities-container">
                <?php foreach ($universities as $university): ?>
                    <div class="university-card"
                         data-name="<?= htmlspecialchars(strtolower($university['name'])) ?>"
                         data-country="<?= htmlspecialchars(strtolower($university['country'])) ?>"
                         data-description="<?= htmlspecialchars(strtolower($university['description'] ?? '')) ?>">
                        <?php if ($university['logo_url']): ?>
                            <img src="<?= htmlspecialchars($university['logo_url']) ?>" alt="<?= htmlspecialchars($university['name']) ?>" class="university-image">
                        <?php endif; ?>

                        <div class="university-content">
                            <div class="university-header">
                                <div>
                                    <h3 class="university-name"><?= htmlspecialchars($university['name']) ?></h3>
                                    <div class="university-country"><?= htmlspecialchars($university['country']) ?></div>
                                </div>
                            </div>

                            <?php if ($university['description']): ?>
                                <p class="university-description"><?= htmlspecialchars($university['description']) ?></p>
                            <?php endif; ?>

                            <div class="university-rating"><?= number_format($university['rating'], 1) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('university-search');
            const universitiesContainer = document.getElementById('universities-container');
            const universityCards = document.querySelectorAll('.university-card');

            // Создаем элемент для сообщения "Ничего не найдено"
            const noResults = document.createElement('div');
            noResults.className = 'no-results';
            noResults.textContent = 'Университеты не найдены';
            noResults.style.display = 'none';
            universitiesContainer.appendChild(noResults);

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.trim().toLowerCase();
                let hasResults = false;

                universityCards.forEach(card => {
                    const name = card.dataset.name;
                    const country = card.dataset.country;
                    const description = card.dataset.description;

                    if (name.includes(searchTerm) ||
                        country.includes(searchTerm) ||
                        description.includes(searchTerm)) {
                        card.style.display = 'block';
                        hasResults = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Показываем/скрываем сообщение "Ничего не найдено"
                noResults.style.display = hasResults ? 'none' : 'block';
            });
        });
    </script>

<?php require 'includes/footer.php'; ?>