<?php
	$session = true;

	if($session){
		// Connexion a la base de donnees
		include("dbconf.php");

		//mysql_query("SET NAMES UTF8");

		$annee = $_GET['annee'];
		$isbn = $_GET['isbn'];

		if ($annee == "") {
		// positionnement de l'année courante
			// $date = getdate();
			// $annee = $date[year];
			$annee = "2022";
		}

		$cnx = mysqli_connect($host, $user, $mdp);
  		if(!cnx){
		  die("Connexion a mysql impossible : ".$cnx->connect_error);
		}
		mysqli_select_db($cnx,$bdd);

		$query = "SELECT * FROM ".$annee."_APIE_Livres WHERE EAN=".$isbn;
		$result = $cnx->query($query) or die($cnx->error);

		while ($row = mysqli_fetch_object($result)) {
			$livres[] = $row;
		}

		// Récupération de la quantité en stock
		$query = "SELECT * FROM ".$annee."_APIE_Stock_Livres WHERE EAN=".$isbn;
		$result = $cnx->query($query) or die($cnx->error);

		while ($row = mysqli_fetch_object($result)) {
			$qte[] = $row;
		}

		$livres[0]->Qte = $qte[0]->Qte;
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
		<script type="text/javascript">
			function imprimer(){
				$("#entete-APIE").addClass('hidden');
			}

			$(document).ready(function(){
				$('#modale').hide();
			}
		</script>
		<title>LibrAPIE - Gestion du stock</title>
  </head>
  <body>
		<div class="container" id="content" class="content">
		  <h3>Détail du livre à rendre</h3>
				<form action="app_renduLivre.php" method="POST">
					<input type="text" id="Annee" name="Annee" value="<?php print($annee); ?>" hidden>
					<div class="col-sm-4">
						<img width="100px;" id="urlCouverture" name="urlCouverture" src="<?php print($livres[0]->urlCouverture); ?>">
					</div>
					<div class="col-sm-8">
						<p>Choix : <?php print($livres[0]->Choix); ?></p>
						<p>ISBN : <?php print($livres[0]->EAN); ?></p>
						<input type="text" id="EAN" name="EAN" value="<?php print($livres[0]->EAN); ?>" hidden>
						<p>Titre : <?php print($livres[0]->Titre); ?></p>
						<p>Auteur : <?php print($livres[0]->Auteur); ?></p>
						<p>Editeur : <?php print($livres[0]->Editeur); ?></p>
						<p>Prix (€) : <?php print($livres[0]->Prix); ?></p>
						<p>Qté stock : <?php print($livres[0]->Qte); ?></p>
						<input type="text" id="Qte" name="Qte" value="<?php print($livres[0]->Qte); ?>" hidden>
						<p>Catégorie : <?php print($livres[0]->Categorie); ?></p>
					</div>
					<div>
						<label>annuler le retour</label><input type="checkbox" id="annuler" name="annuler">
						<button type="submit" title="rendre le livre" class="btn btn-default">
							rendre le livre
						</button>
					</div>
				</form>
		</div>
	</body>
</html>
