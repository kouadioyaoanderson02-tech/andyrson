<?php

session_start();


// Vérification connexion utilisateur

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


<!-- Bootstrap -->

<link 
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
rel="stylesheet">


<!-- CSS personnalisé -->

<link rel="stylesheet" href="css/examen.css">


</head>



<body>



<!-- NAVBAR -->


<nav class="navbar navbar-expand-lg navbar-dark">


<div class="container">


<a class="navbar-brand" href="dashboard.php">

🎓 SmartEXAM

</a>



<button 
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">


<span class="navbar-toggler-icon"></span>


</button>



<div class="collapse navbar-collapse" id="menu">


<ul class="navbar-nav ms-auto">


<li class="nav-item">

<a class="nav-link" href="dashboard.php">

Accueil

</a>

</li>



<li class="nav-item">

<a class="nav-link active" href="examen.php">

Examens

</a>

</li>



<li class="nav-item">

<a class="nav-link" href="cours.php">

Cours

</a>

</li>



<li class="nav-item">

<a class="nav-link" href="resultat.php">

Résultats

</a>

</li>



<li class="nav-item">

<a class="nav-link" href="logout.php">

Déconnexion

</a>

</li>



</ul>


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