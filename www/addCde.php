
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
 <head>
  <meta http-equiv="refresh" content="2 ; url=index.php"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<title>LibrAPIE - Commande prise en compte</title>
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
    <div class="col-sm-12">
      <?php
      // positionnement de l'année courante
      // $date = getdate();
      // $annee = $date[year];
      $annee = "2021";
      $periode = "1";

      // Recuperation des parametres
      $idPersonne = $_POST['idPersonne'];
      $nom = $_POST['nom'];
      $prenom = $_POST['prenom'];
      $isbn = $_POST['isbn'];
      $qte = $_POST['qte'];

      print("<h1>".$prenom." ".$nom."</h1>");

      if($isbn <> ""){
        if($qte <> ""){
          // Connexion a la base de donnees
          include("dbconf.php");

          // $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
          // $cnx = mysqli_connect($host, $user, $mdp);
        
          $cnx = mysqli_connect($host, $user, $mdp);
          if(!cnx){
            die("Connexion a mysql impossible : ".$cnx->connect_error);
          }
          mysqli_select_db($cnx,$bdd);

          // vérification de la présence du livre dans la base
          $query = "SELECT Titre FROM ".$annee."_APIE_Livres WHERE EAN='$isbn'";
          $result = $cnx->query($query) or die($cnx->error);

          while ($row = mysqli_fetch_object($result)) {
          	$livres[] = $row;
          }

          if(sizeof($livres) > 0){

            // vérification si le livre n'est pas déjà commandé pour cette personne
            $query = "SELECT idCde FROM ".$annee."_APIE_Commandes WHERE idPersonne='$idPersonne' AND EAN='$isbn'";
            $result = $cnx->query($query) or die($cnx->error);

            while ($row = mysqli_fetch_object($result)) {
            	$livreCde[] = $row;
            }

            if(sizeof($livreCde) == O){
              // recuperation des quantites deja commandes
              // $query = "SELECT Qte FROM ".$annee."_APIE_Commandes WHERE EAN='$isbn'";
              // $result = mysql_query($query) or die(mysql_error());
              //
              // while ($row = mysql_fetch_object($result)) {
              // 	$qteList[] = $row;
              // }

              if($nom == ""){
              	$query = "INSERT INTO ".$annee."_APIE_Commandes (EAN, Qte, idPersonne, Periode) VALUES ('$isbn', '$qte', '$idPersonne', '$periode')";
              	$result = $cnx->query($query) or die($cnx->error);
              	$idCde = mysqli_insert_id($cnx);
                print("<div class='alert alert-success' role='alert'>");
                print("<span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>");
                print("<span class='sr-only'>Error:</span>");
                print(" Commande prise en compte.");
                print("</div>");
              }
              else{
              	$query = "INSERT INTO APIE_Personnes (Prenom, Nom) VALUES ('$prenom', '$nom')";
              	// print($query);
              	if($result = $cnx->query($query) or die($cnx->error)){
              		$idPersonne = mysql_insert_id();
              		$query = "INSERT INTO ".$annee."_APIE_Commandes (EAN, Qte, idPersonne, Periode) VALUES ('$isbn', '$qte', '$idPersonne', '$periode')";
                  print("<div class='alert alert-success' role='alert'>");
                  print("<span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>");
                  print("<span class='sr-only'>Error:</span>");
              		print(" Commande prise en compte.");
                  print("</div>");
              		$result = $cnx->query($query) or die($cnx->error);
              		$idCde = mysqli_insert_id($cnx);
              	}
              }
            } else{
              // récupération des information sur la personne
              $query = "SELECT * FROM APIE_Personnes WHERE id='$idPersonne'";
              $result = $cnx->query($query) or die($cnx->error);

              while ($row = mysqli_fetch_object($result)) {
              	$personne[] = $row;
              }
              print("<div class='alert alert-danger' role='alert'>");
              print("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>");
              print("<span class='sr-only'>Error:</span>");
              print("<b> ".$livres[0]->Titre."</b> est déjà commandé pour <b>".$personne[0]->Prenom." ".$personne[0]->Nom."</b>");
              print("<p>Passer par le détail de la commande pour modifier la commande en modifiant la quantité ou en supprimant l'article.</p>");
              print("</div>");
            }
          }
          else {
            print("<div class='alert alert-danger' role='alert'>");
            print("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>");
            print("<span class='sr-only'>Error:</span>");
            print(" Le livre n'est pas dans la base. <b>Impossible de passer la commande.</b>");
            print("</div>");
          }
        } else {
          print("<div class='alert alert-danger' role='alert'>");
          print("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>");
          print("<span class='sr-only'>Error:</span>");
          print(" Il faut entrer une <b>quantité</b>.");
          print("</div>");
        }
      } else {
        print("<div class='alert alert-danger' role='alert'>");
        print("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>");
        print("<span class='sr-only'>Error:</span>");
        print(" Il faut entrer un <b>numéro ISBN</b> de livre.");
        print("</div>");
      }
      ?>
      <button type="button" title="retour à l'accueil" class="btn btn-default" onclick="javascript:window.location='formCdeLivre.php'">
        <span class="glyphicon glyphicon-arrow-left"></span>
      </button>
			</div>
	   </div>
  </body>
</html>
