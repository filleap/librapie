function verifUser(){
	var pseudo = document.getElementById('pseudo').value;
	var pass = document.getElementById('pass').value;
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}
	xhr_object.open("POST", "verifCnx.php", true);
	xhr_object.onreadystatechange = function(){
		if (xhr_object.readyState == 4) {
			if (xhr_object.responseText == "ok") {
				window.location = "index.php";
			}
			else{
				alert(xhr_object.responseText);
			}
		}
	}
	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	var data="pseudo="+pseudo+"&pass="+pass;
	xhr_object.send(data);
}

function choixAnnee(){
    $("#btnChoixAnnee").click();
}

function majQteStock(annee, EAN){
  var qte = document.getElementById('qte_'+EAN).value;
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

  // alert("ça marche ! "+annee+" "+EAN+" "+qte);

  // mise à jour du stock
	xhr_object.open("POST", "updateStock.php", true);
	xhr_object.onreadystatechange = function(){
		if (xhr_object.readyState == 4) {
			if (xhr_object.responseText == "ok") {
				window.location = "bookStock.php";
			}
      else{
        alert(xhr_object.responseText);
      }
		}
	}
	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	var data="annee="+annee+"&EAN="+EAN+"&qte="+qte;
	xhr_object.send(data);
}

function majQteCdee(annee, idCde, idPersonne){
  var qteCdee = document.getElementById('qte_'+idCde).value;
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

  // alert("ça marche ! "+annee+" "+EAN+" "+qte);

  // mise à jour de la quantité commandée
	xhr_object.open("POST", "updateCde.php", true);
	xhr_object.onreadystatechange = function(){
		if (xhr_object.readyState == 4) {
			if (xhr_object.responseText == "ok") {
				window.location = "formCde.php?id="+idPersonne;
			}
      else{
        alert(xhr_object.responseText);
      }
		}
	}
	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	var data="annee="+annee+"&idCde="+idCde+"&qte="+qteCdee;
	xhr_object.send(data);
}

function majPrixLivre(annee, EAN){
  var prix = document.getElementById('prix_'+EAN).value;
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

  // alert("ça marche ! "+annee+" "+EAN+" "+qte);

  // mise à jour du stock
	xhr_object.open("POST", "updatePrix.php", true);
	xhr_object.onreadystatechange = function(){
		if (xhr_object.readyState == 4) {
			if (xhr_object.responseText == "ok") {
				window.location = "bookStock.php";
			}
      else{
        alert(xhr_object.responseText);
      }
		}
	}
	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	var data="annee="+annee+"&EAN="+EAN+"&prix="+prix;
	xhr_object.send(data);
}

function suppLivre(id, annee) {
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

	if (confirm("Voulez vous vraiment supprimer ce livre ?")){
		xhr_object.open("POST", "suppLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "index.php";
				}
				else if (xhr_object.responseText == "commande"){
					alert("Impossible de supprimer ce livre,\nune commande est en cours.");
				}
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+id+"&annee="+annee;
		xhr_object.send(data);
	}
	else {
		// suppression de la coche dans la case
		document.getElementById('supp_'+id).checked = 0;
	}

}

function selLivre(id) {
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

	if (document.getElementById('sel_'+id).checked) {
		var msg = "Voulez ajouter ce livre a la selection ?";
	}
	else {
		var msg = "Voulez supprimer ce livre de la selection ?";
	}

	if (confirm(msg)){
		xhr_object.open("POST", "selLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "index.php";
				}
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+id;
		xhr_object.send(data);
	}
	else {
		// suppression de la coche dans la case
		document.getElementById('sel_'+id).checked = 0;
	}

}

function cdeLivre(id) {
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

	if (document.getElementById('cde_'+id).checked) {
		var msg = "Ce livre est-il vraiment une commande ?";
	}
	else {
		var msg = "Voulez supprimer cette commande ?";
	}

	if (confirm(msg)){
		xhr_object.open("POST", "cdeLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "bookList.php";
				}
				else
					alert(xhr_object.responseText+" dans la mise a jour !");
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+id;
		xhr_object.send(data);
	}
	else {
		// suppression de la coche dans la case
		document.getElementById('cde_'+id).checked = 0;
	}

}

function recuLivre(id) {
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

	if (document.getElementById('recu_'+id).checked) {
		var msg = "Ce livre a-t-il vraiment ete recu ?";
	}
	else {
		var msg = "Voulez supprimer la reception de ce livre ?";
	}

	if (confirm(msg)){
		xhr_object.open("POST", "recuLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "bookList.php";
				}
				else
					alert(xhr_object.responseText+" dans la mise a jour !");
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+id;
		xhr_object.send(data);
	}
	else {
		// suppression de la coche dans la case
		document.getElementById('recu_'+id).checked = 0;
	}

}

function renduLivre(id) {
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

	if (document.getElementById('rendu_'+id).checked) {
		var msg = "Ce livre a-t-il vraiment ete rendu ?";
	}
	else {
		var msg = "Voulez supprimer le retour de ce livre ?";
	}

	if (confirm(msg)){
		xhr_object.open("POST", "renduLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "bookList.php";
				}
				else
					alert(xhr_object.responseText+" dans la mise a jour !");
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+id;
		xhr_object.send(data);
	}
	else {
		// suppression de la coche dans la case
		document.getElementById('rendu_'+id).checked = 0;
	}

}

