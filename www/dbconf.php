<?php
    include("autoload.php");
    
    // Connexion a la base de donnees
    // $host = "librapie_database";
    $host = env('DB_HOST');
    $user = env('DB_USER');
    $mdp = env('DB_MDP');
    $bdd = env('DB_NAME');
?>
