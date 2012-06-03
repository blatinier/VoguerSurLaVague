<?php

$nom_page='page fiche technique';
$Racine_abs = str_replace($_SERVER['PHP_SELF'],"",$_SERVER['SCRIPT_FILENAME']);
require $Racine_abs.'/modules/allmystats/visiteur.php';

if(!empty($_GET['com']) && $_GET['com']){	
	include("modules/com/action.php");
}


$_GET['page'] = (empty($_GET['page']))? 1 : $_GET['page'];
if(!empty($_GET['ft'])){
	$req = get_fichetech_byId($_GET['ft']);
	$res = mysql_fetch_assoc($req);
	$nbcom = nbcomments($_GET['ft']);
}
$cats = get_list_cat_ft();
$autresfiches_req = get_fiches_tech();
$old = "";
$storenext = false;
while($autrefiches = mysql_fetch_assoc($autresfiches_req)){
	if($storenext){
		$next = $autrefiches['id'];
		$nexttitre = $autrefiches['titre'];
		break;
	}
	if(!empty($res) && $autrefiches['id'] == $res['id']){
		$storenext = true;
	}
	else{ 
		$old = $autrefiches['id'];
		$oldtitre = $autrefiches['titre'];
	}
}

?>
