<?php
	$session = true;
	if($session){
    $annee = addslashes($_POST['Annee']);
    $annuler = addslashes($_POST['annuler']);
    $ean = addslashes($_POST['EAN']);
	$qte = $_POST['Qte'];

    // Connexion a la base de donnees
    include("dbconf.php");

	$cnx = mysqli_connect($host, $user, $mdp);
	  if(!cnx){
	  die("Connexion a mysql impossible : ".$cnx->connect_error);
	}
	mysqli_select_db($cnx,$bdd);

	// Mise à jour du stock et de l'indication que le livre a été rendu
	// on diminue de 1 le stock suite au retour du livre
	if($annuler){
		$qte = $qte+1;
		$query = "UPDATE ".$annee."_APIE_Stock_Livres SET Qte='$qte', Rendu='0' WHERE EAN='$ean'";
	} else{
		$qte = $qte-1;
		if($qte<0){
			print("<link rel='stylesheet' href='./bootstrap-3.3.5-dist/css/bootstrap.min.css'>");
			print("<div class='alert alert-danger' role='alert'>");
			print("<span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>");
			print("Stock vide. Impossible de rendre ce livre !");
			print("</div>");
			exit();
		}
		$query = "UPDATE ".$annee."_APIE_Stock_Livres SET Qte='$qte', Rendu='1' WHERE EAN='$ean'";
	}

    if ($result = $cnx->query($query)){
			if($annuler){
    			$msg = "Annulation du retour du livre effectuée !";
			}
			else {
				$msg = "Livre retourné à Laprocure !";
			}
			header("refresh:2 ; URL=./app_infoLivre.php?isbn=".$ean);
		}
    else
    	$msg = "ERREUR !";
  }
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
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
					<?php print($msg); ?>
			</div>
		</div>
	</body>
</html>
