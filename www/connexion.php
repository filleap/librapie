<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="./bootstrap-3.3.5-dist/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="css/default.css"/> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
  <script src="./bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
	<title>LibrAPIE - Page de connexion</title>
 </head>
 <body  onload="javascript:document.getElementById('pseudo').focus();">
	 <div class="container">
		 <div class="page-header">
			 <div class="row">
				 <div class="col-sm-2">
					 <img height="100px" src="./Logo_APIE.gif"/>
				 </div>
				 <div class="col-sm-10">
					 <h1>Bienvenue dans librAPIE</h1>
					 <caption>la bibliothèque du marché de Noël</caption>
				 </div>
			 </div>
		 </div>
		 <div class="row">
			 <form action="verifCnx.php" method="post">
				 peudo <input type="text" id="pseudo" name="pseudo">
				 <br>
				 mot de passe <input type="password" id="pass" name="pass">
				 <br>
				 <button title="valider" type="submit" class="btn btn-default">
					 valider
				 </button>
			 </form>
		 </div>
	 </div>
 </body>
</html>
