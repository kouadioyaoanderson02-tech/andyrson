<?php
session_start();

// Vérification de la connexion utilisateur
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "smartexam_db");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer l'ID de l'examen
$examen_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($examen_id == 0) {
    header("Location: examen.php");
    exit();
}

// Récupérer les informations de l'examen
$examen_query = $conn->query("SELECT * FROM examens WHERE id = $examen_id");
$examen = $examen_query->fetch_assoc();

if (!$examen) {
    header("Location: examen.php");
    exit();
}

// Récupérer toutes les questions de l'examen
$questions_query = $conn->query("
    SELECT q.* FROM questions q
    INNER JOIN examen_questions eq ON q.id = eq.question_id
    WHERE eq.examen_id = $examen_id
    ORDER BY eq.id ASC
");

$questions = [];
while ($row = $questions_query->fetch_assoc()) {
    $questions[] = $row;
}

// Traiter la soumission du formulaire
$score = 0;
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submitted = true;
    
    // Compter les bonnes réponses
    foreach ($questions as $question) {
        $q_id = $question['id'];
        $user_answer = $_POST["question_$q_id"] ?? null;
        
        if ($user_answer === $question['bonne_reponse']) {
            $score++;
        }
    }
    
    // Calculer le pourcentage
    $pourcentage = count($questions) > 0 ? ($score / count($questions)) * 100 : 0;
    
    // Enregistrer la participation
    $user_id = $_SESSION['user_id'];
    
    $conn->query("
        INSERT INTO participations (utilisateur_id, examen_id, score)
        VALUES ('$user_id', '$examen_id', '$score')
    ");
    
    // Rediriger vers les résultats
    header("Location: resultat.php?examen_id=$examen_id&score=$score&total=" . count($questions) . "&pourcentage=$pourcentage");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz -SmartExam <?php echo htmlspecialchars($examen['titre']); ?> - SmartExam</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="css/quiz.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background-color: rgba(0, 0, 0, 0.8) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.8rem;
            color: #667eea !important;
        }
        
        .quiz-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin: 40px 0;
            overflow: hidden;
        }
        
        .quiz-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .quiz-header h1 {
            margin: 0;
            font-weight: bold;
            font-size: 2rem;
        }
        
        .timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff6b6b;
        }
        
        .quiz-body {
            padding: 30px;
        }
        
        .question-number {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .question-text {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        
        .option {
            margin-bottom: 15px;
        }
        
        .option input[type="radio"] {
            margin-right: 10px;
            cursor: pointer;
        }
        
        .option label {
            cursor: pointer;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            display: block;
            transition: all 0.3s ease;
            margin: 0;
        }
        
        .option input[type="radio"]:checked + label {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .option label:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 30px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .btn-back a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-back a:hover {
            color: #764ba2;
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
                    <a class="nav-link" href="dashboard.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="examen.php">Examens</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?php if (!$submitted): ?>
    
    <div class="quiz-container">
        <!-- En-tête du Quiz -->
        <div class="quiz-header">
            <h1><?php echo htmlspecialchars($examen['titre']); ?></h1>
            <p class="mb-0 mt-2">
                <i class="fas fa-clock"></i> Durée : <?php echo $examen['duree']; ?> minutes
            </p>
            <p class="mb-0">
                <i class="fas fa-list"></i> Nombre de questions : <?php echo count($questions); ?>
            </p>
        </div>
        
        <!-- Barre de progression -->
        <div class="quiz-body">
            <div class="progress mb-4" style="height: 5px;">
                <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
            </div>
            
            <!-- Formulaire du Quiz -->
            <form method="POST" id="quizForm">
                <?php foreach ($questions as $index => $question): ?>
                
                <div class="question-card mb-5">
                    <div class="question-number">
                        Question <?php echo $index + 1; ?> sur <?php echo count($questions); ?>
                    </div>
                    
                    <div class="question-text">
                        <?php echo htmlspecialchars($question['question']); ?>
                    </div>
                    
                    <!-- Options de réponse -->
                    <div class="options">
                        <?php
                        $options = [
                            'A' => ['label' => 'A)', 'value' => $question['choix_a']],
                            'B' => ['label' => 'B)', 'value' => $question['choix_b']],
                            'C' => ['label' => 'C)', 'value' => $question['choix_c']],
                            'D' => ['label' => 'D)', 'value' => $question['choix_d']]
                        ];
                        
                        foreach ($options as $key => $option):
                        ?>
                        <div class="option">
                            <input type="radio" 
                                   id="q<?php echo $question['id']; ?>_<?php echo $key; ?>" 
                                   name="question_<?php echo $question['id']; ?>" 
                                   value="<?php echo $key; ?>"
                                   required>
                            <label for="q<?php echo $question['id']; ?>_<?php echo $key; ?>">
                                <strong><?php echo $option['label']?></strong> <?php echo htmlspecialchars($option['value']); ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php endforeach; ?>
                
                <!-- Bouton Soumettre -->
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check-circle"></i> Soumettre les réponses
                </button>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Mise à jour de la barre de progression
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('quizForm');
        const progressBar = document.getElementById('progressBar');
        const inputs = form.querySelectorAll('input[type="radio"]');
        
        function updateProgress() {
            const checked = form.querySelectorAll('input[type="radio"]:checked').length;
            const total = inputs.length / 4; // 4 options par question
            const percentage = (checked / total) * 100;
            progressBar.style.width = percentage + '%';
        }
        
        inputs.forEach(input => {
            input.addEventListener('change', updateProgress);
        });
        
        // Confirmation avant de soumettre
        form.addEventListener('submit', function(e) {
            const checked = form.querySelectorAll('input[type="radio"]:checked').length;
            const total = inputs.length / 4;
            
            if (checked < total) {
                e.preventDefault();
                if (!confirm('Vous n\'avez pas répondu à toutes les questions. Êtes-vous sûr de vouloir continuer ?')) {
                    return false;
                }
            }
        });
    });
</script>

</body>

</html>
 




</header>

    <a href="exam.php"> INFAS





    </a>






 </body>   

</head>

















</html>