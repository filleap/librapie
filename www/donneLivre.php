<?php
$id = $_POST['id'];
$annee = "2021";
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

$query = "SELECT * FROM ".$annee."_APIE_Commandes WHERE idCde='$id'";
if($result = $cnx->query($query)){
		$commande = mysqli_fetch_object($result);
}

if($commande->Donne == 0)
	$donne = 1;
else
	$donne = 0;

$query = "SELECT Qte FROM ".$annee."_APIE_Stock_Livres WHERE EAN='$commande->EAN'";
	if($result = $cnx->query($query)){
			$stock = mysqli_fetch_object($result);
	}
	if($donne){
		$qteStock = $stock->Qte - $qte;
		$qteRestant = $commande->Qte - $qte;
	} else {
		$qteStock = $stock->Qte + $qte;
		$qteRestant = $commande->Qte + $qte;
	}
if ($qteStock >= 0) {
	$query = "UPDATE ".$annee."_APIE_Stock_Livres SET Qte='$qteStock' WHERE EAN='$commande->EAN'";
	if ($result = $cnx->query($query)){
		$query = "UPDATE ".$annee."_APIE_Commandes SET Qte='$qteRestant', Donne='$donne', QteDonne='$qte' WHERE idCde='$id'";
		if ($result = $cnx->query($query)){
			print("ok");
		} else
			print("erreur !");
	}
} else {
	print("Stock négatif ! Il y a un problème...");
}





// $query = "UPDATE ".$annee."_APIE_Commandes SET Donne='$donne' WHERE idCde='$id'";
//
// if ($result = mysql_query($query)){
// 	$query = "SELECT Qte FROM ".$annee."_APIE_Stock_Livres WHERE EAN='$commande->EAN'";
// 	if($result = mysql_query($query)){
// 			$stock = mysql_fetch_object($result);
// 	}
// 	if($donne){
// 		$qteStock = $stock->Qte - $qte;
// 	} else {
// 		$qteStock = $stock->Qte + $qte;
// 	}
// 	if ($qteStock > 0) {
// 		$query = "UPDATE ".$annee."_APIE_Stock_Livres SET Qte='$qteStock' WHERE EAN='$commande->EAN'";
// 		if ($result = mysql_query($query)){
// 			print("ok");
// 		} else
// 			print("erreur !");
// 	} else {
// 		print("Stock négatif ! Il y a un problème...");
// 	}
// }
?>
