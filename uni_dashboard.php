<?php
require 'header.php';
session_start();

// Проверка авторизации
if (!isset($_SESSION['uni_logged_in']) || !$_SESSION['uni_logged_in']) {
    header('Location: uni_login.php');
    exit;
}

// Получаем данные университета
$university_name = $_SESSION['uni_university_name'] ?? 'Неизвестный университет';
$country = $_SESSION['uni_country'] ?? '';
$logo_url = $_SESSION['uni_logo_url'] ?? '';
$university_id = $_SESSION['uni_university_id'] ?? 0;

try {
    // Подключение к базе данных
    require_once 'config/database.php';

    if (!isset($pdo)) {
        $host = 'localhost';
        $dbname = 'exchange_platform';
        $username = 'root';
        $db_password = '';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Получаем статистику по программам университета
    $programs_stmt = $pdo->prepare("SELECT COUNT(*) as total_programs FROM programs WHERE university_id = ?");
    $programs_stmt->execute([$university_id]);
    $total_programs = $programs_stmt->fetchColumn();

    // Получаем статистику по заявкам
    $applications_stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_applications,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_applications,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_applications,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_applications
        FROM applications a
        JOIN programs p ON a.program_id = p.id
        WHERE p.university_id = ?
    ");
    $applications_stmt->execute([$university_id]);
    $applications_stats = $applications_stmt->fetch(PDO::FETCH_ASSOC);

    // Получаем последние программы
    $recent_programs_stmt = $pdo->prepare("
        SELECT * FROM programs 
        WHERE university_id = ? 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $recent_programs_stmt->execute([$university_id]);
    $recent_programs = $recent_programs_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Получаем последние заявки
    $recent_applications_stmt = $pdo->prepare("
        SELECT a.*, p.title as program_title 
        FROM applications a
        JOIN programs p ON a.program_id = p.id
        WHERE p.university_id = ?
        ORDER BY a.application_date DESC
        LIMIT 5
    ");
    $recent_applications_stmt->execute([$university_id]);
    $recent_applications = $recent_applications_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database error in uni_dashboard.php: " . $e->getMessage());
    $error_message = "Ошибка загрузки данных";
}
?>

    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 5% auto;
            padding: 2rem;
        }

        .university-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .university-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .university-info h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .university-info p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .user-controls{
            display: none;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .content-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #667eea;
        }

        .list-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .item-title {
            font-weight: 500;
            color: #2d3748;
        }

        .item-meta {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-approved {
            background: #d1fae5;
            color: #059669;
        }

        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
        }

        .btn-logout:hover {
            background: #dc2626;
        }

        .no-data {
            text-align: center;
            color: #6b7280;
            padding: 2rem;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .university-header {
                flex-direction: column;
                text-align: center;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>

    <div class="dashboard-container">
        <div class="university-header">
            <?php if ($logo_url): ?>
                <img src="<?= htmlspecialchars($logo_url) ?>" alt="Логотип" class="university-logo">
            <?php else: ?>
                <div class="university-logo" style="background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-university" style="font-size: 2rem;"></i>
                </div>
            <?php endif; ?>
            <div class="university-info">
                <h1><?= htmlspecialchars($university_name) ?></h1>
                <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($country) ?></p>
            </div>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php else: ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_programs ?></div>
                    <div class="stat-label">Программы обмена</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $applications_stats['total_applications'] ?? 0 ?></div>
                    <div class="stat-label">Всего заявок</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $applications_stats['pending_applications'] ?? 0 ?></div>
                    <div class="stat-label">Ожидают рассмотрения</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $applications_stats['approved_applications'] ?? 0 ?></div>
                    <div class="stat-label">Одобрено</div>
                </div>
            </div>

            <div class="content-grid">
                <div class="content-section">
                    <h2 class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Последние программы
                    </h2>
                    <?php if (!empty($recent_programs)): ?>
                        <?php foreach ($recent_programs as $program): ?>
                            <div class="list-item">
                                <div>
                                    <div class="item-title"><?= htmlspecialchars($program['title']) ?></div>
                                    <div class="item-meta">
                                        <?= htmlspecialchars($program['field_of_study']) ?> •
                                        <?= htmlspecialchars($program['duration']) ?> •
                                        <?= htmlspecialchars($program['language']) ?>
                                    </div>
                                </div>
                                <?php if ($program['rating']): ?>
                                    <div class="item-meta">
                                        <i class="fas fa-star" style="color: #fbbf24;"></i>
                                        <?= number_format($program['rating'], 1) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">Программы не найдены</div>
                    <?php endif; ?>
                </div>

                <div class="content-section">
                    <h2 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Последние заявки
                    </h2>
                    <?php if (!empty($recent_applications)): ?>
                        <?php foreach ($recent_applications as $application): ?>
                            <div class="list-item">
                                <div>
                                    <div class="item-title"><?= htmlspecialchars($application['program_title']) ?></div>
                                    <div class="item-meta">
                                        <?= date('d.m.Y H:i', strtotime($application['application_date'])) ?>
                                    </div>
                                </div>
                                <span class="status-badge status-<?= $application['status'] ?>">
                                <?php
                                switch ($application['status']) {
                                    case 'pending': echo 'Ожидает'; break;
                                    case 'approved': echo 'Одобрено'; break;
                                    case 'rejected': echo 'Отклонено'; break;
                                }
                                ?>
                            </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">Заявки не найдены</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="action-buttons">
            <a href="uni_programs.php" class="btn btn-primary">
                <i class="fas fa-graduation-cap"></i>
                Управление программами
            </a>
            <a href="uni_applications.php" class="btn btn-secondary">
                <i class="fas fa-file-alt"></i>
                Заявки студентов
            </a>
            <a href="uni_change.php" class="btn btn-primary">
                <i class="fas fa-graduation-cap"></i>
редактировать профиль
            </a>
        </div>
    </div>

<?php require 'includes/footer.php'; ?>