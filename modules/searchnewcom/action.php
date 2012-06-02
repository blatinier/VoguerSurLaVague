<?php

if(!$_SESSION['ok']){
	header('Location: index.php');
}


if($_GET['read'] && $_GET['art']){
	markComment($_GET['read']);
	header('Location: index.php?art='.$_GET['art']);
}

if($_GET['all'] == 1){
	markAllComments();
}

$listcomreq = getNewComments();
$nbnlucom = 0;
while($listcomres = mysql_fetch_assoc($listcomreq)){
	$rliens[] = "read=".$listcomres['id']."&amp;art=".$listcomres['idarticle'];
	$titres[] = $listcomres['titre'];
	$pseudos[] = $listcomres['pseudo'];
	$nbnlucom++;
}
?>
