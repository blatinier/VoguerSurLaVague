<?php

if(strtolower($_POST['val'])=='oui'){
	del_com($_GET['c']);
	$deleted = true;
}
else{
	$req = get_com_byId($_GET['c']);
	$res = mysql_fetch_assoc($req);
}
?>
