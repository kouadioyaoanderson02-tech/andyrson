
<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "smartexam_db");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur
$user_query = $conn->query("SELECT * FROM utilisateurs WHERE id = $user_id");
$user = $user_query->fetch_assoc();

// Récupérer les statistiques
$exams_count = $conn->query("SELECT COUNT(*) as count FROM examens")->fetch_assoc()['count'];
$courses_count = $conn->query("SELECT COUNT(*) as count FROM cours")->fetch_assoc()['count'];
$participations = $conn->query("SELECT COUNT(*) as count FROM participations WHERE utilisateur_id = $user_id")->fetch_assoc()['count'];

// Récupérer les derniers examens
$recent_exams = $conn->query("
    SELECT e.titre, e.id as examen_id, p.score, p.date_passage,
           (SELECT COUNT(*) FROM examen_questions WHERE examen_id = e.id) as total_questions
    FROM participations p 
    JOIN examens e ON p.examen_id = e.id 
    WHERE p.utilisateur_id = $user_id 
    ORDER BY p.date_passage DESC 
    LIMIT 5
");

// Calculer la moyenne
$avg_percent = 0;
if ($participations > 0) {
    $avg_result = $conn->query("
        SELECT AVG((p.score / eq_count.total_q) * 100) as avg_percent
        FROM participations p
        JOIN (
            SELECT examen_id, COUNT(*) as total_q 
            FROM examen_questions 
            GROUP BY examen_id
        ) eq_count ON p.examen_id = eq_count.examen_id
        WHERE p.utilisateur_id = $user_id
    ");
    if ($avg_result) {
        $avg_row = $avg_result->fetch_assoc();
        $avg_percent = $avg_row['avg_percent'] ?? 0;
    }
}

// Calculer le meilleur score
$best_percent = 0;
if ($participations > 0) {
    $best = $conn->query("
        SELECT MAX(p.score) as max_score, eq_count.total_q
        FROM participations p
        JOIN (
            SELECT examen_id, COUNT(*) as total_q 
            FROM examen_questions 
            GROUP BY examen_id
        ) eq_count ON p.examen_id = eq_count.examen_id
        WHERE p.utilisateur_id = $user_id
    ");
    if ($best) {
        $best_row = $best->fetch_assoc();
        if ($best_row['max_score'] && $best_row['total_q']) {
            $best_percent = ($best_row['max_score'] / $best_row['total_q']) * 100;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartExam</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="css/dashbord.css">
    
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
            margin-bottom: 40px;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2);
        }
        
        .page-header h1 {
            color: white;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 0;
        }
        
        .page-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 1.1rem;
        }
        
        .stat-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .stat-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.25);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #666;
            font-size: 1rem;
            margin-top: 10px;
        }
        
        .section-title {
            color: #333;
            font-weight: bold;
            font-size: 1.8rem;
            margin: 40px 0 25px 0;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }
        
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ddd;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            🎓 SmartEXAM
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-home"></i> Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="examen.php">
                        <i class="fas fa-file-alt"></i> Examens
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cours.php">
                        <i class="fas fa-book"></i> Cours
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="resultat.php">
                        <i class="fas fa-chart-bar"></i> Résultats
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1>Bienvenue 👋</h1>
        <p><?php echo htmlspecialchars($user['prenom'] ?? 'Étudiant'); ?>, prêt à réviser ?</p>
    </div>
</div>

<div class="container">
    
    <!-- Statistiques -->
    <h2 class="section-title">Vos statistiques</h2>
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="stat-box">
                <div class="stat-number"><?php echo $courses_count; ?></div>
                <div class="stat-label">
                    <i class="fas fa-book"></i> Cours disponibles
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-box">
                <div class="stat-number"><?php echo $exams_count; ?></div>
                <div class="stat-label">
                    <i class="fas fa-file-alt"></i> Examens
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-box">
                <div class="stat-number"><?php echo $participations; ?></div>
                <div class="stat-label">
                    <i class="fas fa-check-circle"></i> Examens passés
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-box">
                <div class="stat-number">
                    <?php echo round($avg_percent, 1); ?>%
                </div>
                <div class="stat-label">
                    <i class="fas fa-star"></i> Moyenne
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes d'action rapide -->
    <h2 class="section-title">Actions rapides</h2>
    <div class="row mb-5 g-4">
        <div class="col-lg-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h1>📚</h1>
                    <h4>Cours</h4>
                    <p>Découvrez nos ressources pédagogiques</p>
                    <a href="cours.php" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h1>📝</h1>
                    <h4>Examens</h4>
                    <p>Testez vos connaissances</p>
                    <a href="examen.php" class="btn btn-success">
                        <i class="fas fa-arrow-right"></i> Commencer
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h1>📊</h1>
                    <h4>Résultats</h4>
                    <p>Suivez votre progression</p>
                    <a href="resultat.php" class="btn btn-warning">
                        <i class="fas fa-arrow-right"></i> Voir
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h1>👤</h1>
                    <h4>Profil</h4>
                    <p>Gérez vos informations</p>
                    <a href="#" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers examens passés -->
    <div class="row">
        <div class="col-lg-8">
            <h2 class="section-title">Historique des examens</h2>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history"></i> Vos derniers examens
                </div>
                <div class="card-body">
                    <?php if ($recent_exams->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-book"></i> Examen</th>
                                        <th><i class="fas fa-percentage"></i> Score</th>
                                        <th><i class="fas fa-calendar"></i> Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($exam = $recent_exams->fetch_assoc()): 
                                        $pourcentage = ($exam['score'] / $exam['total_questions']) * 100;
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($exam['titre']); ?></td>
                                            <td>
                                                <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                                    <?php echo $exam['score']; ?>/<?php echo $exam['total_questions']; ?> (<?php echo round($pourcentage, 1); ?>%)
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($exam['date_passage'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <p>Aucun examen passé pour le moment.</p>
                            <a href="examen.php" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-right"></i> Commencer un examen
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Barre de progression -->
        <div class="col-lg-4">
            <h2 class="section-title">Progression</h2>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-line"></i> Votre progression
                </div>
                <div class="card-body">
                    <?php 
                    $progress = min(($participations * 20), 100);
                    ?>
                    <div class="mb-3">
                        <p class="text-muted">Objectif: Passer 5 examens</p>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: <?php echo $progress; ?>%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"
                                 aria-valuenow="<?php echo $progress; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo round($progress, 0); ?>%
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white;">
                        <i class="fas fa-lightbulb"></i> <strong>Conseil:</strong> Faites au moins un examen chaque jour pour améliorer vos résultats.
                    </div>

                    <div class="mt-3">
                        <h6>Statistiques</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check" style="color: #38ef7d;"></i> 
                                Examens réussis: <strong><?php echo $participations; ?></strong>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-star" style="color: #f5576c;"></i> 
                                Meilleur score: <strong><?php echo round($best_percent, 1); ?>%</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>