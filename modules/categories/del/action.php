<?php
$req = get_cat_byId($_GET['id']);
$res = mysql_fetch_assoc($req);

if(strtolower($_POST['val'])=='oui'){
	del_cat($_GET['id']);
	$del = true;
}
?>
