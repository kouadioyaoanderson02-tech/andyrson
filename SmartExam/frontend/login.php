<?php

// Démarre une session
session_start();

// Variable pour afficher les messages
$message = "";

// Vérifie si le formulaire est envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupération des informations
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Vérification temporaire
    if ($email == "kouadio@gmail.com" && $password == "password123") {

        // Création de la session
        $_SESSION["user_id"] = 1;

        // Redirection vers le dashboard
        header("Location: dashboard.php");
        exit();

    } else {

        $message = "❌ Email ou mot de passe incorrect.";

    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion - SmartEXAM</title>

    <link rel="stylesheet" href="css/login.css">

</head>

<body>

    <div class="login-container">

        <!-- Entête -->

        <div class="login-header">

            <div class="logo">
                🎓
            </div>

            <h1>SmartEXAM</h1>

            <p>Connectez-vous pour réviser vos concours.</p>

        </div>

        <!-- Message -->

        <?php if (!empty($message)) { ?>

            <div class="message">

                <?php echo $message; ?>

            </div>

        <?php } ?>

        <!-- Formulaire -->

        <form action="login.php" method="POST">

            <div class="input-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Entrez votre email"
                    required>

            </div>

            <div class="input-group">

                <label>Mot de passe</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Entrez votre mot de passe"
                    required>

            </div>

            <div class="options">

                <label>

                    <input type="checkbox">

                    Se souvenir de moi

                </label>

            </div>

            <a href="#">Mot de passe oublié ?</a>

            <button type="submit">

                Se connecter

            </button>

            <p class="register">

                Vous n'avez pas de compte ?

                <a href="register.php">

                    Créer un compte

                </a>

            </p>

        </form>

    </div>

</body>

</html>