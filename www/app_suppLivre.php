<?php
$ean = $_GET['ean'];

// Connexion a la base de donnees
include("dbconf.php");

//mysql_query("SET NAMES UTF8");

// positionnement de l'année courante
// $date = getdate();
// $annee = $date[year];
$annee = "2021";

$cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
$cnx = mysqli_connect($host, $user, $mdp);
  
  if(!cnx){
    die("Connexion a mysql impossible : ".$cnx->connect_error);
  }
  mysqli_select_db($cnx,$bdd);

$query = "SELECT * FROM ".$annee."_APIE_Livres WHERE EAN='$ean'";
if($result = mysql_query($query)){
		$livre = mysql_fetch_object($result);
}
if($livre->Suppr == 0)
	$suppr = 1;
else
    $suppr = 0;

$query = "UPDATE ".$annee."_APIE_Livres SET Suppr='$suppr' WHERE EAN='$ean'";
if ($result = mysql_query($query))
	print("ok");
else
	print("erreur !");
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
					Livre supprimé !
			</div>
		</div>
	</body>
</html>
