// Chargement de la base de données à partir de l'export d'un panier de la procure

<?php
    $csvFile = $_GET['csvFile'];
    $Choix = $_GET['Choix'];
    var_dump($csvFile);
    var_dump($Choix);
    
    // Connexion a la base de donnees
    include("dbconf.php");

    $cnx = mysqli_connect($host, $user, $mdp);
    if(!cnx){
      die("Connexion a mysql impossible : ".$cnx->connect_error);
    }
    mysqli_select_db($cnx,$bdd);

    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            // var_dump($data);
            $EAN = $data[5];
            $Titre = addslashes($data[0]);
            $Auteur = addslashes($data[1]);
            // $Editeur = addslashes($data[2]." , ".$data[3]);
            $Editeur = addslashes($data[2]);
            $Prix = $data[12];
            $Qte = $data[6];
            
            // insertion dans tables Livre
            $query = "INSERT INTO `2022_APIE_Livres` (`EAN`, `Titre`, `Auteur`, `Editeur`, `Prix`, `Categorie`, `Choix`) VALUES ('$EAN', '$Titre', '$Auteur', '$Editeur', '$Prix', 'A', '$Choix')";
            var_dump($query );
            $result = $cnx->query($query) or die($cnx->error);

            // insertion dans table Stock
            $query = "INSERT INTO `2022_APIE_Stock_Livres` (`EAN`, `Titre`, `Prix`, `Qte`) VALUES ('$EAN', '$Titre', '$Prix', '$Qte')";
            // var_dump($query);
            $result = $cnx->query($query) or die($cnx->error);
        }
    }
?>