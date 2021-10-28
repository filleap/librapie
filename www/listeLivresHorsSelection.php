<?php
// Liste des livres commandes par une personne
	$session = false;
	// vérification de l'utilisateur
	session_start();
	if(isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
		$session = true;
	} else {
		$session = false;
	}

	if($session){
		// Connexion a la base de donnees
		include("dbconf.php");

		//mysql_query("SET NAMES UTF8");

		$annee = $_GET['annee'];

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

		$query = "SELECT * FROM ".$annee."_APIE_Livres WHERE Sel='0' AND Suppr='0' ORDER BY Suppr, Categorie, Titre";
		$result = $cnx->query($query) or die($cnx->error);

		while ($row = mysqli_fetch_object($result)) {
			$listeLivres[] = $row;
		}
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	  <!-- mise en page pour impression -->
	  <!-- <link rel="stylesheet" media="print" href="css/print.css">
	  <link rel="stylesheet" href="./css/tableau.css"> -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
	  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
    <script type="text/javascript" src="./jslib/tablesort.js"></script>
		<script type="text/javascript">
			function imprimer(){
				$("#entete-APIE").addClass('hidden');
			}
		</script>
		<title>LibrAPIE - Livres hors sélection de l'année <?php print($annee) ?></title>
  </head>
  <body>
		<div class="container" id="content" class="content">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-2">
					<a href="index.php">
						<img height="100px" src="./images/sapin-noel.png"/>
					</a>
				</div>
				<div class="col-sm-8">
					<h1>Bienvenue dans librAPIE <?php print($_SESSION['pseudo']); ?></h1>
					<caption>la bibliothèque du marché de Noël</caption>
				</div>
				<div class="col-sm-2">
						<img height="100px" src="./images/Logo_APIE.gif"/>
				</div>
			</div>
		</div>
			<?php
				if(!$session){
					print("<div id='warning' class='row'>");
				} else {
					print("<div id='warning' class='row' hidden>");
				}
			?>
				<div class="col-sm-4">
					<img src="images/Cadenas.png">
				</div>
				<div class="col-sm-8">
					<h2>Vous n'êtes pas autorisé à accéder à cette page.</h2>
					<p>Veuillez vous connecter</p>
					<a href="connexion.php">
						<button type="button" class="btn btn-info" title="connexion">
							connexion
						</button>
					</a>
				</div>
			</div>
			<?php
				if(!$session){
					print("<div id='listeLivresHorsSelection' hidden>");
				} else {
					print("<div id='listeLivresHorsSelection'>");
				}
			?>
				<div id="barre_boutons">
					<button type="button" title="page précédente" class="btn btn-default" onclick="history.go(-1)">
						<span class="glyphicon glyphicon-arrow-left"></span>
					</button>
					<a href="index.php">
						<button type="button" title="retour à l'acceuil" class="btn btn-default" onclick="javascript:window.location='index.php'">
							<span class="glyphicon glyphicon-home"></span>
						</button>
					</a>
					<button title="imprimer" type="button" class="btn btn-default" onclick="javascript:imprimer();return false;">
						<span class="glyphicon glyphicon-print"></span>
					</button>
				</div>
				<div>
					<table class="table table-hover table-responsive">
				    <caption>Liste des livres hors sélection de l'année <?php print($annee) ?></caption>
						<tr>
							<th>Choix</th>
							<th>Couverture</th>
							<th>ISBN-13</th>
							<th class="sortable-text">Titre</th>
							<th>Auteur(s)</th>
							<th>Editeur</th>
							<th class="sortable-text">Cat.</th>
							<th width="90px">Prix</th>
							<th>Sel</th>
							<th>Suppr</th>
						</tr>
						<?php
							for($i=0; $i<sizeof($listeLivres); $i++){
								$sel = "";
								$supp = "";
								if ($listeLivres[$i]->Suppr) {
									print("<tr class='danger'>");
									$supp = "checked";
								} else if ($listeLivres[$i]->Sel) {
									print("<tr class='info'>");
									$sel = "checked";
								}
								print("<td>".$listeLivres[$i]->Choix."</td>");
								print("<td align='center'><img src='".$listeLivres[$i]->urlCouverture."'></td>");
								print("<td>".$listeLivres[$i]->EAN."</td>");
								print("<td>".$listeLivres[$i]->Titre."</td>");
								print("<td>".$listeLivres[$i]->Auteur."</td>");
								print("<td>".$listeLivres[$i]->Editeur."</td>");
								print("<td>".$listeLivres[$i]->Categorie."</td>");
								print("<td>".number_format($listeLivres[$i]->Prix, 2, ',', '')." €</td>");
								print("<td align='center'><input type='checkbox' id='sel_".$listeLivres[$i]->id."' ".$sel." onclick='selLivre(".$listeLivres[$i]->id.");'></td>");
								print("<td align='center'><input type='checkbox' id='supp_".$listeLivres[$i]->id."' ".$supp." onclick='suppLivre(".$listeLivres[$i]->id.");'></td>");
								print("</tr>");
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
