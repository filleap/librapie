<?php
$id = $_POST['id'];

// Connexion a la base de donnees
include("dbconf.php");

//mysql_query("SET NAMES UTF8");

// positionnement de l'année courante
// $date = getdate();
// $annee = $date[year];
$annee = "2022";

// $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
// $cnx = mysqli_connect($host, $user, $mdp);

$cnx = mysqli_connect($host, $user, $mdp);
if(!cnx){
	die("Connexion a mysql impossible : ".$cnx->connect_error);
}
mysqli_select_db($cnx,$bdd);

$query = "SELECT * FROM ".$annee."_APIE_Livres WHERE id='$id'";
if($result = $cnx->query($query)){
		$livre = mysqli_fetch_object($result);
}
if($livre->Sel == 0)
	$sel = 1;
else
	$sel = 0;

$query = "UPDATE ".$annee."_APIE_Livres SET Sel='$sel' WHERE id='$id'";
if ($result = $cnx->query($query))
	print("ok");
else
	print("erreur !");
?>
