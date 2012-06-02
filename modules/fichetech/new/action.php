<?php

if(!empty($_POST['auteur']) && !empty($_POST['titre']) && !empty($_POST['cat']) && !empty($_POST['texte'])){
	
	$ida = post_fiche_tech($_POST['auteur'],$_POST['titre'],$_POST['texte'],$_POST['cat'],$_POST['pub'],$_POST['mini']);
	header("Location: index.php?p=modft&ft=".$ida);
}

$maintenant = date("Y-m-d H:i:s");
?>
