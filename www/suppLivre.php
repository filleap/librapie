<?php
	$id = $_POST['id'];
	$annee = $_POST['annee'];

	// Connexion a la base de donnees
	include("dbconf.php");

	//mysql_query("SET NAMES UTF8");

	if ($annee == "") {
	// positionnement de l'année courante
		// $date = getdate();
		// $annee = $date[year];
		$annee = "2021";
	}

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
	if($livre->Suppr == 0){
		$suppr = 1;
		// vérification que le livre n'est pas dans une commande
		$query = "SELECT * FROM ".$annee."_APIE_Commandes WHERE EAN='$livre->EAN'";
		$result = $cnx->query($query) or die ($cnx->error); 
		if($result->num_rows == 0){
			// suppression du livre
			$query = "DELETE FROM ".$annee."_APIE_Livres WHERE id='$id'";
			if($result = $cnx->query($query)){
				// TODO suppirmer le livre du stock
				$query = "DELETE FROM ".$annee."_APIE_Stock_Livres WHERE EAN='$livre->EAN'";
				if($result = $cnx->query($query))
					print("ok");
			}
		} else
			print("commande");
	}
	// else{
	// 	$suppr = 0;
	// 	$query = "UPDATE ".$annee."_APIE_Livres SET Suppr='$suppr' WHERE id='$id'";
	// 	if ($result = $cnx->query($query)){
	// 		// TODO suppirmer le livre du stock
	// 		print("ok");
	// 	}
	// 	else
	// 		print("erreur !");
	// }

?>
