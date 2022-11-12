<?php
	$session = true;
	if($session){
    $annee = addslashes($_POST['Annee']);
    $ean = addslashes($_POST['EAN']);
    $choix = addslashes($_POST['Choix']);
    $titre = addslashes($_POST['Titre']);
    $auteur = addslashes($_POST['Auteur']);
    $editeur = addslashes($_POST['Editeur']);
    $categorie = addslashes($_POST['Categorie']);
	$prix = $_POST['Prix'];
	$qte = $_POST['Qte'];

    // Connexion a la base de donnees
    include("dbconf.php");

	$cnx = mysqli_connect($host, $user, $mdp);
	if(!cnx){
		die("Connexion a mysql impossible : ".$cnx->connect_error);
	}
	mysqli_select_db($cnx,$bdd);

	// Mise à jour des information sur le livre
    $query = "UPDATE ".$annee."_APIE_Livres SET Choix='$choix', Titre='$titre', Auteur='$auteur', Editeur='$editeur', Prix='$prix', Categorie='$categorie' WHERE EAN='$ean'";

    if ($result = $cnx->query($query)){
			// Mise à jour de la quantité dans le stock
			$query = "UPDATE ".$annee."_APIE_Stock_Livres SET Qte='$qte' WHERE EAN='$ean'";
			if ($result = $cnx->query($query)){
	    		$msg = "mise à jour effectuée.";
				header("refresh:2 ; URL=./app_infoLivre.php?isbn=".$ean);
			}
		}
    else
    	$msg = "erreur !";
  }
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr_FR" xml:lang="fr_FR">
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	  <link rel="stylesheet" href="./bootstrap-3.3.5-dist/css/bootstrap.min.css">
	  <link rel="stylesheet" href="./css/modale.css">
	  <!-- mise en page pour impression -->
	  <!-- <link rel="stylesheet" media="print" href="css/print.css">
	  <link rel="stylesheet" href="./css/tableau.css"> -->
		<script type="text/javascript" src="./bootstrap-3.3.5-dist/js/bootstrap.js"></script>
	  <script type="text/javascript" src="./jslib/jquery.js"></script>
	  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
		<title>LibrAPIE - Gestion du stock</title>
  </head>
  <body>
		<div class="container" id="content" class="content">
			<div class='alert alert-success' role='alert'>
				<span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>
					Livre mis à jour !
			</div>
		</div>
	</body>
</html>
