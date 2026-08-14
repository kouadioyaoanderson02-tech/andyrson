<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoGest - Gestion Immobilière</title>
    <link rel="stylesheet" href="/andyrson/gestion_immobiliere/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-brand">🏠 ImmoGest</div>
    <div class="nav-links">
        <a href="/andyrson/gestion_immobiliere/client/accueil.php">Accueil</a>
        <a href="/andyrson/gestion_immobiliere/client/rechercher.php">Rechercher</a>
        <?php if (isset($_SESSION['user'])): ?>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="/andyrson/gestion_immobiliere/admin/dashboard.php">Dashboard</a>
            <?php endif; ?>
            <a href="/andyrson/gestion_immobiliere/deconnexion.php" class="btn-danger">Déconnexion</a>
        <?php else: ?>
            <a href="/andyrson/gestion_immobiliere/connexion.php">Connexion</a>
            <a href="/andyrson/gestion_immobiliere/inscription.php" class="btn-primary">S'inscrire</a>
        <?php endif; ?>
    </div>
</nav>
