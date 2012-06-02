<?php

$list_cat_q = "SELECT titre,id FROM voguer_cat WHERE type=0";
$list_cat_q = mysql_query($list_cat_q)or die(mysql_error());

while($r = mysql_fetch_assoc($list_cat_q)){
	$cats[$r['id']] = $r['titre'];
}

$nom_page='page accueil';
$Racine_abs = str_replace($_SERVER['PHP_SELF'],"",$_SERVER['SCRIPT_FILENAME']);
require $Racine_abs.'/modules/visitstat/visiteur.php';

if(!empty($_GET['com'])){	
	include("modules/com/action.php");
}


if(!empty($_GET['change']) && $_GET['change']==1 && $_SESSION['ord']!="ASC")
	$_SESSION['ord'] = "ASC";
elseif(!empty($_GET['change']) && $_GET['change']==1 && $_SESSION['ord']!="DESC")
	$_SESSION['ord'] = "DESC";

$ord = (!empty($_SESSION['ord']))?$_SESSION['ord']:"DESC";


$_GET['page'] = (empty($_GET['page']))? 1 : $_GET['page'];
if(!empty($_GET['art'])){
	$req = get_article_byId($_GET['art']);
	$nbcom = nbcomments($_GET['art']);
}
elseif(!empty($_GET['cat'])){
    $req = get_articles("pubdate",$_GET['cat'],$ord,$_GET['page']);
	$nbcom = nbcomments(NULL);
}
else{
	$req = get_articles("pubdate","",$ord,$_GET['page']);
	$nbcom = nbcomments(NULL);
}
?>
