<?php
$id = $_POST['id'];

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

$query = "SELECT * FROM APIE_Livres WHERE id='$id'";
if($result = $cnx->query($query)){
		$livre = mysqli_fetch_object($result);
}
if($livre->Cde == 0){
	$query = "UPDATE APIE_Livres SET Cde='1' WHERE id='$id'";
}
else{
	$query = "UPDATE APIE_Livres SET Cde='0' WHERE id='$id'";
}

if ($result = $cnx->query($query))
	print("ok");
else
	print("erreur !");	
?>