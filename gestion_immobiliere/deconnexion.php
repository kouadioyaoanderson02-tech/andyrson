<?php
session_start();
session_destroy();
header("Location: /andyrson/gestion_immobiliere/connexion.php");
exit;
