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

$query = "SELECT * FROM ".$annee."_APIE_Livres ORDER BY Suppr, Categorie, Titre";
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
	<title>LibrAPIE - Liste des livres</title>
    </head>
    <body>
<div style="font-family: Arial; font-size: 2;" id="content" class="content">
	<table border="1" id="listeLivres">
        <caption class="tab_title">Liste des livres de l'année <?php print($annee) ?></caption>
		<tr>
			<th>num</th>
			<th>Couverture</th>
			<th>ISBN-13</th>
			<th class="sortable-text">Titre</th>
			<th>Auteur(s)</th>
			<th>Editeur</th>
			<th>Prix</th>
			<th class="sortable-text">Cat.</th>
			<th class="supp">Suppr.</th>
			<th>Sel.</th>
			<th>Cde.</th>
			<th>Qté</th>
			<th>Recu</th>
			<th>En Cde</th>
			<th>Rendu</th>
		</tr>
		<?php
			for($i=0; $i<sizeof($listeLivres); $i++){
				// Reset
				$listeQte = "";
				$qte=0;
				$ean = $listeLivres[$i]->EAN;
				$cpt++;
				$query = "select Qte from APIE_Commandes where Annee='$annee' and EAN='$ean'";
				$result = mysql_query($query) or die(mysql_error());
				while ($row = mysql_fetch_object($result)) {
					$listeQte[] = $row;
				}
				if($listeLivres[$i]->Suppr == "1") {
					print("<tr class='suppr'>");
					print("<td class='suppr'>".$cpt."</td>");
					print("<td align='center' class='suppr'><img src='".$listeLivres[$i]->urlCouverture."'></td>");
					print("<td class='suppr'>".$listeLivres[$i]->EAN."</td>");
					print("<td class='suppr'>".$listeLivres[$i]->Titre."</td>");
					print("<td class='suppr'>".$listeLivres[$i]->Auteur."</td>");
					print("<td class='suppr'>".$listeLivres[$i]->Editeur."</td>");
					print("<td style='cursor:pointer;' onclick='showFormUpdate(".$listeLivres[$i]->id.");' align='center' class='suppr'>".$listeLivres[$i]->Prix." €</td>");
					print("<td align='center' class='suppr'>".$listeLivres[$i]->Categorie."</td>");
					// livre supprimé ou non
					if($listeLivres[$i]->Suppr == "1")
						$supp = "checked";
					else
						$supp = "";
					print("<td class='suppr' align='center'><input id='supp_".$listeLivres[$i]->id."' type='checkbox' ".$supp." onclick='suppLivre(".$listeLivres[$i]->id.");'></td>");
					// livre sélection ou non
					if($listeLivres[$i]->Sel == "1")
						$sel = "checked";
					else
						$sel = "";
					print("<td align='center' class='suppr'><input id='sel_".$listeLivres[$i]->id."' type='checkbox' ".$sel." onclick='selLivre(".$listeLivres[$i]->id.");'></td>");
					// livre commandé ou non
					if($listeLivres[$i]->Cde == "1")
						$cde = "checked";
					else
						$cde = "";
					print("<td class='suppr' align='center'><input id='cde_".$listeLivres[$i]->id."' type='checkbox' ".$cde." onclick='cdeLivre(".$listeLivres[$i]->id.");'></td>");
					// Quantit� command�e
					print("<td class='suppr' align='center'>".$listeLivres[$i]->Qte."</td>");
					// livre recu ou non
					if($listeLivres[$i]->Recu == "1")
						$recu = "checked";
					else
						$recu = "";
					print("<td class='suppr' align='center'><input id='recu_".$listeLivres[$i]->id."' type='checkbox' ".$recu." onclick='recuLivre(".$listeLivres[$i]->id.");'></td>");
					// qt� de livre en commande
					print("<td class='suppr'>".$listeLivres[$i]->EnCde."</td>");
					// livre rendu ou non
					if($listeLivres[$i]->Rendu == "1")
						$rendu = "checked";
					else
						$rendu = "";
					print("<td class='suppr' align='center'><input id='rendu_".$listeLivres[$i]->id."' type='checkbox' ".$rendu." onclick='renduLivre(".$listeLivres[$i]->id.");'></td>");
					print("</tr>");
				}
				// Livres faisant parti de la selection
				else if ($listeLivres[$i]->Sel == "1") {
					print("<tr class='sel'>");
					print("<td class='sel'>".$cpt."</td>");
					print("<td align='center' class='sel'><img src='".$listeLivres[$i]->urlCouverture."'></td>");
					print("<td  class='sel'>".$listeLivres[$i]->EAN."</td>");
					print("<td class='sel'>".$listeLivres[$i]->Titre."</td>");
					print("<td class='sel'>".$listeLivres[$i]->Auteur."</td>");
					print("<td class='sel'>".$listeLivres[$i]->Editeur."</td>");
					print("<td style='cursor:pointer;' onclick='showFormUpdate(".$listeLivres[$i]->id.");' align='center' class='sel'>".$listeLivres[$i]->Prix." €</td>");
					print("<td align='center' class='sel'>".$listeLivres[$i]->Categorie."</td>");
					// livre supprimé ou non
					if($listeLivres[$i]->Suppr == "1")
						$supp = "checked";
					else
						$supp = "";
					print("<td class='sel' align='center'><input id='supp_".$listeLivres[$i]->id."' type='checkbox' ".$supp." onclick='suppLivre(".$listeLivres[$i]->id.");'></td>");
					// livre sélection ou non
					if($listeLivres[$i]->Sel == "1")
						$sel = "checked";
					else
						$sel = "";
					print("<td align='center' class='sel'><input id='sel_".$listeLivres[$i]->id."' type='checkbox' ".$sel." onclick='selLivre(".$listeLivres[$i]->id.");'></td>");
					// livre commandé ou non
					if($listeQte[0]->Qte != "")
						$cde = "checked";
					else
						$cde = "";
					print("<td class='sel' align='center'><input id='cde_".$listeLivres[$i]->id."' type='checkbox' ".$cde." onclick='cdeLivre(".$listeLivres[$i]->id.");'></td>");
					// Quantit� command�e
					if($cde == "checked"){
						if(sizeof($listeQte)>0){
							for($j=0; $j<sizeof($listeQte); $j++){
								$qte = $qte+$listeQte[$j]->Qte;
							}
							print("<td class='sel' align='center'>".$qte."</td>");
						}else{
							// Il y a une commande identifiee dans la liste mais pas de fiche
							print("<td class='sel' align='center'>TbD</td>");
						}
					}else
						print("<td class='sel' align='center'>".$listeLivres[$i]->Qte."</td>");
					// livre recu ou non
					if($listeLivres[$i]->Recu == "1")
						$recu = "checked";
					else
						$recu = "";
					print("<td class='sel' align='center'><input id='recu_".$listeLivres[$i]->id."' type='checkbox' ".$recu." onclick='recuLivre(".$listeLivres[$i]->id.");'></td>");
					// qt� de livre en commande
					print("<td class='sel'>".$listeLivres[$i]->EnCde."</td>");
					// livre rendu ou non
					if($listeLivres[$i]->Rendu == "1")
						$rendu = "checked";
					else
						$rendu = "";
					print("<td class='sel' align='center'><input id='rendu_".$listeLivres[$i]->id."' type='checkbox' ".$rendu." onclick='renduLivre(".$listeLivres[$i]->id.");'></td>");
					print("</tr>");
				}
				// Livres commandes mais en dehors de la selection
				else if ($listeQte[0]->Qte != "") {
					print("<tr class='cde'>");
					print("<td class='cde'>".$cpt."</td>");
					print("<td align='center' class='cde'><img src='".$listeLivres[$i]->urlCouverture."'></td>");
					print("<td  class='cde'>".$listeLivres[$i]->EAN."</td>");
					print("<td class='cde'>".$listeLivres[$i]->Titre."</td>");
					print("<td class='cde'>".$listeLivres[$i]->Auteur."</td>");
					print("<td class='cde'>".$listeLivres[$i]->Editeur."</td>");
					print("<td style='cursor:pointer;' onclick='showFormUpdate(".$listeLivres[$i]->id.");' align='center' class='cde'>".$listeLivres[$i]->Prix." €</td>");
					print("<td align='center' class='cde'>".$listeLivres[$i]->Categorie."</td>");
					// livre supprimé ou non
					if($listeLivres[$i]->Suppr == "1")
						$supp = "checked";
					else
						$supp = "";
					print("<td class='cde' align='center'><input id='supp_".$listeLivres[$i]->id."' type='checkbox' ".$supp." onclick='suppLivre(".$listeLivres[$i]->id.");'></td>");
					// livre sélection ou non
					if($listeLivres[$i]->Sel == "1")
						$sel = "checked";
					else
						$sel = "";
					print("<td align='center' class='cde'><input id='sel_".$listeLivres[$i]->id."' type='checkbox' ".$sel." onclick='selLivre(".$listeLivres[$i]->id.");'></td>");
					// livre commandé ou non
					print("<td class='cde' align='center'><input id='cde_".$listeLivres[$i]->id."' type='checkbox' checked onclick='cdeLivre(".$listeLivres[$i]->id.");'></td>");
					// Quantit� command�e
					if(sizeof($listeQte)>0){
						for($j=0; $j<sizeof($listeQte); $j++){
							$qte = $qte+$listeQte[$j]->Qte;
						}
						print("<td class='cde' align='center'>".$qte."</td>");
					}else{
						// Il y a une commande identifiee dans la liste mais pas de fiche
						print("<td class='cde' align='center'>TbD</td>");
					}
					// livre recu ou non
					if($listeLivres[$i]->Recu == "1")
						$recu = "checked";
					else
						$recu = "";
					print("<td class='cde' align='center'><input id='recu_".$listeLivres[$i]->id."' type='checkbox' ".$recu." onclick='recuLivre(".$listeLivres[$i]->id.");'></td>");
					// qt� de livre en commande
					print("<td class='cde'>".$listeLivres[$i]->EnCde."</td>");
					// livre rendu ou non
					if($listeLivres[$i]->Rendu == "1")
						$rendu = "checked";
					else
						$rendu = "";
					print("<td class='cde' align='center'><input id='rendu_".$listeLivres[$i]->id."' type='checkbox' ".$rendu." onclick='renduLivre(".$listeLivres[$i]->id.");'></td>");
					print("</tr>");
				}
				else {
					print("<tr>");
					print("<td>".$cpt."</td>");
					print("<td align='center'><img src='".$listeLivres[$i]->urlCouverture."'></td>");
					print("<td >".$listeLivres[$i]->EAN."</td>");
					print("<td>".$listeLivres[$i]->Titre."</td>");
					print("<td>".$listeLivres[$i]->Auteur."</td>");
					print("<td>".$listeLivres[$i]->Editeur."</td>");
					print("<td style='cursor:pointer;' onclick='showFormUpdate(".$listeLivres[$i]->id.");' align='center'>".$listeLivres[$i]->Prix." €</td>");
					print("<td align='center'>".$listeLivres[$i]->Categorie."</td>");
					// livre supprimé ou non
					if($listeLivres[$i]->Suppr == "1")
						$supp = "checked";
					else
						$supp = "";
					print("<td align='center'><input id='supp_".$listeLivres[$i]->id."' type='checkbox' ".$supp." onclick='suppLivre(".$listeLivres[$i]->id.");'></td>");
					// livre sélection ou non
					if($listeLivres[$i]->Sel == "1")
						$sel = "checked";
					else
						$sel = "";
					print("<td align='center'><input id='sel_".$listeLivres[$i]->id."' type='checkbox' ".$sel." onclick='selLivre(".$listeLivres[$i]->id.");'></td>");
					// livre commandé ou non
					if($listeLivres[$i]->Cde == "1")
						$cde = "checked";
					else
						$cde = "";
					print("<td align='center'><input id='cde_".$listeLivres[$i]->id."' type='checkbox' ".$cde." onclick='cdeLivre(".$listeLivres[$i]->id.");'></td>");
					// Quantit� command�e
					print("<td align='center'>".$listeLivres[$i]->Qte."</td>");
					// livre recu ou non
					if($listeLivres[$i]->Recu == "1")
						$recu = "checked";
					else
						$recu = "";
					print("<td align='center'><input id='recu_".$listeLivres[$i]->id."' type='checkbox' ".$recu." onclick='recuLivre(".$listeLivres[$i]->id.");'></td>");
					// qt� de livre en commande
					print("<td>".$listeLivres[$i]->EnCde."</td>");
					// livre rendu ou non
					if($listeLivres[$i]->Rendu == "1")
						$rendu = "checked";
					else
						$rendu = "";
					print("<td align='center'><input id='rendu_".$listeLivres[$i]->id."' type='checkbox' ".$rendu." onclick='renduLivre(".$listeLivres[$i]->id.");'></td>");
					print("</tr>");
				}
			}
		?>
		</tr>
		</font>
	</table>
</div>
<div id="print" style="float:left;">
	<input id="bouton" type="button" onclick="javascript:window.open('bookList.php');return false;" value="imprimer">
	<input id="boutonCde" type="button" onclick="javascript:window.open('bookCde21.php?annee=<?php echo $annee ?>');return false;" value="liste Cdes">
	<input id="boutonSel" type="button" onclick="javascript:window.open('bookSel.php?annee=<?php echo $annee ?>');return false;" value="liste Sel">
	<input id="boutonSel" type="button" onclick="javascript:window.open('bookHorsSel.php?annee=<?php echo $annee ?>');return false;" value="liste hors Sel">
</div>
</body>
</html>
