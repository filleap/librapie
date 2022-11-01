<?php
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
			$annee = "2022";
		}

		// $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
		// $cnx = mysqli_connect($host, $user, $mdp);
  
		$cnx = mysqli_connect($host, $user, $mdp);
		if(!cnx){
			die("Connexion a mysql impossible : ".$cnx->connect_error);
		}
		mysqli_select_db($cnx,$bdd);

		$query = "SELECT livres.Choix, livres.id, livres.urlCouverture, livres.EAN, livres.ISBN, livres.Titre, livres.Prix, stock.Qte,stock.Cde, stock.QteCdee, stock.Rendu FROM ".$annee."_APIE_Stock_Livres AS stock, ".$annee."_APIE_Livres AS livres WHERE livres.EAN=stock.EAN AND livres.Suppr='0' ORDER BY livres.Titre";
		$result = $cnx->query($query) or die($cnx->error);

		while ($row = mysqli_fetch_object($result)) {
			$listeLivres[] = $row;
		}
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="fr_FR" xml:lang="fr_FR">
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	  <link rel="stylesheet" href="./css/modale.css">
	  <!-- mise en page pour impression -->
	  <!-- <link rel="stylesheet" media="print" href="css/print.css">
	  <link rel="stylesheet" href="./css/tableau.css"> -->
		<script type="text/javascript" src="./bootstrap-3.3.5-dist/js/bootstrap.js"></script>
	  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
	  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
    <script type="text/javascript" src="./jslib/tablesort.js"></script>
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
					print("<div id='gestionStock' hidden>");
				} else {
					print("<div id='gestionStock'>");
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
				<div class="col-sm-12">
					<table class="table table-hover table-responsive">
				  	<caption class="tab_title">Stock de livres de l'année <?php print($annee) ?></caption>
						<tr>
							<th>modif</th>
							<th class="sortable-text">Choix</th>
							<th>Couverture</th>
							<th>ISBN</th>
							<th class="sortable-text">Titre</th>
							<th>Stock</th>
							<th>Cdé</th>
							<th width="90px">Prix</th>
							<th>Rendu</th>
						</tr>
						<?php
							$total = 0;
							$livreArendre = 0;
							for($i=0; $i<sizeof($listeLivres); $i++){
								$listeCde = "";
								$qteCdee = 0;
								// interrogation des commandes
								$query = "SELECT * FROM ".$annee."_APIE_Commandes WHERE EAN='".$listeLivres[$i]->EAN."'";
								$result = $cnx->query($query) or die($cnx->error);
								while ($row = mysqli_fetch_object($result)) {
									$listeCde[] = $row;
								}

								print("<tr>");
								print("<td><button type='button' title='éditer' class='btn btn-default' data-toggle='modal' data-target='#test' onclick='editLivre(".$listeLivres[$i]->EAN.", ".$annee.")'>");
								print("<span class='glyphicon glyphicon-edit'></span>");
								print("</button></td>");
								if($listeCde[0]->Qte > 0) {
									for ($j=0; $j < sizeof($listeCde); $j++) {
										$qteCdee += $listeCde[$j]->Qte;
									}
									print("<td >".$listeLivres[$i]->Choix."<br><span class='glyphicon glyphicon-book'> ".$qteCdee."</span></td>");
								} else {
									print("<td>".$listeLivres[$i]->Choix."</td>");
								}
								print("<td align='center'><img width='50px' src='".$listeLivres[$i]->urlCouverture."'></td>");
								print("<td>".$listeLivres[$i]->EAN."</td>");
								print("<td>".$listeLivres[$i]->Titre."</td>");
								$qteStock = $listeLivres[$i]->Qte;
								//Stock
								if($qteStock == 0){
									print("<td>".$listeLivres[$i]->Qte."</td>");
								} elseif($qteStock >= $qteCdee){
									print("<td align='center'><span class='glyphicon glyphicon-ok' style='color:green'><br>stock-".$qteStock."</span></td>");
								} elseif($qteStock < $qteCdee){
									print("<td align='center'><span class='glyphicon glyphicon-remove' style='color:red'><br>stock-".$qteStock."</span></td>");
								}
								// Commandé
								if($listeLivres[$i]->Cde){
									if($qteStock+$listeLivres[$i]->QteCdee >= $qteCdee){
										print("<td align='center'><span class='glyphicon glyphicon-ok' style='color:green'><br>cdé-".$listeLivres[$i]->QteCdee."</span></td>");
									} elseif($qteStock+$listeLivres[$i]->QteCdee <= 0 || $qteStock+$listeLivres[$i]->QteCdee < $qteCdee){
										print("<td align='center'><span class='glyphicon glyphicon-remove' style='color:red'><br>cdé-".$listeLivres[$i]->QteCdee."</span></td>");
									}
								} else {
									print("<td align='center'><strike>Cdé</strike></td>");
									$livreArendre = $livreArendre + $qteStock;
								}
								print("<td>".number_format($listeLivres[$i]->Prix, 2, ',', '')." €</td>");
								$prixLivre = $listeLivres[$i]->Prix;
								if($listeLivres[$i]->Rendu){
									print("<td>rendu</td>");
								} else{
									print("<td><strike>rendu</strike></td>");
								}
								print("</tr>");
								$total = $total + ($prixLivre * $qteStock);
							}
					 	?>
					</table>
					<h1 size="20px"><?php print("Total: ".number_format($total, 2, ',', ' ')." €"); ?></h1>
					<h2 size="10px"><?php print("Livres à rendre: ".$livreArendre);?></h2>
				</div>
			</div>
		</div>
		<div id="modale" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" onclick="$('#modale').hide();"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">Détail du livre</h4>
		      </div>
					<div class="modal-body">
						<form action="majBook.php" method="POST">
							<input type="text" id="Annee" name="Annee" value="<?php print($annee); ?>" hidden>
							<div class="col-sm-4">
								<img width="100px;" id="urlCouverture" name="urlCouverture" src="" onclick="$('#urlCouv').show();">
								<p id="urlCouv" hidden>Couverture : <input type="text" id="Couverture" name="Couverture" value=""></p>
							</div>
							<div class="col-sm-8">
								<p>Choix : <input type="text" id="Choix" name="Choix" value=""></p>
								<p>ISBN : <input type="text" id="EAN" name="EAN" value="" readonly></p>
								<p>Titre : <input type="text" id="Titre" name="Titre" value=""></p>
								<p>Auteur : <input type="text" id="Auteur" name="Auteur" value=""></p>
								<p>Editeur : <input type="text" id="Editeur" name="Editeur" value=""></p>
								<p>Prix (€) : <input type="text" id="Prix" name="Prix" value=""></p>
								<p>Quantité : <input type="text" id="Qte" name="Qte" value=""></p>
								<p>Catégorie : <input type="text" id="Categorie" name="Categorie" value=""></p>
								<p>Commandé : <input type="checkbox" id="Cde" name="Cde"><br>
									Qté Commandée : <input type="text" id="QteCdee" name="QteCdee" value="0"></p>
								<p>Rendu : <input type="checkbox" id="Rendu" name="Rendu"><br>
									Qté à rendre : <input type="text" id="QteRendue" name="QteRendue" value="0"></p>
							</div>
				      		<div class="modal-footer">
								<button type="submit" title="mettre à jour" class="btn btn-default">
									mettre à jour
								</button>
								<button type="button" title="annuler" class="btn btn-danger" onclick="$('#modale').hide();">
									fermer
								</button>
				      		</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
