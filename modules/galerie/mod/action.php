<?php
if(!empty($_POST['mini'])){
	mod_img($_POST['titre'],urldecode($_POST['cat']),$_GET['img'],$_POST['mini']);
}
else{
	$err = true;
}

$req = get_img_byId($_GET['img']);
$res = mysql_fetch_assoc($req);

$res['titre'] = str_replace("\'","'",$res['titre']);
$res['titre'] = str_replace('\"',"",$res['titre']);

$galcat = get_cat_from_id($_GET['img']);

?>
