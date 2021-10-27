<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
 <head>
  <meta http-equiv="refresh" content="2 ; url=index.php"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<title>LibrAPIE - Livre ajouté</title>
 </head>
 <body>
	 <div class="container">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-2">
					<a href="index.php">
						<img height="100px" src="./sapin-noel.png"/>
					</a>
				</div>
				<div class="col-sm-8">
					<h1>Bienvenue dans librAPIE <?php print($_SESSION['pseudo']); ?></h1>
					<caption>la bibliothèque du marché de Noël</caption>
				</div>
				<div class="col-sm-2">
						<img height="100px" src="./Logo_APIE.gif"/>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
      <?php
        $annee = $_POST['Annee'];
        $url = $_POST['URL'];
        $isbn = $_POST['ISBN'];
        $ean = $_POST['EAN'];
        $titre = addslashes($_POST['Titre']);
        $auteur = addslashes($_POST['Auteur']);
        $editeur = addslashes($_POST['Editeur']);
        $prix = $_POST['Prix'];
        $categorie = $_POST['Categorie'];
        $choix = $_POST['Choix'];
        $sel = $_POST['sel_laprocure'];

        if($ean <> ""){
          if($prix <> ""){
            // mysql_query("SET NAMES UTF8");

            if($sel == "on")
            	$sel = 1;
            else
            	$sel = 0;

            if ($annee == "") {
            // positionnement de l'année courante
              // $date = getdate();
              // $annee = $date[year];
              $annee = "2021";
            }

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


            //vérification que le livre n'est pas déjà dans la liste
            if($isbn <> ""){
              $query = "SELECT * FROM ".$annee."_APIE_Livres WHERE ISBN='$isbn' OR EAN='$ean'";
            } else{
              $query = "SELECT * FROM ".$annee."_APIE_Livres WHERE EAN='$ean'";
            }
           
            $resultTestLivre = $cnx->query($query) or die($cnx->error);

            while ($row = mysqli_fetch_object($resultTestLivre)) {
            	$dbLivre[] = $row;
            }

            if ($dbLivre[0]->EAN == $ean && $ean <> ""){
              print("<div class='alert alert-danger' role='alert'>");
              print("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>");
              print("<span class='sr-only'>Error:</span>");
            	print(" Imposible d'ajouter. Livre deja dans la liste !");
              print("</div>");
            }
            else if ($dbLivre[0]->ISBN == $isbn && $isbn <> ""){
              print("<div class='alert alert-danger' role='alert'>");
              print("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>");
              print("<span class='sr-only'>Error:</span>");
            	print(" Imposible d'ajouter. <b>Livre deja dans la liste !</b>");
              print("</div>");
            }
            else{
            	$request = "INSERT INTO ".$annee."_APIE_Livres (ISBN, EAN, urlCouverture, Titre, Auteur, Editeur, Prix, Categorie, Choix, Sel) VALUES ('$isbn', '$ean', '$url', '$titre', '$auteur', '$editeur', $prix, '$categorie', '$choix', '$sel')";
            	// print_r($request);
            	$result = $cnx->query($request) or die($cnx->error);

            	// ajout de 1 exemplaire en stock par défaut
            	$request = "INSERT INTO ".$annee."_APIE_Stock_Livres (ISBN, EAN, Titre, Prix, Qte) VALUES ('$isbn', '$ean', '$titre', $prix, '0')";
            	$resul = $cnx->query($request) or die($cnx->error);

            	if($dbLivre[0]->EAN == $ean){
                print("<div class='alert alert-success' role='alert'>");
                print("<span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>");
                print("<span class='sr-only'>Error:</span>");
            		print(" Livre selectionn&eacute en ".$dbLivre[0]->Annee."<p>Livre ajout&eacute; !");
                print("</div>");
            	} else {
                print("<div class='alert alert-success' role='alert'>");
                print("<span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>");
                print("<span class='sr-only'>Error:</span>");
            		print(" Livre <b>".$titre."</b> ajout&eacute; !");
            		print("<p>Redirection automatique vers l'accueil. Si cela n'était pas le cas cliquez sur la maison.");
                print("</div>");
              }
            }
          } else {
            print("<div class='alert alert-danger' role='alert'>");
            print("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>");
            print("<span class='sr-only'>Error:</span>");
            print("Il faut renseigner un <b>prix</b> à l'article.");
            print("</div>");
            print("<button type='button' title='retour détail livre' class='btn btn-default' onclick='javascript:history.go(-1)'>");
            print("<span class='glyphicon glyphicon-arrow-left'></span>");
            print("</button>");
          }
        } else {
          print("Il faut que l'article ait un <b>numéro ISBN</b>.");
          print("<br>");
          print("<button type='button' title='retour détail livre' class='btn btn-default' onclick='javascript:history.go(-1)'>");
          print("<span class='glyphicon glyphicon-arrow-left'></span>");
          print("</button>");
        }
      ?>
      <button type="button" title="retour à l'acceuil" class="btn btn-default" onclick="javascript:window.location='index.php'">
        <span class="glyphicon glyphicon-home"></span>
      </button>
  	</div>
  </div>
</body>
</html>