function qteLivre(id) {
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

	var qte = document.getElementById('qte_'+id).value;
	var msg = "Commander "+qte+" ?";

	if (confirm(msg)){
		xhr_object.open("POST", "qteLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "bookList.php";
				}
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+id+"&qte="+qte;
		xhr_object.send(data);
	}
	else {
		// suppression de la coche dans la case
		document.getElementById('cde_'+id).checked = 0;
	}

}

function editLivre(ean, annee){
  $('#modale').show();
  $('#urlCouv').hide();
  // interrogation base de données
  if (window.XMLHttpRequest) // Firefox
      xhr_object = new XMLHttpRequest();
  else
    if (window.ActiveXObject) // Internet Explorer
        xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
    else { // XMLHttpRequest non support� par le navigateur
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
        return;
    }

  xhr_object.open("POST", "infoLivre.php", true);
  xhr_object.onreadystatechange = function(){
    if (xhr_object.readyState == 4) {
      // alert(xhr_object.responseText);
      try{
        livre = JSON.parse(xhr_object.responseText);
      } catch(e){
        console.error("Erreur : "+e);
	  }
      $('#urlCouverture').attr('src', livre.urlCouverture);
	  $('#Couverture').val(livre.urlCouverture);
      $("#Choix").val(livre.Choix);
      $('#EAN').val(livre.EAN);
      $("#Titre").val(livre.Titre);
      $("#Auteur").val(livre.Auteur);
      $("#Editeur").val(livre.Editeur);
      $("#Prix").val(parseFloat(livre.Prix).toFixed(2));
      $("#Qte").val(livre.Qte);
	  $("#Categorie").val(livre.Categorie);
	  if(livre.Cde == 1){
		  $("#Cde").attr('checked', true);
	  } else{
		$("#Cde").attr('checked', false);
	  }
	  $("#QteCdee").val(livre.QteCdee);
	  if(livre.Rendu == 1){
		  $("#Rendu").attr('checked', true);
	  } else{
		$("#Rendu").attr('checked', false);
	  }
	  $("#QteRendue").val(livre.QteRendue);
    }
  }
  xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var data="EAN="+ean+"&Annee="+annee;
  xhr_object.send(data);
}

function getAvis(isbn){
	//alert("isbn : "+isbn);
	document.getElementsByTagName('body')[0].style.cursor = 'wait';
    var xhr_object = null;
    if (window.XMLHttpRequest) // Firefox
        xhr_object = new XMLHttpRequest();
    else
        if (window.ActiveXObject) // Internet Explorer
            xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
        else { // XMLHttpRequest non support� par le navigateur
            alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
            return;
        }

    xhr_object.open("POST", "avisLivre.php", true);
    xhr_object.onreadystatechange = function(){
        if (xhr_object.readyState == 4) {
			alert(xhr_object.responseText);
			document.getElementsByTagName('body')[0].style.cursor = 'default';
        }
    }
	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	var data="ISBN="+isbn;
	xhr_object.send(data);
}

function showFormUpdate(id){
	//alert("id : "+id);
	window.open('./formMaJ.php?id='+id, 'Update', 'width=800,height=600,scrollbars=1');
}

function soumettre(){
    document.getElementById("btnValider").submit;
}

function showCde(annee){
	var idPersonnes = $("#personnes").val();
	// var annee = $("#annee").val();
	window.open('formCde.php?id='+idPersonnes+'&annee='+annee);

}

function paye(idCde, idPers) {
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

	if (document.getElementById('paye_'+idCde).checked) {
		var msg = "Ce livre a t il bien ete paye ?";
	}
	else {
		var msg = "Souhaitez vous vraiment supprimer le payement de ce livre ?";
	}

	if (confirm(msg)){
		xhr_object.open("POST", "payeLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "formCde.php?id="+idPers;
				}
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+idCde;
		xhr_object.send(data);
	}
	else {
		// suppression de la coche dans la case
		document.getElementById('paye_'+idCde).checked = 0;
	}

}

function donne(idCde, idPers, qte) {
	// mise a jour de la base
	var xhr_object = null;
	if (window.XMLHttpRequest){
		// Firefox
		xhr_object = new XMLHttpRequest();
	}
	else {
		if (window.ActiveXObject) // Internet Explorer
			xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else { // XMLHttpRequest non support� par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
		}
	}

	if (document.getElementById('donne_'+idCde).checked) {
		var msg = "Ce livre a t il bien ete donné ?\nSi oui combien d'exemplaires ont été donné ?";
	}
	else {
		var msg = "Souhaitez annuler la livraison de ce livre ?\nSi oui combien d'exemplaires rendez-vous ?";
	}

	if (qte = prompt(msg, "1")){
		xhr_object.open("POST", "donneLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "formCde.php?id="+idPers;
				} else {
          alert(xhr_object.responseText);
        }
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+idCde+"&qte="+qte;
		xhr_object.send(data);
	}
	else {
		// suppression de la coche dans la case
		document.getElementById('donne_'+idCde).checked = 0;
	}

}
