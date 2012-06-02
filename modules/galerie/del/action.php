<?php
$req = get_galerie_byId(urldecode($_GET['img']));
$res = mysql_fetch_assoc($req);

if(strtolower($_POST['val'])=='oui'){
	del_galerie(urldecode($_GET['img']));
	$del = true;
}
?>
