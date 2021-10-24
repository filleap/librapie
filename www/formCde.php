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
		$idPersonne = $_GET['id'];
		// $annee = $_GET['annee'];
		$annee = '2019';
		$total = 0;

		// Connexion a la base de donnees
		include("dbconf.php");

		$commande = FALSE;

		// $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
		// $cnx = mysqli_connect($host, $user, $mdp);
  
		$cnx = mysqli_connect($host, $user, $mdp);
		if(!cnx){
			die("Connexion a mysql impossible : ".$cnx->connect_error);
		}
		mysqli_select_db($cnx,$bdd);

		$query = "SELECT * FROM APIE_Personnes WHERE id='$idPersonne'";
		$result = $cnx->query($query) or die($cnx->error);

		$personne = mysqli_fetch_object($result);

		$query = "SELECT * FROM ".$annee."_APIE_Commandes WHERE idPersonne='$idPersonne'";
		$result = $cnx->query($query) or die($cnx->error);

		while ($row = mysqli_fetch_object($result)) {
			$listeCde[] = $row;
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR"
	xml:lang="en_FR">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/x-icon" href="favicon.ico" />
<!-- <link rel="stylesheet" href="css/default.css" /> -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
<script type="text/javascript" src="./jslib/utilBibli.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
	function imprimer(){
		$("#entete-APIE").addClass('hidden');
	}
</script>
<title>LibrAPIE - Détail de la commande</title>
</head>
<body>
	<div class="container">
	 <div class="page-header" id="entete-APIE">
		 <div class="row">
			 <div class="col-sm-2">
				 <img src="./Logo_APIE.gif"/>
			 </div>
			 <div class="col-sm-8">
				 <h1>Bienvenue dans librAPIE <?php print($_SESSION['pseudo']); ?></h1>
				 <caption>la bibliothèque du marché de Noël</caption>
			 </div>
		 </div>
	 </div>
	 <div id="non_connecte">
		 <?php
			 if(!$session){
				 print("<h2>Vous n'avez pas le droit de consulter cette page !</h2>");
			 }
		 ?>
	 </div>
	 <div id="barre_boutons">
		 <button title="imprimer" type="button" class="btn btn-default" onclick="javascript:imprimer();return false;">
			 <span class="glyphicon glyphicon-print"></span>
		 </button>
		 <button title="fermer la fenêtre" type="button" class="btn btn-default" onclick="javascript:window.close();">
			 <span class="glyphicon glyphicon-remove-circle"></span>
		 </button>
	 </div>
	 <div class="col-sm-12">
			<h2>Commande de
				<?php print($personne->Prenom." ".$personne->Nom); ?>
			</h2>
			<?php
			if($listeCde == null){
				print("<h2>Pas de commande l'année ".$annee."</h2>");
			} else{
				?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Couv</th>
						<th>Titre</th>
						<th>Prix</th>
						<th>Qté Cde</th>
						<th>Payé</th>
						<th>Donné</th>
						<th>Qté Donnée</th>
					</tr>
				</thead>
				<?php
				for ($i = 0; $i < sizeof($listeCde); $i++) {
					$isbn = $listeCde[$i]->EAN;
					$query = "SELECT * FROM ".$annee."_APIE_Livres WHERE EAN='$isbn'";
					$result = $cnx->query($query) or die($cnx->error);

					$livre = mysqli_fetch_object($result);
					$qte = $listeCde[$i]->Qte;
					$qteDonne = $listeCde[$i]->QteDonne;
					$prix = $livre->Prix;
					if($listeCde[$i]->Paye == 1){
						$reste = $total - ($prix * ($qte+$qteDonne));
					}
					print("<tr>");
					print("<td><img width='50 px' src='".$livre->urlCouverture."'></td>");
					print("<td>".$livre->Titre."</td>");
					print("<td>".number_format($prix, 2, ',', '')." €</td>");
					print("<td><input size='2' id='qte_".$listeCde[$i]->idCde."' name='qte_".$listeCde[$i]->idCde."' value='".$qte."' onchange='majQteCdee(".$annee.", ".$listeCde[$i]->idCde.", ".$idPersonne.");'></td>");
					if ($listeCde[$i]->Paye) $paye = "checked";
					else $paye = "";
					print("<td><input id='paye_".$listeCde[$i]->idCde."' type='checkbox' ".$paye." onclick='paye(".$listeCde[$i]->idCde.",".$idPersonne.");'></td>");
					if ($listeCde[$i]->Donne) $donne = "checked";
					else $donne = "";
					print("<td><input id='donne_".$listeCde[$i]->idCde."' type='checkbox' ".$donne." onclick='donne(".$listeCde[$i]->idCde.",".$idPersonne.",".$qte.");'></td>");
					print("<td align='center'>".$listeCde[$i]->QteDonne."</td>");
					print("</tr>");
					$total = $total + ($prix * ($qte+$qteDonne));
				}
			}

			$reste = $total;
			for ($i = 0; $i < sizeof($listeCde); $i++) {
				$isbn = $listeCde[$i]->EAN;
				$query = "SELECT * FROM ".$annee."_APIE_Livres WHERE EAN='$isbn'";
				$result = $cnx->query($query) or die($cnx->error);

				$livre = mysqli_fetch_object($result);
				$qte = $listeCde[$i]->Qte;
				$qteDonne = $listeCde[$i]->QteDonne;
				$prix = $livre->Prix;
				if($listeCde[$i]->Paye == 1){
					$reste = $reste - ($prix * ($qte+$qteDonne));
				}
			}
			?>
			</table>
			<p><b>Total Commande : <?php print(number_format($total, 2, ',', '')." €"); ?></b></p>
			<p><b>Reste à payer : <?php print(number_format(abs($reste), 2, ',', '')." €"); ?></b></p>
		</div>
	</div>
</body>
</html>
