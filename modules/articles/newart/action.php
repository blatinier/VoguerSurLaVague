<?php
$list_cat_q = "SELECT titre,id FROM voguer_cat WHERE type=0";
$list_cat_q = mysql_query($list_cat_q)or die(mysql_error());
if (!empty($_POST['auteur']) && !empty($_POST['titre']) && !empty($_POST['cat']) && !empty($_POST['texte'])) {
	if (!empty($_POST['mini'])) {
    	$idf = post_fiche_tech($_POST['auteur'],$_POST['titre'],$_POST['texte'],$_POST['cat'],$_POST['pub'],$_POST['mini']);
	}
	$ida = poster_articles($_POST['auteur'],$_POST['titre'],$_POST['texte'],$_POST['cat'],$_POST['pub'],$_POST['is_diy']);
	header("Location: index.php?mod=1&art=".$ida);
}

$maintenant = date("Y-m-d H:i:s",time()+3600*24);
$req = mysql_query("SELECT auteur,titre,cat,art FROM cache WHERE id=1");
$res = mysql_fetch_assoc($req);
$_SESSION['nart_auteur'] = $res['auteur'];
$_SESSION['nart_titre'] = $res['titre'];
$_SESSION['nart_cat'] = $res['cat'];
$_SESSION['nart_art'] = $res['art'];
$_SESSION['is_diy'] = $res['is_diy'];

$fm_javascript="
		function cache_art(){
			var xhr = null;
			if (window.XMLHttpRequest || window.ActiveXObject) {
				if (window.ActiveXObject) {
					try {
						xhr = new ActiveXObject('Msxml2.XMLHTTP');
					} catch(e) {
						xhr = new ActiveXObject('Microsoft.XMLHTTP');
					}
				} else {
					xhr = new XMLHttpRequest(); 
				}
			} else {
				alert('Votre navigateur ne supporte pas l\'objet XMLHTTPRequest...');
				return null;
			}

			auteur = encodeURIComponent(document.getElementById('auteur').value);	
			titre = encodeURIComponent(document.getElementById('titre').value);
			cat = encodeURIComponent(document.getElementById('cat').value);	
			art = encodeURIComponent(document.getElementById('texte').value);	

			xhr.onreadystatechange = function() {
	        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
    	    }
};

			uri = 'auteur='+auteur+'&titre='+titre+'&cat='+cat+'&art='+art;
			xhr.open('GET', 'modules/articles/newart/save.php?'+uri, true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send(null);
		}

		var interval;
		window.onload = function(){
			// 60 secondes entre les demandes
  			interval = setInterval ( 'cache_art()', 60 * 1000); 
		};";
?>
