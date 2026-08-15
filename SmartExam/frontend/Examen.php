<?php

session_start();


// Vérification de connexion

if(!isset($_SESSION['user_id'])){

    header("Location: login.php");

    exit();

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Examens - SmartExam</title>

<link rel="stylesheet" href="css/examen.css">

</head>


<body>


<header>

<div class="logo">

<h1>SmartEXAM</h1>

</div>


<nav>

<a href="dashboard.php">Accueil</a>

<a href="examen.php">Examens</a>

<a href="cours.php">Cours</a>

<a href="resultat.php">Résultats</a>

<a href="logout.php">Déconnexion</a>

</nav>


</header>



<main class="container">


<h1>
📚 Examens disponibles
</h1>


<p>
Choisissez un examen blanc et testez vos connaissances.
</p>



<div class="examens">



<div class="examen-card">


<h2> INFAS</h2>


<p>
Mathématique
</p>


<p>
Questions : 20 QCM
</p>


<p>
Durée : 25 min
</p>


<a href="quiz.php?id=1">

Commencer

</a>


</div>




<div class="examen-card">


<h2>CAFOP</h2>
<p>
Matière : Francais
</p>
<p>
Questions : 25 QCM
</p>
<p>
Durée : 45 minutes
</p>
<a href="quiz.php?id=2">
Commencer

</a>


</div>




<div class="examen-card">


<h2>ENA</h2>


<p>
Matière : Dissertation francais
</p>


<p>
Questions : 30 QCM
</p>


<p>
Durée : 90 minutes
</p>


<a href="quiz.php?id=3">

Commencer

</a>


</div>



</div>


</main>


</body>

</html>