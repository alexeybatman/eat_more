<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Программы международного обмена</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .filters {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .filters h3 {
            color: #2d3748;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2d3748;
        }

        .filter-group select,
        .filter-group input {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .program-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-subtitle {
            color: #667eea;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .card-code {
            background: #e2e8f0;
            color: #4a5568;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #4a5568;
        }

        .info-item i {
            color: #667eea;
            width: 16px;
        }

        .disciplines-preview {
            margin-bottom: 1rem;
        }

        .disciplines-preview h4 {
            font-size: 0.9rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .disciplines-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .discipline-tag {
            background: #f7fafc;
            color: #4a5568;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid #e2e8f0;
            font-weight: 500;
        }

        .discipline-tag.more {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .card-features {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .feature-badge {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.5rem 1rem;
            background: #f0fff4;
            color: #22543d;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #c6f6d5;
        }

        .feature-badge.credits {
            background: #fff5f5;
            color: #742a2a;
            border-color: #fed7d7;
        }

        .feature-badge.price {
            background: #ebf8ff;
            color: #2b6cb0;
            border-color: #bee3f8;
        }

        .feature-badge i {
            font-size: 0.7rem;
        }

        .card-footer {
            padding: 1rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .view-details-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            justify-content: center;
        }

        .view-details-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        /* Попап стили */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .popup-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .popup {
            background: white;
            border-radius: 20px;
            max-width: 1000px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            transform: scale(0.8);
            transition: all 0.3s ease;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .popup-overlay.show .popup {
            transform: scale(1);
        }

        .popup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            position: relative;
        }

        .popup-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .popup-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .popup-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .popup-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .popup-code {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            display: inline-block;
        }

        .popup-content {
            padding: 2rem;
        }

        .popup-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .popup-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .popup-section h3 {
            color: #2d3748;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .popup-section h3 i {
            color: #667eea;
        }

        .disciplines-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .disciplines-table th,
        .disciplines-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .disciplines-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #2d3748;
        }

        .disciplines-table tr:hover {
            background: #f8fafc;
        }

        .disciplines-table tr:last-child td {
            border-bottom: none;
        }

        .semester-badge {
            background: #e2e8f0;
            color: #4a5568;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .credits-badge {
            background: #fff5f5;
            color: #742a2a;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #fed7d7;
        }

        .price-badge {
            background: #ebf8ff;
            color: #2b6cb0;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #bee3f8;
        }

        .competencies-list {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 12px;
            margin-top: 0.5rem;
        }

        .competencies-list ul {
            list-style: none;
            padding: 0;
        }

        .competencies-list li {
            padding: 0.25rem 0;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .competencies-list li::before {
            content: '•';
            color: #667eea;
            font-weight: bold;
        }

        .costs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .cost-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .cost-item h4 {
            font-size: 1rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cost-item p {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2b6cb0;
        }

        .cost-item .note {
            font-size: 0.8rem;
            color: #718096;
            margin-top: 0.5rem;
            font-style: italic;
        }

        .apply-btn {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            justify-content: center;
            margin-top: 2rem;
        }

        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .programs-grid {
                grid-template-columns: 1fr;
            }

            .card-info {
                grid-template-columns: 1fr;
            }

            .popup {
                width: 95%;
            }

            .popup-content {
                padding: 1rem;
            }

            .disciplines-table {
                font-size: 0.9rem;
            }

            .disciplines-table th,
            .disciplines-table td {
                padding: 0.5rem;
            }

            .costs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<?php require 'header.php'; ?>
<body>
<div class="container">
    <div class="header" style="margin-top: 25%;">
        <h1><i class="fas fa-university"></i> Программы международного обмена</h1>
        <p>Выберите университет и направление для международного академического обмена</p>
    </div>

    <div class="filters">
        <h3><i class="fas fa-filter"></i> Фильтры поиска</h3>
        <div class="filter-grid">
            <div class="filter-group">
                <label for="filter-university">ВУЗ</label>
                <select id="filter-university">
                    <option value="">Все университеты</option>
                    <option value="МГУ">МГУ им. М.В. Ломоносова</option>
                    <option value="СПбГУ">СПбГУ</option>
                    <option value="МФТИ">МФТИ</option>
                    <option value="НИУ ВШЭ">НИУ ВШЭ</option>
                    <option value="МГИМО">МГИМО</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-direction">Направление</label>
                <select id="filter-direction">
                    <option value="">Все направления</option>
                    <option value="Математика">Математика</option>
                    <option value="Физика">Физика</option>
                    <option value="Информатика">Информатика</option>
                    <option value="Экономика">Экономика</option>
                    <option value="Международные отношения">Международные отношения</option>
                    <option value="Лингвистика">Лингвистика</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-semester">Семестр</label>
                <select id="filter-semester">
                    <option value="">Любой семестр</option>
                    <option value="1">1 семестр</option>
                    <option value="2">2 семестр</option>
                    <option value="3">3 семестр</option>
                    <option value="4">4 семестр</option>
                    <option value="5">5 семестр</option>
                    <option value="6">6 семестр</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-credits">Зачетные единицы</label>
                <select id="filter-credits">
                    <option value="">Любое количество</option>
                    <option value="2">2 единицы</option>
                    <option value="3">3 единицы</option>
                    <option value="4">4 единицы</option>
                    <option value="5">5 единиц</option>
                    <option value="6">6 единиц</option>
                </select>
            </div>
        </div>
    </div>

    <div class="programs-grid" id="programs-grid">
        <!-- Программы будут добавлены через JavaScript -->
    </div>
</div>

<!-- Попап для подробной информации -->
<div class="popup-overlay" id="popup-overlay">
    <div class="popup">
        <div class="popup-header">
            <button class="popup-close" onclick="closePopup()">
                <i class="fas fa-times"></i>
            </button>
            <h2 class="popup-title" id="popup-title"></h2>
            <p class="popup-subtitle" id="popup-subtitle"></p>
            <div class="popup-code" id="popup-code"></div>
        </div>
        <div class="popup-content" id="popup-content">
            <!-- Контент будет добавлен через JavaScript -->
        </div>
    </div>
</div>

<script>
    // Данные программ университетов с финансовой информацией
    const universityPrograms = [
        {
            id: 1,
            university: "МГУ",
            code: "01.03.01",
            direction: "Математика",
            duration: "1 семестр",
            financialInfo: {
                tuition: 0, // Бесплатно по обмену
                dormitory: 15000, // руб/мес
                insurance: 5000, // руб/семестр
                visa: 10000, // руб
                additional: 20000, // руб (перелет, питание и пр.)
                scholarship: 20000, // руб/мес (стипендия по обмену)
                note: "Программа поддерживается государственной стипендией"
            },
            disciplines: [
                {
                    name: "Математический анализ",
                    credits: 6,
                    semester: 1,
                    description: "Основы математического анализа, теория пределов, дифференциальное и интегральное исчисление",
                    competencies: ["Способность к абстрактному мышлению", "Умение решать математические задачи", "Владение методами математического анализа"]
                },
                {
                    name: "Линейная алгебра",
                    credits: 4,
                    semester: 1,
                    description: "Векторные пространства, линейные операторы, системы линейных уравнений",
                    competencies: ["Владение методами линейной алгебры", "Способность к логическому мышлению", "Умение работать с матрицами"]
                },
                {
                    name: "Теория вероятностей",
                    credits: 5,
                    semester: 2,
                    description: "Основы теории вероятностей, случайные величины, распределения",
                    competencies: ["Понимание стохастических процессов", "Умение анализировать случайные события", "Владение статистическими методами"]
                }
            ]
        },
        {
            id: 2,
            university: "МФТИ",
            code: "03.03.02",
            direction: "Физика",
            duration: "2 семестра",
            financialInfo: {
                tuition: 0,
                dormitory: 12000,
                insurance: 5000,
                visa: 10000,
                additional: 25000,
                scholarship: 25000,
                note: "Программа включает лабораторные практикумы"
            },
            disciplines: [
                {
                    name: "Общая физика",
                    credits: 6,
                    semester: 1,
                    description: "Механика, термодинамика, электродинамика, основы квантовой физики",
                    competencies: ["Понимание физических явлений", "Умение проводить физические эксперименты", "Владение методами физических измерений"]
                },
                {
                    name: "Квантовая механика",
                    credits: 5,
                    semester: 3,
                    description: "Основы квантовой механики, волновая функция, операторы",
                    competencies: ["Понимание квантовых явлений", "Умение решать задачи квантовой механики", "Владение математическим аппаратом"]
                },
                {
                    name: "Статистическая физика",
                    credits: 4,
                    semester: 4,
                    description: "Статистические ансамбли, термодинамические функции, фазовые переходы",
                    competencies: ["Понимание статистических закономерностей", "Умение анализировать макроскопические системы", "Владение методами статистической физики"]
                }
            ]
        },
        {
            id: 3,
            university: "НИУ ВШЭ",
            code: "09.03.03",
            direction: "Информатика",
            duration: "1 семестр",
            financialInfo: {
                tuition: 50000, // руб/семестр
                dormitory: 18000,
                insurance: 5000,
                visa: 10000,
                additional: 30000,
                scholarship: 0,
                note: "Возможны корпоративные скидки от партнеров университета"
            },
            disciplines: [
                {
                    name: "Программирование",
                    credits: 5,
                    semester: 1,
                    description: "Основы программирования, алгоритмы, структуры данных",
                    competencies: ["Владение языками программирования", "Умение разрабатывать алгоритмы", "Способность к решению вычислительных задач"]
                },
                {
                    name: "Базы данных",
                    credits: 4,
                    semester: 2,
                    description: "Проектирование и управление базами данных, SQL, NoSQL",
                    competencies: ["Умение проектировать базы данных", "Владение языком SQL", "Понимание принципов хранения данных"]
                },
                {
                    name: "Машинное обучение",
                    credits: 6,
                    semester: 5,
                    description: "Алгоритмы машинного обучения, нейронные сети, глубокое обучение",
                    competencies: ["Понимание методов машинного обучения", "Умение создавать модели ИИ", "Владение инструментами анализа данных"]
                }
            ]
        },
        {
            id: 4,
            university: "СПбГУ",
            code: "38.03.01",
            direction: "Экономика",
            duration: "2 семестра",
            financialInfo: {
                tuition: 35000,
                dormitory: 10000,
                insurance: 5000,
                visa: 10000,
                additional: 22000,
                scholarship: 15000,
                note: "Программа включает стажировки в банках и корпорациях"
            },
            disciplines: [
                {
                    name: "Микроэкономика",
                    credits: 4,
                    semester: 1,
                    description: "Поведение потребителей, теория производства, рыночные структуры",
                    competencies: ["Понимание микроэкономических процессов", "Умение анализировать рыночные механизмы", "Владение экономическими моделями"]
                },
                {
                    name: "Макроэкономика",
                    credits: 5,
                    semester: 2,
                    description: "Национальная экономика, денежно-кредитная политика, международная торговля",
                    competencies: ["Понимание макроэкономических процессов", "Умение анализировать экономическую политику", "Владение методами экономического анализа"]
                },
                {
                    name: "Эконометрика",
                    credits: 4,
                    semester: 3,
                    description: "Статистические методы в экономике, регрессионный анализ, прогнозирование",
                    competencies: ["Владение статистическими методами", "Умение строить экономические модели", "Способность к количественному анализу"]
                }
            ]
        },
        {
            id: 5,
            university: "МГИМО",
            code: "41.03.05",
            direction: "Международные отношения",
            duration: "1 семестр",
            financialInfo: {
                tuition: 75000,
                dormitory: 20000,
                insurance: 5000,
                visa: 15000,
                additional: 35000,
                scholarship: 0,
                note: "Программа включает дипломатические стажировки"
            },
            disciplines: [
                {
                    name: "Теория международных отношений",
                    credits: 5,
                    semester: 1,
                    description: "Основные теории и концепции международных отношений, геополитика",
                    competencies: ["Понимание международных процессов", "Умение анализировать геополитические тенденции", "Владение теоретическим аппаратом"]
                },
                {
                    name: "Дипломатия",
                    credits: 4,
                    semester: 2,
                    description: "История дипломатии, дипломатический протокол, переговорный процесс",
                    competencies: ["Владение дипломатическим протоколом", "Умение вести переговоры", "Понимание принципов дипломатии"]
                },
                {
                    name: "Международное право",
                    credits: 5,
                    semester: 3,
                    description: "Основы международного права, международные организации, разрешение споров",
                    competencies: ["Знание международного права", "Умение применять правовые нормы", "Понимание международных институтов"]
                }
            ]
        }
    ];

    // Функция для форматирования денежных значений
    function formatMoney(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' ₽';
    }

    // Функция для создания карточки программы
    function createProgramCard(program) {
        const visibleDisciplines = program.disciplines.slice(0, 3);
        const remainingCount = program.disciplines.length - 3;
        const totalCredits = program.disciplines.reduce((sum, disc) => sum + disc.credits, 0);
        const totalCost = program.financialInfo.tuition +
            (program.financialInfo.dormitory * (program.duration.includes('2') ? 10 : 5)) +
            program.financialInfo.insurance +
            program.financialInfo.visa +
            program.financialInfo.additional;

        return `
                <div class="program-card" onclick="openPopup(${program.id})">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-university"></i>
                            ${program.university}
                        </h3>
                        <p class="card-subtitle">${program.direction}</p>
                        <span class="card-code">Код: ${program.code}</span>
                    </div>
                    <div class="card-body">
                        <div class="card-info">
                            <div class="info-item">
                                <i class="fas fa-book"></i>
                                <span>${program.disciplines.length} дисциплин</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-credit-card"></i>
                                <span>${totalCredits} зач. единиц</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <span>${program.duration}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-ruble-sign"></i>
                                <span>${formatMoney(totalCost)}</span>
                            </div>
                        </div>

                        <div class="disciplines-preview">
                            <h4>Дисциплины:</h4>
                            <div class="disciplines-tags">
                                ${visibleDisciplines.map(disc => `<span class="discipline-tag">${disc.name}</span>`).join('')}
                                ${remainingCount > 0 ? `<span class="discipline-tag more">+${remainingCount}</span>` : ''}
                            </div>
                        </div>

                        <div class="card-features">
                            <span class="feature-badge credits">
                                <i class="fas fa-award"></i>
                                ${totalCredits} зач. ед.
                            </span>
                            <span class="feature-badge price">
                                <i class="fas fa-ruble-sign"></i>
                                ${formatMoney(totalCost)}
                            </span>
                            <span class="feature-badge">
                                <i class="fas fa-calendar"></i>
                                ${program.duration}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="view-details-btn">
                            <i class="fas fa-info-circle"></i>
                            Подробнее
                        </button>
                    </div>
                </div>
            `;
    }

    // Функция для отображения программ
    function displayPrograms(programsToShow = universityPrograms) {
        const grid = document.getElementById('programs-grid');
        grid.innerHTML = programsToShow.map(program => createProgramCard(program)).join('');
    }

    // Функция для открытия попапа
    function openPopup(programId) {
        const program = universityPrograms.find(p => p.id === programId);
        if (!program) return;

        document.getElementById('popup-title').textContent = program.university;
        document.getElementById('popup-subtitle').textContent = program.direction;
        document.getElementById('popup-code').textContent = `Код: ${program.code}`;

        // Рассчитываем общую стоимость
        const dormitoryMonths = program.duration.includes('2') ? 10 : 5;
        const totalDormitory = program.financialInfo.dormitory * dormitoryMonths;
        const totalCost = program.financialInfo.tuition + totalDormitory +
            program.financialInfo.insurance + program.financialInfo.visa +
            program.financialInfo.additional;

        // Заполняем контент попапа
        const popupContent = document.getElementById('popup-content');
        popupContent.innerHTML = `
                <div class="popup-section">
                    <h3><i class="fas fa-info-circle"></i> Основная информация</h3>
                    <div class="card-info">
                        <div class="info-item">
                            <i class="fas fa-book"></i>
                            <span>${program.disciplines.length} дисциплин</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-credit-card"></i>
                            <span>${program.disciplines.reduce((sum, disc) => sum + disc.credits, 0)} зач. единиц</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <span>Продолжительность: ${program.duration}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-ruble-sign"></i>
                            <span>Общая стоимость: ${formatMoney(totalCost)}</span>
                        </div>
                    </div>
                </div>

                <div class="popup-section">
                    <h3><i class="fas fa-money-bill-wave"></i> Финансовая информация</h3>
                    <div class="costs-grid">
                        <div class="cost-item">
                            <h4><i class="fas fa-graduation-cap"></i> Обучение</h4>
                            <p>${formatMoney(program.financialInfo.tuition)}</p>
                            <div class="note">${program.financialInfo.tuition === 0 ? 'Бесплатно по программе обмена' : 'За весь период обучения'}</div>
                        </div>
                        <div class="cost-item">
                            <h4><i class="fas fa-home"></i> Проживание</h4>
                            <p>${formatMoney(totalDormitory)}</p>
                            <div class="note">${program.financialInfo.dormitory} ₽/мес × ${dormitoryMonths} месяцев</div>
                        </div>
                        <div class="cost-item">
<h4><i class="fas fa-shield-alt"></i> Медицинская страховка</h4>
<p>${formatMoney(program.financialInfo.insurance)}</p>
<div class="note">На весь период программы</div>
</div>
<div class="cost-item">
<h4><i class="fas fa-passport"></i> Виза</h4>
<p>${formatMoney(program.financialInfo.visa)}</p>
<div class="note">Оформление учебной визы</div>
</div>
<div class="cost-item">
<h4><i class="fas fa-plane"></i> Дополнительные расходы</h4>
<p>${formatMoney(program.financialInfo.additional)}</p>
<div class="note">Перелет, питание, учебные материалы</div>
</div>
${program.financialInfo.scholarship > 0 ? <div class="cost-item"> <h4><i class="fas fa-gift"></i> Стипендия</h4> <p style="color: #38a169;">+${formatMoney(program.financialInfo.scholarship * dormitoryMonths)}</p> <div class="note">${formatMoney(program.financialInfo.scholarship)} ₽/мес × ${dormitoryMonths} месяцев</div> </div> : ''}
</div>
<p style="margin-top: 1rem; font-style: italic; color: #4a5568;">${program.financialInfo.note}</p>
</div>
                        <div class="popup-section">
                <h3><i class="fas fa-book-open"></i> Дисциплины программы</h3>
                <table class="disciplines-table">
                    <thead>
                        <tr>
                            <th>Дисциплина</th>
                            <th>Описание</th>
                            <th>Семестр</th>
                            <th>Зач. единицы</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${program.disciplines.map(discipline => `
                            <tr>
                                <td><strong>${discipline.name}</strong></td>
                                <td>${discipline.description}</td>
                                <td><span class="semester-badge">${discipline.semester} семестр</span></td>
                                <td><span class="credits-badge">${discipline.credits} ед.</span></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div class="competencies-list">
                                        <ul>
                                            ${discipline.competencies.map(comp => `<li>${comp}</li>`).join('')}
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>

            <button class="apply-btn" onclick="applyToProgram(${program.id})">
                <i class="fas fa-paper-plane"></i>
                Подать заявку на участие
            </button>
        `;

        // Показываем попап
        document.getElementById('popup-overlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    // Функция для закрытия попапа
    function closePopup() {
        document.getElementById('popup-overlay').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Функция для подачи заявки
    function applyToProgram(programId) {
        const program = universityPrograms.find(p => p.id === programId);
        if (!program) return;

        alert(`Заявка на программу "${program.direction}" в ${program.university} успешно отправлена!`);
        closePopup();
    }

    // Функция для фильтрации программ
    function filterPrograms() {
        const university = document.getElementById('filter-university').value;
        const direction = document.getElementById('filter-direction').value;
        const semester = document.getElementById('filter-semester').value;
        const credits = document.getElementById('filter-credits').value;

        const filteredPrograms = universityPrograms.filter(program => {
            return (university === '' || program.university === university) &&
                (direction === '' || program.direction === direction) &&
                (semester === '' || program.disciplines.some(d => d.semester == semester)) &&
                (credits === '' || program.disciplines.some(d => d.credits == credits));
        });

        displayPrograms(filteredPrograms);
    }

    // Инициализация страницы
    document.addEventListener('DOMContentLoaded', () => {
        displayPrograms();

        // Навешиваем обработчики на фильтры
        document.querySelectorAll('#filter-university, #filter-direction, #filter-semester, #filter-credits').forEach(
            filter => filter.addEventListener('change', filterPrograms)
        );
    });

    // Закрытие попапа при клике вне его области
    document.getElementById('popup-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closePopup();
        }
    });
</script>
</body> </html>