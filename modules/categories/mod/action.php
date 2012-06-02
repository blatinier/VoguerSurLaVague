<?php

if($_SESSION['ok'] == 1){
	if(!empty($_POST['titre']) && !empty($_POST['texte'])){
		mod_cat($_GET['id'], $_POST['titre'], $_POST['texte'], $_POST['type']);
		$poste = true;
	}
}
$c = get_cat_byId($_GET['id']);
$c = mysql_fetch_assoc($c);
$titre = $c['titre'];
$type = $c['type'];
$abstract = $c['abstract'];
?>
