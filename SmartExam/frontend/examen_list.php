<?php

session_start();

// Vérification connexion utilisateur
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "smartexam_db");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer l'ID de la matière
$matiere_id = isset($_GET['matiere_id']) ? (int)$_GET['matiere_id'] : 0;

if ($matiere_id == 0) {
    header("Location: examen.php");
    exit();
}

// Récupérer les informations de la matière
$matiere_query = $conn->query("SELECT * FROM matieres WHERE id = $matiere_id");
$matiere = $matiere_query->fetch_assoc();

if (!$matiere) {
    header("Location: examen.php");
    exit();
}

// Récupérer le concours associé
$concours_query = $conn->query("SELECT * FROM concours WHERE id = " . $matiere['concours_id']);
$concours = $concours_query->fetch_assoc();

// Récupérer tous les examens de cette matière
$examens_query = $conn->query("
    SELECT e.* FROM examens e
    JOIN examen_questions eq ON e.id = eq.examen_id
    JOIN questions q ON eq.question_id = q.id
    WHERE q.matiere_id = $matiere_id
    GROUP BY e.id
");

$examens = [];
while ($row = $examens_query->fetch_assoc()) {
    // Compter les questions
    $count_query = $conn->query("
        SELECT COUNT(*) as total FROM examen_questions 
        WHERE examen_id = " . $row['id']
    );
    $count = $count_query->fetch_assoc()['total'];
    $row['total_questions'] = $count;
    $examens[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examens - <?php echo htmlspecialchars($matiere['nom']); ?> - SmartExam</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        
        .breadcrumb-custom {
            background: transparent;
            padding: 10px 0;
            margin: 0;
        }
        
        .breadcrumb-custom a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
        }
        
        .breadcrumb-custom a:hover {
            color: white;
        }
        
        .exam-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            cursor: pointer;
            height: 100%;
        }
        
        .exam-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
        }
        
        .exam-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.25);
        }
        
        .exam-body {
            padding: 30px;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .exam-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        
        .exam-info {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9ff;
            border-radius: 10px;
        }
        
        .info-item {
            text-align: center;
        }
        
        .info-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }
        
        .exam-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            flex-grow: 1;
        }
        
        .btn-start {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-start:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
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
        
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            color: #ffd43b;
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
                    <a class="nav-link active" href="examen.php">
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

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="container">
        <a href="examen.php" class="back-button">
            <i class="fas fa-arrow-left"></i> Retour aux concours
        </a>
        <h1>
            <i class="fas fa-book"></i> <?php echo htmlspecialchars($matiere['nom']); ?>
        </h1>
        <p><?php echo htmlspecialchars($concours['nom']); ?> - <?php echo htmlspecialchars($matiere['description'] ?? ''); ?></p>
    </div>
</div>

<!-- CONTENU -->
<div class="container py-5">
    
    <?php if (count($examens) > 0): ?>
        <div class="row g-4">
            <?php foreach ($examens as $exam): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card exam-card">
                        <div class="exam-body">
                            <h3 class="exam-title">
                                📝 <?php echo htmlspecialchars($exam['titre']); ?>
                            </h3>
                            
                            <div class="exam-info">
                                <div class="info-item">
                                    <div class="info-value"><?php echo $exam['total_questions']; ?></div>
                                    <div class="info-label">Questions</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-value"><?php echo $exam['duree']; ?>'</div>
                                    <div class="info-label">Durée</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-value"><?php echo htmlspecialchars($exam['niveau'] ?? 'N/A'); ?></div>
                                    <div class="info-label">Niveau</div>
                                </div>
                            </div>
                            
                            <p class="exam-description">
                                Testez vos connaissances avec cet examen de <?php echo $exam['total_questions']; ?> questions.
                            </p>
                            
                            <a href="quiz.php?id=<?php echo $exam['id']; ?>" class="btn btn-start">
                                <i class="fas fa-play-circle"></i> Commencer l'examen
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <p>Aucun examen disponible pour cette matière.</p>
            <a href="examen.php" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left"></i> Retour aux concours
            </a>
        </div>
    <?php endif; ?>
    
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
