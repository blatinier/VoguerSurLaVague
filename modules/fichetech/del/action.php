<?php
$req = get_fichetech_byId($_GET['ft']);
$res = mysql_fetch_assoc($req);

if(strtolower($_POST['val'])=='oui'){
	del_fichetech($_GET['ft']);
	$del = true;
}
?>
