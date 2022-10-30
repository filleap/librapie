$(document).ready(function(){
	var availableTags = [
	         			"ActionScript",
	         			"AppleScript",
	         			"Asp",
	         			"BASIC",
	         			"C",
	         			"C++",
	         			"Clojure",
	         			"COBOL",
	         			"ColdFusion",
	         			"Erlang",
	         			"Fortran",
	         			"Groovy",
	         			"Haskell",
	         			"Java",
	         			"JavaScript",
	         			"Lisp",
	         			"Perl",
	         			"PHP",
	         			"Python",
	         			"Ruby",
	         			"Scala",
	         			"Scheme"
	 ];
	$( "#nom" ).autocomplete({
		source: availableTags
	});
	$("#Keywords").focus();
});


function choixAnnee(){
    $("#btnChoixAnnee").click();
}

function suppLivre(id) {
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
	
	if (document.getElementById('supp_'+id).checked) {
		var msg = "Voulez vous vraiment supprimer ce livre ?";
	}
	else {
		var msg = "Voulez vous vraiment remettre ce livre dans la liste ?";
	}
	
	if (confirm(msg)){
		xhr_object.open("POST", "suppLivre.php", true);
		xhr_object.onreadystatechange = function(){
			if (xhr_object.readyState == 4) {
				if (xhr_object.responseText == "ok") {
					window.location = "bookList.php";
				}
			}
		}
		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var data="id="+id;
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
					window.location = "bookList.php";
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

function completion() {
	var availableTags = [
		"ActionScript",
		"AppleScript",
		"Asp",
		"BASIC",
		"C",
		"C++",
		"Clojure",
		"COBOL",
		"ColdFusion",
		"Erlang",
		"Fortran",
		"Groovy",
		"Haskell",
		"Java",
		"JavaScript",
		"Lisp",
		"Perl",
		"PHP",
		"Python",
		"Ruby",
		"Scala",
		"Scheme"
	];
	$( "#nom" ).autocomplete({
		source: availableTags
	});
}
