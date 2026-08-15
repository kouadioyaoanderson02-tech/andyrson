<?php

session_start();


// Test temporaire de connexion
// À remplacer plus tard par la vraie connexion utilisateur

if(!isset($_SESSION['user_id'])){

    $_SESSION['user_id'] = 1;

}


$user_id = $_SESSION['user_id'];

?>


<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard - SmartExam</title>

<link rel="stylesheet" href="css/dashboard.css">

</head>


<body>


<header>


<div class="logo">

<h1>SmartEXAM</h1>

</div>


<!-- Navigation du tableau de bord -->

<nav>

<a href="dashboard.php">Accueil</a>

<a href="examen.php">Examens</a>

<a href="cours.php">Cours</a>

<a href="resultat.php">Résultats</a>

<a href="logout.php">Déconnexion</a>

</nav>


</header>



<main class="dashboard">


<h1>
Bienvenue dans votre espace étudiant 👋
</h1>


<p>
Votre identifiant utilisateur est :
<?php echo $user_id; ?>
</p>



<div class="cards">


<div class="card">

<h2>📚 Cours</h2>

<p>
Consultez vos supports de révision.
</p>

<a href="cours.php">
Voir les cours
</a>

</div>



<div class="card">

<h2>📝 Examens</h2>

<p>
Passez vos examens blancs.
</p>

<a href="examen.php">
Commencer
</a>

</div>



<div class="card">

<h2>📊 Résultats</h2>

<p>
Suivez votre progression.
</p>

<a href="resultat.php">
Voir résultats
</a>

</div>



</div>


</main>



</body>

</html>