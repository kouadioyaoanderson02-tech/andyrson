<?php

session_start();

// Vérification connexion
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "smartexam_db");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Récupérer les résultats de l'utilisateur
$resultats_query = $conn->query("
    SELECT p.*, e.titre as examen_titre, e.duree,
           (SELECT COUNT(*) FROM examen_questions WHERE examen_id = e.id) as total_questions
    FROM participations p
    JOIN examens e ON p.examen_id = e.id
    WHERE p.utilisateur_id = $user_id
    ORDER BY p.date_passage DESC
");

$resultats = [];
$total_score = 0;
$total_questions = 0;

while ($row = $resultats_query->fetch_assoc()) {
    $pourcentage = ($row['score'] / $row['total_questions']) * 100;
    $row['pourcentage'] = $pourcentage;
    $resultats[] = $row;
    $total_score += $row['score'];
    $total_questions += $row['total_questions'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes résultats - SmartExam</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="css/resultat.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fb 0%, #e9ecf1 100%);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.8rem;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: #ffd43b !important;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            padding: 50px 0;
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
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.25);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.95rem;
            margin-top: 10px;
        }
        
        .section-title {
            color: #333;
            font-weight: bold;
            font-size: 1.8rem;
            margin: 30px 0 25px 0;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }
        
        .resultat-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .resultat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
        }
        
        .resultat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
        }
        
        .resultat-header {
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .resultat-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            flex: 1;
        }
        
        .score-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .score-badge.excellent {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .score-badge.good {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .score-badge.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .resultat-details {
            padding: 0 25px 25px 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }
        
        .detail-item {
            text-align: center;
            padding: 15px;
            background: #f8f9ff;
            border-radius: 10px;
        }
        
        .detail-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .detail-label {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }
        
        .progress-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: conic-gradient(#667eea 0deg, #667eea 360deg);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .progress-circle::after {
            content: '';
            position: absolute;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: white;
        }
        
        .progress-text {
            position: relative;
            z-index: 1;
            font-weight: bold;
            font-size: 1.5rem;
            color: #667eea;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            🎓 SmartEXAM
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
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
                    <a class="nav-link active" href="resultat.php">
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

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-chart-bar"></i> Mes résultats</h1>
        <p>Consultez votre progression et vos performances</p>
    </div>
</div>

<div class="container">
    
    <!-- STATISTIQUES GLOBALES -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-value"><?php echo count($resultats); ?></div>
            <div class="stat-label">
                <i class="fas fa-check-circle"></i> Examens passés
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?php 
                if (count($resultats) > 0) {
                    $moyennes = array_map(function($r) { return $r['pourcentage']; }, $resultats);
                    echo round(array_sum($moyennes) / count($moyennes), 1);
                } else {
                    echo '--';
                }
                %>%
            </div>
            <div class="stat-label">
                <i class="fas fa-star"></i> Moyenne générale
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">
                <?php 
                if (count($resultats) > 0) {
                    $max = max(array_map(function($r) { return $r['pourcentage']; }, $resultats));
                    echo round($max, 1);
                } else {
                    echo '--';
                }
                ?>%
            </div>
            <div class="stat-label">
                <i class="fas fa-trophy"></i> Meilleur score
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_score; ?>/<?php echo $total_questions; ?></div>
            <div class="stat-label">
                <i class="fas fa-list"></i> Total bonnes réponses
            </div>
        </div>
    </div>

    <!-- HISTORIQUE DES RÉSULTATS -->
    <h2 class="section-title">
        <i class="fas fa-history"></i> Historique détaillé
    </h2>
    
    <?php if (count($resultats) > 0): ?>
        <div class="resultats-list">
            <?php foreach ($resultats as $resultat): 
                $classe = $resultat['pourcentage'] >= 75 ? 'excellent' : ($resultat['pourcentage'] >= 50 ? 'good' : 'warning');
                $icon = $resultat['pourcentage'] >= 75 ? '✓' : ($resultat['pourcentage'] >= 50 ? '→' : '⚠');
            ?>
                <div class="resultat-card">
                    <div class="resultat-header">
                        <div class="resultat-title">
                            <i class="fas fa-book"></i> <?php echo htmlspecialchars($resultat['examen_titre']); ?>
                        </div>
                        <div class="score-badge <?php echo $classe; ?>">
                            <span><?php echo $icon; ?></span>
                            <span><?php echo round($resultat['pourcentage'], 1); ?>%</span>
                        </div>
                    </div>
                    <div class="resultat-details">
                        <div class="detail-item">
                            <div class="detail-value"><?php echo $resultat['score']; ?>/<?php echo $resultat['total_questions']; ?></div>
                            <div class="detail-label">Score</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-value"><?php echo $resultat['duree']; ?>'</div>
                            <div class="detail-label">Durée</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-value"><?php echo date('d/m/Y', strtotime($resultat['date_passage'])); ?></div>
                            <div class="detail-label">Date</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-value"><?php echo date('H:i', strtotime($resultat['date_passage'])); ?></div>
                            <div class="detail-label">Heure</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <p>Vous n'avez pas encore passé d'examen.</p>
            <a href="examen.php" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-right"></i> Commencer un examen
            </a>
        </div>
    <?php endif; ?>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>