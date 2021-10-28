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

		$cpt = 0;

		// $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
		// $cnx = mysqli_connect($host, $user, $mdp);
  
		$cnx = mysqli_connect($host, $user, $mdp);
  		if(!cnx){
		  die("Connexion a mysql impossible : ".$cnx->connect_error);
		}
		mysqli_select_db($cnx,$bdd);

		//$query = "SELECT l.urlCouverture, l.EAN, l.Titre, l.Editeur, l.Prix, l.Categorie, COUNT(l.EAN) as Qte , l.Sel, p.Nom, p.Prenom, c.Donne
		// Liste des livres commandés
		$query = "SELECT DISTINCT EAN FROM ".$annee."_APIE_Commandes WHERE Periode='2' ORDER BY EAN";
		$result = $cnx->query($query) or die($cnx->error);

		while ($row = mysqli_fetch_object($result)) {
			$listeLivresCommandes1[] = $row;
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
 <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="css/default.css"/> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function imprimer(){
			$("#entete-APIE").addClass('hidden');
		}
	</script>
	<title>LibrAPIE - Liste des commandes</title>
 </head>
 <body>
	 <div class="container">
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
				print("<div id='listeCommandes' hidden>");
			} else {
				print("<div id='listeCommandes'>");
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
				<button title="gestionCommandes" type="button" class="btn btn-default" onclick="javascript:window.location='formCdeLivre.php'">
					<img height="30px" src="./images/commande.jpg"/>
				</button>
			</div>
			<div style="font-family: Arial; font-size: 2;" id="content" class="content">
				<table class="table table-hover table-responsive">
				<caption>Liste des livres commandés durant ou après le marché de l'année <?php print($annee); ?></caption>
					<tr>
						<th>Couverture</th>
						<th>ISBN-13</th>
						<th class="sortable-text">Titre</th>
						<th>Editeur</th>
						<th width="90px">Prix</th>
						<th>Cat</th>
						<th>Nom/Prenom</th>
						<th>Qté à Cdée</th>
						<th>Donné</th>
					</tr>
					<?php
						$total = 0;

						for ($i=0; $i < sizeof($listeLivresCommandes1); $i++) {
							$detailsLivre = "";
							$detailsCommande = "";
							// On récupère les informations sur le livre commandé
							$query = "SELECT* FROM ".$annee."_APIE_Livres WHERE EAN=".$listeLivresCommandes1[$i]->EAN;
							$result = $cnx->query($query) or die($cnx->error);

							while ($row = mysqli_fetch_object($result)) {
								$detailsLivre[] = $row;
							}

							// Pour chaque livre on compte le nombre de commandes
							$query = "SELECT * FROM ".$annee."_APIE_Commandes WHERE EAN=".$listeLivresCommandes1[$i]->EAN." AND Periode='2'";
							$result = $cnx->query($query) or die($cnx->error);

							while ($row = mysqli_fetch_object($result)) {
								$detailsCommande[] = $row;
							}
							print("<td rowspan=".sizeof($detailsCommande)."><img width='50px' src='".$detailsLivre[0]->urlCouverture."'</td>");
							print("<td rowspan=".sizeof($detailsCommande).">".$detailsLivre[0]->EAN."</td>");
							print("<td rowspan=".sizeof($detailsCommande).">".$detailsLivre[0]->Titre."</td>");
							print("<td rowspan=".sizeof($detailsCommande).">".$detailsLivre[0]->Editeur."</td>");
							print("<td rowspan=".sizeof($detailsCommande).">".number_format($detailsLivre[0]->Prix, 2, ',', '')." €</td>");
							$prixLivre = $detailsLivre[0]->Prix;
							print("<td rowspan=".sizeof($detailsCommande).">".$detailsLivre[0]->Categorie."</td>");
							for ($j=0; $j < sizeof($detailsCommande); $j++) {
								$detailsPersonne = "";
								if ($detailsCommande[$j]->Donne) {
									$donne = "checked";
								} else {
									$donne = "";
								}
								// On récupère les informations sur la personne
								$query = "SELECT * FROM APIE_Personnes WHERE id =".$detailsCommande[$j]->idPersonne;
								$result = $cnx->query($query) or die($cnx->error);

								while ($row = mysqli_fetch_object($result)) {
									$detailsPersonne[] = $row;
								}
								print("<td>".$detailsPersonne[0]->Nom." ".$detailsPersonne[0]->Prenom."</td>");
								print("<td>".$detailsCommande[$j]->Qte."</td>");
								$qteCommandee = $detailsCommande[$j]->Qte;
								print("<td align='center'><input type='checkbox' ".$donne." disabled></td>");
								$total = $total + ($prixLivre*$qteCommandee);
								print("</tr>");
								print("<tr>");
							}
						}
					?>
				</table>
			</div>
			<h2 size="20px"><?php print("Total: ".number_format($total, 2, ',', ' ')." €"); ?></h2>
			<?php 
				$query = "SELECT DISTINCT EAN FROM ".$annee."_APIE_Commandes WHERE Periode='1' ORDER BY EAN";
				$result = $cnx->query($query) or die($cnx->error);

				while ($row = mysqli_fetch_object($result)) {
					$listeLivresCommandes2[] = $row;
				}
			?>
			<div style="font-family: Arial; font-size: 2;" id="content" class="content">
				<table class="table table-hover table-responsive">
			    <caption>Liste des livres commandés avant le marché de l'année <?php print($annee); ?></caption>
					<tr>
						<th>Couverture</th>
						<th>ISBN-13</th>
						<th class="sortable-text">Titre</th>
						<th>Editeur</th>
						<th width="90px">Prix</th>
						<th>Cat</th>
						<th>Nom/Prenom</th>
						<th>Qté Cdée</th>
						<th>Donné</th>
					</tr>
					<?php
						$total = 0;

						for ($i=0; $i < sizeof($listeLivresCommandes2); $i++) {
							$detailsLivre = "";
							$detailsCommande = "";
							// On récupère les informations sur le livre commandé
							$query = "SELECT* FROM ".$annee."_APIE_Livres WHERE EAN=".$listeLivresCommandes2[$i]->EAN;
							$result = $cnx->query($query) or die($cnx->error);

							while ($row = mysqli_fetch_object($result)) {
								$detailsLivre[] = $row;
							}

							// Pour chaque livre on compte le nombre de commandes
							$query = "SELECT * FROM ".$annee."_APIE_Commandes WHERE EAN=".$listeLivresCommandes2[$i]->EAN." AND Periode='1'";
							$result = $cnx->query($query) or die($cnx->error);

							while ($row = mysqli_fetch_object($result)) {
								$detailsCommande[] = $row;
							}
							print("<td rowspan=".sizeof($detailsCommande)."><img width='50px' src='".$detailsLivre[0]->urlCouverture."'</td>");
							print("<td rowspan=".sizeof($detailsCommande).">".$detailsLivre[0]->EAN."</td>");
							print("<td rowspan=".sizeof($detailsCommande).">".$detailsLivre[0]->Titre."</td>");
							print("<td rowspan=".sizeof($detailsCommande).">".$detailsLivre[0]->Editeur."</td>");
							print("<td rowspan=".sizeof($detailsCommande).">".number_format($detailsLivre[0]->Prix, 2, ',', '')." €</td>");
							$prixLivre = $detailsLivre[0]->Prix;
							print("<td rowspan=".sizeof($detailsCommande).">".$detailsLivre[0]->Categorie."</td>");
							for ($j=0; $j < sizeof($detailsCommande); $j++) {
								$detailsPersonne = "";
								if ($detailsCommande[$j]->Donne) {
									$donne = "checked";
								} else {
									$donne = "";
								}
								// On récupère les informations sur la personne
								$query = "SELECT * FROM APIE_Personnes WHERE id =".$detailsCommande[$j]->idPersonne;
								$result = $cnx->query($query) or die($cnx->error);

								while ($row = mysqli_fetch_object($result)) {
									$detailsPersonne[] = $row;
								}
								print("<td>".$detailsPersonne[0]->Nom." ".$detailsPersonne[0]->Prenom."</td>");
								print("<td>".$detailsCommande[$j]->Qte."</td>");
								$qteCommandee = $detailsCommande[$j]->Qte;
								print("<td align='center'><input type='checkbox' ".$donne." disabled></td>");
								$total = $total + ($prixLivre*$qteCommandee);
								print("</tr>");
								print("<tr>");
							}
						}
					?>
				</table>
			</div>
			<h2 size="20px"><?php print("Total: ".number_format($total, 2, ',', ' ')." €"); ?></h2>
		</div>
	</body>
</html>
