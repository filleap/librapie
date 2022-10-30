<?php
$id = $_POST['id'];
$annee = "2022";

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

$query = "SELECT * FROM ".$annee."_APIE_Commandes WHERE idCde='$id'";
if($result = $cnx->query($query)){
		$commande = mysqli_fetch_object($result);
}
if($commande->Paye == 0)
	$paye = 1;
else
	$paye = 0;

$query = "UPDATE ".$annee."_APIE_Commandes SET Paye='$paye' WHERE idCde='$id'";
if ($result = $cnx->query($query))
	print("ok");
else
	print("erreur !");
?>
