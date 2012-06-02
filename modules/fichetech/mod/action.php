<?php
if(!empty($_POST['auteur']) && !empty($_POST['titre']) && !empty($_POST['cat']) && !empty($_POST['texte'])){
	mod_fichetech($_POST['auteur'],$_POST['titre'],$_POST['texte'],$_POST['cat'],$_GET['ft'],$_POST['pub'],$_POST['mini']);
}
else{
	$err = true;
}

$req = get_fichetech_byId($_GET['ft']);
$res = mysql_fetch_assoc($req);

$res['titre'] = str_replace("\'","'",$res['titre']);
$res['titre'] = str_replace('\"',"",$res['titre']);

?>
