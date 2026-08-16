<?php

// Vérifie si le formulaire a été envoyé
if($_SERVER["REQUEST_METHOD"] == "POST"){


    // Récupération des données envoyées par le formulaire
    $email = $_POST['email'];

    $password = $_POST['password'];



    // Vérification temporaire
    if($email == "kouadio@gmail.com" && $password == "password123"){


        echo "Connexion réussie";


    }else{


        echo "Erreur de connexion";


    }

}

?>



<!DOCTYPE html>

<html lang="fr">


<head>


    <meta charset="UTF-8">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Connexion - SmartEXAM</title>


    <!-- Liaison avec le fichier CSS -->

    <link rel="stylesheet" href="css/login.css">


</head>



<body>



<!-- CARTE PRINCIPALE -->

<div class="login-container">



    <!-- ENTETE -->

    <div class="login-header">


        <div class="logo">

            🎓

        </div>



        <h1>

            SmartEXAM

        </h1>



        <p>

            Connectez-vous pour réviser vos concours

        </p>


    </div>





    <!-- FORMULAIRE -->

    <form action="login.php" method="POST">





        <!-- EMAIL -->

        <div class="input-group">


            <label>

                Email

            </label>



            <input 
            type="email"
            name="email"
            placeholder="Entrez votre email"
            required>


        </div>






        <!-- MOT DE PASSE -->

        <div class="input-group">


            <label>

                Mot de passe

            </label>



            <input
            type="password"
            name="password"
            placeholder="Entrez votre mot de passe"
            required>


        </div>







        <!-- OPTION -->


        <div class="options">


            <label>


                <input type="checkbox">


                Se souvenir de moi


            </label>


        </div>






        <!-- MOT DE PASSE OUBLIE -->


        <a href="#">

            Mot de passe oublié ?

        </a>






        <!-- BOUTON -->


        <button type="submit">


            Se connecter


        </button>






        <!-- INSCRIPTION -->


        <p class="register">


            Vous n’avez pas de compte ?


            <a href="register.html">

                Créer un compte

            </a>


        </p>




    </form>



</div>



</body>


</html>