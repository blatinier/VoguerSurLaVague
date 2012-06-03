<?php

$nom_page='page galerie';
$Racine_abs = str_replace($_SERVER['PHP_SELF'],"",$_SERVER['SCRIPT_FILENAME']);
require $Racine_abs.'/modules/allmystats/visiteur.php';

$_GET['page'] = (empty($_GET['page']))? 1 : $_GET['page'];
if(!empty($_GET['img'])){
	$req = get_galerie_byId(urldecode($_GET['img']));
	$oldcat = "";
	while($res = mysql_fetch_assoc($req)){
		if($encoded_cat != $oldcat){
			$oldcat = $encoded_cat;
			$array['id'] = 'cat';
			$array['name'] = $res['cat'];
			$fiche[] = $array;
		}
		$array['titre'] = $res['titre'];
		$array['mini'] = $res['image'];
		$array['id'] = $res['id'];
		$fiche[] = $array;
	}
}

$cats = get_list_cat_img();
?>
