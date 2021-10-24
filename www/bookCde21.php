<?php
// Liste des livres commandes par une personne

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

//$query = "SELECT l.urlCouverture, l.EAN, l.Titre, l.Editeur, l.Prix, l.Categorie, COUNT(l.EAN) as Qte , l.Sel, p.Nom, p.Prenom, c.Donne
$query = "SELECT l.urlCouverture, l.EAN, l.Titre, l.Editeur, l.Prix, l.Categorie, c.Qte as Qte , l.Sel, p.Nom, p.Prenom, c.Donne
	FROM ".$annee."_APIE_Commandes as c, ".$annee."_APIE_Livres as l, APIE_Personnes as p
	WHERE c.EAN = l.EAN AND c.idPersonne = p.id
	GROUP BY l.EAN ORDER BY Titre";
$result = mysql_query($query) or die(mysql_error());

while ($row = mysql_fetch_object($result)) {
	$listeCommandes[] = $row;
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
	<title> LibrAPIE</title>
    </head>
    <body>
<div style="font-family: Arial; font-size: 2;" id="content" class="content">
	<table border="1" id="listeLivres">
        <caption class="tab_title">Liste des livres commandés pour l'année <?php print($annee); ?></caption>
		<tr>
			<th>cpt</th>
			<th>Couverture</th>
			<th>ISBN-13</th>
			<th class="sortable-text">Titre</th>
			<th>Editeur</th>
			<th>Prix</th>
			<th>Cat</th>
			<th>Nom/Prenom</th>
			<th>Qté Cdée</th>
			<th>Donné</th>
		</tr>
		<?php
			$cpt = 0;
			$total = 0;
			for($i=0; $i<sizeof($listeCommandes); $i++){
				$cpt = $cpt+1;
				print("<tr>");
				if($listeCommandes[$i]->Sel == "1"){
					if($listeCommandes[$i]->Qte==1){
						$prix = $listeCommandes[$i]->Prix;
						print("<td align='center' class='sel'>".$cpt."</td>");
						print("<td align='center' class='sel'><img src='".$listeCommandes[$i]->urlCouverture."'></td>");
						print("<td class='sel'>".$listeCommandes[$i]->EAN."</td>");
						print("<td class='sel'>".$listeCommandes[$i]->Titre."</td>");
						print("<td class='sel'>".$listeCommandes[$i]->Editeur."</td>");
						print("<td align='center' class='sel'>".$listeCommandes[$i]->Prix." €</td>");
						print("<td class='sel'>".$listeCommandes[$i]->Categorie."</td>");
						print("<td class='sel'>".$listeCommandes[$i]->Nom." ".$listeCommandes[$i]->Prenom."</td>");
						print("<td align='center' class='sel'>".$listeCommandes[$i]->Qte."</td>");
						if($listeCommandes[$i]->Donne == "1")
							$donne = "checked";
						else
							$donne = "";
						print("<td align='center' class='sel'><input id='cde_".$listeCommandes[$i]->idCde."' type='checkbox' ".$donne."></td>");
						$total = $total + $prix;
					} else{
						$prix = $listeCommandes[$i]->Prix;
						$ean = $listeCommandes[$i]->EAN;
						$qte=0;
						$listeDetails = "";
						$query="SELECT c.Qte, p.Nom, p.Prenom , c.Donne
							FROM ".$annee."_APIE_Commandes c, APIE_Personnes p
							WHERE p.id=c.idPersonne AND c.EAN='$ean'";
						$result = mysql_query($query) or die(mysql_error());

						while ($row = mysql_fetch_object($result)) {
							$listeDetails[] = $row;
						}
						print("<td align='center' class='sel' rowspan='".sizeof($listeDetails)."'>".$cpt."</td>");
						print("<td align='center' class='sel' rowspan='".sizeof($listeDetails)."'><img src='".$listeCommandes[$i]->urlCouverture."'></td>");
						print("<td class='sel' rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->EAN."</td>");
						print("<td class='sel' rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->Titre."</td>");
						print("<td class='sel' rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->Editeur."</td>");
						print("<td align='center' class='sel' rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->Prix." €</td>");
						print("<td class='sel' rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->Categorie."</td>");
						for($j=0; $j<sizeof($listeDetails); $j++){
							print("<td class='sel'>".$listeDetails[$j]->Nom." ".$listeDetails[$j]->Prenom."</td>");
							print("<td align='center' class='sel'>".$listeDetails[$j]->Qte."</td>");
							if($listeDetails[$j]->Donne == "1")
								$donne = "checked";
							else
								$donne = "";
							print("<td align='center' class='sel'><input id='cde_".$listeDetails[$j]->idCde."' type='checkbox' ".$donne."></td>");
							print("</tr>");
							print("<tr>");
							$total = $total + ($listeDetails[$j]->Qte*$prix);
						}
					}
				} else {
					if($listeCommandes[$i]->Qte==1){
						$prix = $listeCommandes[$i]->Prix;
						print("<td align='center'>".$cpt."</td>");
						print("<td align='center'><img src='".$listeCommandes[$i]->urlCouverture."'></td>");
						print("<td>".$listeCommandes[$i]->EAN."</td>");
						print("<td>".$listeCommandes[$i]->Titre."</td>");
						print("<td>".$listeCommandes[$i]->Editeur."</td>");
						print("<td align='center'>".$listeCommandes[$i]->Prix." €</td>");
						print("<td>".$listeCommandes[$i]->Categorie."</td>");
						print("<td>".$listeCommandes[$i]->Nom." ".$listeCommandes[$i]->Prenom."</td>");
						print("<td align='center'>".$listeCommandes[$i]->Qte."</td>");
						if($listeCommandes[$i]->Donne == "1")
							$donne = "checked";
						else
							$donne = "";
						print("<td align='center'><input id='cde_".$listeCommandes[$i]->idCde."' type='checkbox' ".$donne."></td>");
						$total = $total + $prix;
					} else{
						$prix = $listeCommandes[$i]->Prix;
						$total = $total + $prix;
						$ean = $listeCommandes[$i]->EAN;
						$qte=0;
						$listeDetails = "";
						$query="SELECT c.Qte, p.Nom, p.Prenom, c.Donne
							FROM ".$annee."_APIE_Commandes c, APIE_Personnes p
							WHERE p.id=c.idPersonne AND c.EAN='$ean'";
						$result = mysql_query($query) or die(mysql_error());

						while ($row = mysql_fetch_object($result)) {
							$listeDetails[] = $row;
						}
						print("<td align='center' rowspan='".sizeof($listeDetails)."'>".$cpt."</td>");
						print("<td align='center' rowspan='".sizeof($listeDetails)."'><img src='".$listeCommandes[$i]->urlCouverture."'></td>");
						print("<td rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->EAN."</td>");
						print("<td rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->Titre."</td>");
						print("<td rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->Editeur."</td>");
						print("<td align='center' rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->Prix." €</td>");
						print("<td rowspan='".sizeof($listeDetails)."'>".$listeCommandes[$i]->Categorie."</td>");
						for($j=0; $j<sizeof($listeDetails); $j++){
							print("<td>".$listeDetails[$j]->Nom." ".$listeDetails[$j]->Prenom."</td>");
							print("<td align='center'>".$listeDetails[$j]->Qte."</td>");
							if($listeDetails[$j]->Donne == "1")
								$donne = "checked";
							else
								$donne = "";
							print("<td align='center'><input id='cde_".$listeDetails[$j]->idCde."' type='checkbox' ".$donne."></td>");
							print("</tr>");
							print("<tr>");
							$total = $total + ($listeDetails[$j]->Qte*$prix);
						}
					}
				}
				print("</tr>");
			}
		?>
		</tr>
		</font>
	</table>
</div>
<h1 size="20px"><?php print("Total: ".$total." €"); ?></h1>
</body>
</html>
