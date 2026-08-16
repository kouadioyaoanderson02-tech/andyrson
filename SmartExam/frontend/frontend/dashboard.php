

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard - SmartExam</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/dashboard.css">

</head>

<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark">

<div class="container">

<a class="navbar-brand fw-bold" href="dashboard.php">

🎓 SmartEXAM

</a>

<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="menu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link active" href="dashboard.php">Accueil</a>
</li>

<li class="nav-item">
<a class="nav-link" href="examen.php">Examens</a>
</li>

<li class="nav-item">
<a class="nav-link" href="cours.php">Cours</a>
</li>

<li class="nav-item">
<a class="nav-link" href="resultat.php">Résultats</a>
</li>

<li class="nav-item">
<a class="nav-link" href="logout.php">Déconnexion</a>
</li>

</ul>

</div>

</div>

</nav>

<div class="container py-5">

<!-- Bienvenue -->

<div class="welcome-card shadow">

<h2>

Bienvenue 👋

</h2>

<p>

Vous êtes connecté sur la plateforme SmartExam.

</p>

<p>

Votre identifiant :

<strong>

<?php echo $user_id; ?>

</strong>

</p>

</div>

<!-- Statistiques -->

<div class="row mt-5 g-4">

<div class="col-lg-3 col-md-6">

<div class="card dashboard-card shadow">

<div class="card-body text-center">

<h1>📚</h1>

<h4>Cours</h4>

<p>25 cours disponibles</p>

<a href="cours.php" class="btn btn-primary">

Accéder

</a>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card dashboard-card shadow">

<div class="card-body text-center">

<h1>📝</h1>

<h4>Examens</h4>

<p>12 concours disponibles</p>

<a href="examen.php" class="btn btn-success">

Commencer

</a>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card dashboard-card shadow">

<div class="card-body text-center">

<h1>📊</h1>

<h4>Résultats</h4>

<p>Suivez vos notes</p>

<a href="resultat.php" class="btn btn-warning">

Voir

</a>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card dashboard-card shadow">

<div class="card-body text-center">

<h1>👤</h1>

<h4>Profil</h4>

<p>Modifier votre profil</p>

<a href="#" class="btn btn-secondary">

Profil

</a>

</div>

</div>

</div>

</div>

<!-- Tableau -->

<div class="row mt-5">

<div class="col-lg-8">

<div class="card shadow">

<div class="card-header bg-primary text-white">

Derniers examens

</div>

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>

<th>Concours</th>

<th>Score</th>

<th>Date</th>

</tr>

</thead>

<tbody>

<tr>

<td>INFAS</td>

<td><span class="badge bg-success">18/20</span></td>

<td>15/08/2026</td>

</tr>

<tr>

<td>CAFOP</td>

<td><span class="badge bg-warning text-dark">16/20</span></td>

<td>13/08/2026</td>

</tr>

<tr>

<td>ENA</td>

<td><span class="badge bg-danger">14/20</span></td>

<td>11/08/2026</td>

</tr>

</tbody>

</table>

</div>

</div>

</div>

<div class="col-lg-4">

<div class="card shadow">

<div class="card-header bg-success text-white">

Votre progression

</div>

<div class="card-body">

<p>

75 % de progression

</p>

<div class="progress">

<div class="progress-bar progress-bar-striped progress-bar-animated"

style="width:75%">

75%

</div>

</div>

<hr>

<div class="alert alert-info">

💡 Continuez à faire des examens chaque jour.

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>