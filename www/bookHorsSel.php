<?php

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

$cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
$cnx = mysqli_connect($host, $user, $mdp);
  
  if(!cnx){
    die("Connexion a mysql impossible : ".$cnx->connect_error);
  }
  mysqli_select_db($cnx,$bdd);

$query = "SELECT * FROM ".$annee."_APIE_Livres WHERE Sel='0' AND Suppr='0' ORDER BY Suppr, Categorie, Titre";
$result = mysql_query($query) or die(mysql_error());

while ($row = mysql_fetch_object($result)) {
	$listeLivres[] = $row;
}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
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
	<title>LibrAPIE - Liste des livres hors sélection</title>
    </head>
    <body>
<div style="font-family: Arial; font-size: 2;" id="content" class="content">
	<table border="1" id="listeLivres">
        <caption class="tab_title">Liste des livres pour l'année <?php print($annee); ?></caption>
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
				// Reset
				$listeQteCde = "";
				$qteCde=0;
				$ean = $listeLivres[$i]->EAN;
				$cpt++;
				$query = "select Qte from ".$annee."_APIE_Commandes where EAN='$ean'";
				$result = mysql_query($query) or die(mysql_error());
				while ($row = mysql_fetch_object($result)) {
					$listeQteCde[] = $row;
				}
				// Le livre est-il une commande
				if ($listeQteCde != "") {
					print("<tr class='cde'>");
					print("<td class='cde'>".$cpt."</td>");
					print("<td align='center' class='cde'><img src='".$listeLivres[$i]->urlCouverture."'></td>");
					print("<td class='cde'>".$listeLivres[$i]->EAN."</td>");
					print("<td class='cde'>".$listeLivres[$i]->Titre."</td>");
					print("<td class='cde'>".$listeLivres[$i]->Auteur."</td>");
					print("<td class='cde'>".$listeLivres[$i]->Editeur."</td>");
					print("<td align='center' class='cde'>".$listeLivres[$i]->Prix." €</td>");
					print("<td align='center' class='cde'>".$listeLivres[$i]->Categorie."</td>");
					print("<td class='cde' align='center'><input id='cde_".$listeLivres[$i]->id."' type='checkbox' checked></td>");
					// Quantit� command�e
					if(sizeof($listeQteCde)>0){
						for($j=0; $j<sizeof($listeQteCde); $j++){
							$qteCde = $qteCde+$listeQteCde[$j]->Qte;
						}
						print("<td class='cde' align='center'>".$qteCde."</td>");
					}else{
						// Il y a une commande identifiee dans la liste mais pas de fiche
						print("<td class='cde' align='center'>TbD</td>");
					}
					$query = "select Qte from ".$annee."_APIE_Stock_Livres where EAN='$ean'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_object($result)) {
						$listeQteStock[] = $row;
					}
					print("<td class='cde' align='center'>".$listeQteStock[0]->Qte."</td>");
					print("</tr>");
				}
				else {
					print("<tr>");
					print("<td >".$cpt."</td>");
					print("<td align='center''><img src='".$listeLivres[$i]->urlCouverture."'></td>");
					print("<td>".$listeLivres[$i]->EAN."</td>");
					print("<td >".$listeLivres[$i]->Titre."</td>");
					print("<td>".$listeLivres[$i]->Auteur."</td>");
					print("<td>".$listeLivres[$i]->Editeur."</td>");
					print("<td align='center'>".$listeLivres[$i]->Prix." €</td>");
					print("<td align='center'>".$listeLivres[$i]->Categorie."</td>");
					// livre commandé ou non
					if($listeLivres[$i]->Cde == "1")
						$cde = "checked";
					else
						$cde = "";
					print("<td align='center'><input id='cde_".$listeLivres[$i]->id."' type='checkbox' ".$cde."></td>");
					// Quantit� command�e
					print("<td align='center'>0</td>");
					$query = "select Qte from ".$annee."_APIE_Stock_Livres where EAN='$ean'";
					$result = mysql_query($query) or die(mysql_error());
					while ($row = mysql_fetch_object($result)) {
						$listeQteStock[] = $row;
					}
					print("<td align='center'>".$listeQteStock[0]->Qte."</td>");
					print("</tr>");
				}
			}
		?>
		</tr>
		</font>
	</table>
</div>
</body>
</html>
