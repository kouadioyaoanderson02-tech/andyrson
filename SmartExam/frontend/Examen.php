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

// Récupérer tous les concours
$concours_query = $conn->query("SELECT * FROM concours");
$concours_list = [];  
while ($row = $concours_query->fetch_assoc()) {
    $concours_list[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examens - SmartExam</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="css/examen.css">
    
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
        
        .page-title {
            margin-bottom: 40px;
        }
        
        .page-title h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        
        .page-title p {
            font-size: 1.1rem;
            color: #666;
        }
        
        .concours-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            cursor: pointer;
            position: relative;
        }
        
        .concours-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
        }
        
        .concours-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.25);
        }
        
        .concours-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
        }
        
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .matiere-list {
            list-style: none;
            padding: 0;
        }
        
        .matiere-item {
            padding: 15px 20px;
            margin-bottom: 10px;
            background: #f8f9ff;
            border-left: 4px solid #667eea;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .matiere-item:hover {
            background: #667eea;
            color: white;
            transform: translateX(5px);
        }
        
        .matiere-item a {
            color: inherit;
            text-decoration: none;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .matiere-arrow {
            font-size: 1.5rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
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

<!-- CONTENU -->
<div class="container py-5">
    
    <div class="text-center page-title">
        <h1>📚 Choisissez votre concours</h1>
        <p>Sélectionnez un concours puis choisissez la matière à réviser.</p>
    </div>
    
    <div class="row g-4 mt-4">
        <?php if (count($concours_list) > 0): ?>
            <?php foreach ($concours_list as $concours): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card concours-card">
                        <div class="card-body text-center">
                            <div class="concours-icon">
                                <?php 
                                $icons = [
                                    'INFAS' => '🩺',
                                    'CAFOP' => '📖',
                                    'ENA' => '🏛️',
                                    'ENS' => '🎓',
                                    'MINING' => '⛏️'
                                ];
                                echo $icons[$concours['nom']] ?? '📝';
                                ?>
                            </div>
                            <h3><?php echo htmlspecialchars($concours['nom']); ?></h3>
                            <p class="text-muted"><?php echo htmlspecialchars($concours['description'] ?? ''); ?></p>
                            <hr>
                            <button class="btn btn-custom w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#matieresModal<?php echo $concours['id']; ?>">
                                <i class="fas fa-list"></i> Choisir une matière
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Modal pour les matières -->
                <div class="modal fade" id="matieresModal<?php echo $concours['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-book"></i> Matières - <?php echo htmlspecialchars($concours['nom']); ?>
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <?php
                                // Récupérer les matières du concours
                                $conn = new mysqli("localhost", "root", "", "smartexam_db");
                                $matieres_query = $conn->query("SELECT * FROM matieres WHERE concours_id = " . $concours['id']);
                                $matieres = [];
                                while ($row = $matieres_query->fetch_assoc()) {
                                    $matieres[] = $row;
                                }
                                
                                if (count($matieres) > 0):
                                ?>
                                    <ul class="matiere-list">
                                        <?php foreach ($matieres as $matiere): ?>
                                            <li class="matiere-item">
                                                <a href="examen_list.php?matiere_id=<?php echo $matiere['id']; ?>">
                                                    <span>
                                                        <strong><?php echo htmlspecialchars($matiere['nom']); ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($matiere['description'] ?? ''); ?></small>
                                                    </span>
                                                    <span class="matiere-arrow">→</span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php 
                                else:
                                ?>
                                    <div class="empty-state">
                                        <div class="empty-state-icon">📭</div>
                                        <p>Aucune matière disponible pour ce concours.</p>
                                    </div>
                                <?php 
                                endif;
                                $conn->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <p>Aucun concours disponible pour le moment.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>


</div>


</div>


</nav>






<!-- CONTENU -->


<div class="container py-5">



<div class="text-center page-title">


<h1>

📚 Choisissez votre concours

</h1>


<p>

Sélectionnez un concours puis choisissez la matière à réviser.

</p>


</div>





<div class="row g-4 mt-4">





<!-- INFAS -->


<div class="col-lg-4 col-md-6">


<div class="card exam-card shadow">


<div class="card-body text-center">


<div class="icon">

🩺

</div>



<h3>

INFAS

</h3>



<p>

Concours santé

</p>



<hr>



<p>

<strong>Matières :</strong>

Maths, Français, SVT

</p>



<p>

<strong>Questions :</strong>

20 QCM

</p>



<a href="matiere.php?concours=INFAS"

class="btn btn-primary btn-custom">


Choisir une matière


</a>



</div>


</div>


</div>







<!-- CAFOP -->


<div class="col-lg-4 col-md-6">


<div class="card exam-card shadow">


<div class="card-body text-center">


<div class="icon">

📖

</div>



<h3>

CAFOP

</h3>



<p>

Concours enseignement

</p>



<hr>



<p>

<strong>Matières :</strong>

Français, Maths, Pédagogie

</p>



<p>

<strong>Questions :</strong>

25 QCM

</p>



<a href="matiere.php?concours=CAFOP"

class="btn btn-success btn-custom">


Choisir une matière


</a>



</div>


</div>


</div>








<!-- ENA -->


<div class="col-lg-4 col-md-6">


<div class="card exam-card shadow">


<div class="card-body text-center">


<div class="icon">

🎓

</div>



<h3>

ENA

</h3>



<p>

Administration publique

</p>



<hr>



<p>

<strong>Matières :</strong>

Droit, Economie, Culture générale

</p>



<p>

<strong>Questions :</strong>

30 QCM

</p>



<a href="matiere.php?concours=ENA"

class="btn btn-warning btn-custom">


Choisir une matière


</a>



</div>


</div>


</div>





</div>


</div>







<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>