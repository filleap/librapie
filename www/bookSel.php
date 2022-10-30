<?php

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

$cpt = 0;

$cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
$cnx = mysqli_connect($host, $user, $mdp);
  
  if(!cnx){
    die("Connexion a mysql impossible : ".$cnx->connect_error);
  }
  mysqli_select_db($cnx,$bdd);

$query = "SELECT * FROM ".$annee."_APIE_Livres WHERE Sel='1' AND Suppr='0' ORDER BY Suppr, Categorie, Titre";
$result = mysql_query($query) or die(mysql_error());

while ($row = mysql_fetch_object($result)) {
	$listeLivres[] = $row;
}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="fr_FR" xml:lang="fr_FR">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
        <!-- mise en page pour impression -->
        <link rel="stylesheet" media="print" href="css/print.css">
        <link rel="stylesheet" href="./css/tableau.css">
        <script type="text/javascript" src="./jslib/utilBibli.js">
	</script>
        <script type="text/javascript" src="./jslib/tablesort.js">
        </script>
	<title> LibrAPIE</title>
    </head>
    <body>
<div style="font-family: Arial; font-size: 2;" id="content" class="content">
	<table border="1" id="listeLivres">
        <caption class="tab_title">Liste des livres de la sélection de l'année <?php print($annee); ?></caption>
		<tr>
			<th>num</th>
			<th>Couverture</th>
			<th>ISBN-13</th>
			<th class="sortable-text">Titre</th>
			<th>Auteur(s)</th>
			<th>Editeur</th>
			<th>Prix</th>
			<th class="sortable-text">Cat.</th>
			<th>Cde.</th>
			<th>Qté Cdée</th>
			<th>Qté Stock</th>
		</tr>
		<?php
			for($i=0; $i<sizeof($listeLivres); $i++){
				$cpt++;
				$ean = $listeLivres[$i]->EAN;
				$query = "select Qte from ".$annee."_APIE_Commandes where EAN='$ean'";
				$result = mysql_query($query) or die(mysql_error());
				// Reset
				$listeQteCde = "";
				$qte=0;
				while ($row = mysql_fetch_object($result)) {
					$listeQteCde[] = $row;
				}
				print("<tr class='sel'>");
				print("<td class='sel'>".$cpt."</td>");
				print("<td align='center' class='sel'><img src='".$listeLivres[$i]->urlCouverture."'></td>");
				print("<td class='sel'>".$listeLivres[$i]->EAN."</td>");
				print("<td class='sel'>".$listeLivres[$i]->Titre."</td>");
				print("<td class='sel'>".$listeLivres[$i]->Auteur."</td>");
				print("<td class='sel'>".$listeLivres[$i]->Editeur."</td>");
				print("<td align='center' class='sel'>".$listeLivres[$i]->Prix." €</td>");
				print("<td align='center' class='sel'>".$listeLivres[$i]->Categorie."</td>");
				// livre commandé ou non
				if($listeQteCde[0]->Qte != "")
					$cde = "checked";
				else
					$cde = "";
				print("<td class='sel' align='center'><input id='cde_".$listeLivres[$i]->id."' type='checkbox' ".$cde."></td>");
				// Quantit� command�e
				if(sizeof($listeQteCde)>0){
					for($j=0; $j<sizeof($listeQteCde); $j++){
						$qteCde = $qteCde+$listeQteCde[$j]->Qte;
					}
					print("<td class='sel' align='center'>".$qteCde."</td>");
				}else{
					// Il y a une commande identifiee dans la liste mais pas de fiche
					print("<td class='sel' align='center'>TbD</td>");
				}
				$query = "select Qte from ".$annee."_APIE_Stock_Livres where EAN='$ean'";
				$result = mysql_query($query) or die(mysql_error());
				while ($row = mysql_fetch_object($result)) {
					$listeQteStock[] = $row;
				}
				print("<td class='sel' align='center'>".$listeQteStock[0]->Qte."</td>");
				print("</tr>");
			}
		?>
		</tr>
		</font>
	</table>
</div>
</body>
</html>
