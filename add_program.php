<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить программу обмена</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .form-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .form-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .form-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .form-content {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group label i {
            color: #667eea;
            width: 16px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        .directions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .direction-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .direction-item:hover {
            background: #e2e8f0;
        }

        .direction-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .direction-item label {
            margin: 0;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .scholarship-details {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1rem;
            border: 1px solid #e2e8f0;
        }

        .scholarship-amount {
            margin-top: 1rem;
            display: none;
        }

        .scholarship-amount.show {
            display: block;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .back-btn {
            background: #e2e8f0;
            color: #4a5568;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background: #cbd5e0;
        }

        .required {
            color: #e53e3e;
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .form-section h3 {
            color: #2d3748;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section h3 i {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-header h1 {
                font-size: 2rem;
            }

            .form-content {
                padding: 1.5rem;
            }

            .directions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-wrapper">
        <div class="form-header">
            <h1><i class="fas fa-graduation-cap"></i> Добавить программу обмена</h1>
            <p>Создайте новую программу международного обмена для вашего университета</p>
        </div>

        <div class="form-content">
            <button class="back-btn" onclick="history.back()">
                <i class="fas fa-arrow-left"></i>
                Назад к панели управления
            </button>

            <form id="programForm" method="POST" action="add_program_z.php">
                <!-- Основная информация -->
                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Основная информация</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="program_title">
                                <i class="fas fa-graduation-cap"></i>
                                Название программы <span class="required">*</span>
                            </label>
                            <input type="text" id="program_title" name="program_title" required
                                   placeholder="Например: Международный бизнес">
                        </div>

                        <div class="form-group">
                            <label for="city">
                                <i class="fas fa-map-marker-alt"></i>
                                Город <span class="required">*</span>
                            </label>
                            <input type="text" id="city" name="city" required
                                   placeholder="Например: Москва">
                        </div>

                        <div class="form-group">
                            <label for="duration">
                                <i class="fas fa-clock"></i>
                                Длительность <span class="required">*</span>
                            </label>
                            <select id="duration" name="duration" required>
                                <option value="">Выберите длительность</option>
                                <option value="1 семестр">1 семестр</option>
                                <option value="2 семестра">2 семестра</option>
                                <option value="1 год">1 год</option>
                                <option value="2 года">2 года</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="language">
                                <i class="fas fa-language"></i>
                                Язык обучения <span class="required">*</span>
                            </label>
                            <select id="language" name="language" required>
                                <option value="">Выберите язык</option>
                                <option value="Русский">Русский</option>
                                <option value="Английский">Английский</option>
                                <option value="Немецкий">Немецкий</option>
                                <option value="Французский">Французский</option>
                                <option value="Китайский">Китайский</option>
                                <option value="Испанский">Испанский</option>
                                <option value="Другой">Другой</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="min_gpa">
                                <i class="fas fa-chart-line"></i>
                                Минимальный средний балл <span class="required">*</span>
                            </label>
                            <input type="number" id="min_gpa" name="min_gpa" step="0.1" min="0" max="5" required
                                   placeholder="Например: 4.0">
                        </div>
                    </div>
                </div>

                <!-- Контактная информация -->
                <div class="form-section">
                    <h3><i class="fas fa-address-book"></i> Контактная информация</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="website">
                                <i class="fas fa-globe"></i>
                                Сайт университета
                            </label>
                            <input type="url" id="website" name="website"
                                   placeholder="https://example.com">
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email для связи <span class="required">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                   placeholder="contact@university.edu">
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i>
                                Телефон
                            </label>
                            <input type="tel" id="phone" name="phone"
                                   placeholder="+7 (999) 123-45-67">
                        </div>
                    </div>
                </div>

                <!-- Направления обучения -->
                <div class="form-section">
                    <h3><i class="fas fa-book"></i> Доступные направления обучения</h3>
                    <div class="form-group">
                        <label>Выберите направления <span class="required">*</span></label>
                        <div class="directions-grid">
                            <div class="direction-item">
                                <input type="checkbox" id="dir_business" name="directions[]" value="Бизнес">
                                <label for="dir_business">Бизнес</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_cs" name="directions[]" value="Компьютерные науки">
                                <label for="dir_cs">Компьютерные науки</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_math" name="directions[]" value="Математика">
                                <label for="dir_math">Математика</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_physics" name="directions[]" value="Физика">
                                <label for="dir_physics">Физика</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_medicine" name="directions[]" value="Медицина">
                                <label for="dir_medicine">Медицина</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_law" name="directions[]" value="Право">
                                <label for="dir_law">Право</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_art" name="directions[]" value="Искусство">
                                <label for="dir_art">Искусство</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_economics" name="directions[]" value="Экономика">
                                <label for="dir_economics">Экономика</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_psychology" name="directions[]" value="Психология">
                                <label for="dir_psychology">Психология</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_engineering" name="directions[]" value="Инженерия">
                                <label for="dir_engineering">Инженерия</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_linguistics" name="directions[]" value="Лингвистика">
                                <label for="dir_linguistics">Лингвистика</label>
                            </div>
                            <div class="direction-item">
                                <input type="checkbox" id="dir_history" name="directions[]" value="История">
                                <label for="dir_history">История</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Условия проживания и стипендия -->
                <div class="form-section">
                    <h3><i class="fas fa-home"></i> Условия проживания и финансирование</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="housing" name="housing" value="1">
                                <label for="housing">
                                    <i class="fas fa-bed"></i>
                                    Университет предоставляет жилье
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="scholarship" name="scholarship" value="1"
                                       onchange="toggleScholarshipAmount()">
                                <label for="scholarship">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Доступна стипендия
                                </label>
                            </div>
                            <div id="scholarshipAmount" class="scholarship-amount">
                                <label for="scholarship_amount">
                                    <i class="fas fa-dollar-sign"></i>
                                    Размер стипендии (в месяц)
                                </label>
                                <input type="number" id="scholarship_amount" name="scholarship_amount"
                                       placeholder="Например: 25000" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Описание программы -->
                <div class="form-section">
                    <h3><i class="fas fa-file-text"></i> Описание программы</h3>
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left"></i>
                            Подробное описание программы <span class="required">*</span>
                        </label>
                        <textarea id="description" name="description" required
                                  placeholder="Опишите программу обмена, ее особенности, преимущества и требования..."></textarea>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-plus"></i>
                    Создать программу
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleScholarshipAmount() {
        const checkbox = document.getElementById('scholarship');
        const amountDiv = document.getElementById('scholarshipAmount');

        if (checkbox.checked) {
            amountDiv.classList.add('show');
        } else {
            amountDiv.classList.remove('show');
            document.getElementById('scholarship_amount').value = '';
        }
    }

    // Валидация формы
    document.getElementById('programForm').addEventListener('submit', function(e) {
        const directions = document.querySelectorAll('input[name="directions[]"]:checked');

        if (directions.length === 0) {
            e.preventDefault();
            alert('Пожалуйста, выберите хотя бы одно направление обучения');
            return;
        }

        // Дополнительная валидация
        const requiredFields = ['program_title', 'city', 'duration', 'language', 'min_gpa', 'email', 'description'];

        for (let field of requiredFields) {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                e.preventDefault();
                alert(`Пожалуйста, заполните поле "${input.previousElementSibling.textContent.replace('*', '').trim()}"`);
                input.focus();
                return;
            }
        }
    });

    // Анимация при фокусе
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'transform 0.2s ease';
        });

        field.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
</script>
<?php include 'includes/footer.php'; ?>

</body>
</html>