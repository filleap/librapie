<?php
	$annee = $_POST['annee'];
	$ean = $_POST['EAN'];
	$qte = $_POST['qte'];

	// Connexion a la base de donnees
	include("dbconf.php");

	//mysql_query("SET NAMES UTF8");

	// $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
	// $cnx = mysqli_connect($host, $user, $mdp);

	$cnx = mysqli_connect($host, $user, $mdp);
	if(!cnx){
	die("Connexion a mysql impossible : ".$cnx->connect_error);
	}
	mysqli_select_db($cnx,$bdd);

	$query = "UPDATE ".$annee."_APIE_Stock_Livres SET Qte='$qte' WHERE EAN='$ean'";
	// print_r($query);
	if ($result = $cnx->query($query))
		print("ok");
	else
		print("erreur !");
?>
