<?php

session_start();


// Vérification connexion

if(!isset($_SESSION['user_id'])){

    header("Location: login.php");

    exit();

}


$user_id = $_SESSION['user_id'];

?>


<!DOCTYPE html>
<html lang="fr">


<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Mes résultats - SmartExam</title>

<link rel="stylesheet" href="css/resultat.css">

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
📊 Mes résultats
</h1>


<p>
Consultez votre progression et vos performances.
</p>




<div class="resultats">


<table>


<thead>

<tr>

<th>Examen</th>

<th>Matière</th>

<th>Score</th>

<th>Date</th>

<th>Statut</th>

</tr>

</thead>



<tbody>


<tr>

<td>BTS Informatique</td>

<td>Java</td>

<td>18/20</td>

<td>15/08/2026</td>

<td class="success">
Réussi
</td>


</tr>



<tr>

<td>Base de données</td>

<td>SQL</td>

<td>12/20</td>

<td>14/08/2026</td>

<td class="warning">
Moyen
</td>


</tr>



<tr>

<td>Algorithmique</td>

<td>Algorithme</td>

<td>8/20</td>

<td>13/08/2026</td>

<td class="danger">
À améliorer
</td>


</tr>



</tbody>


</table>


</div>



<div class="statistique">


<h2>
Ma progression
</h2>


<div class="progress-bar">


<div class="progress">

65%
</div>
</div>
</div>
</main>
</body>
</html>