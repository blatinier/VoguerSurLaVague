<?php
$cat = (empty($_GET['cat'])) ? "" : $_GET['cat'];
$req = get_fiches_tech($cat);

$oldcat = "";
while($res = mysql_fetch_assoc($req)){
	if($res['cat_id'] != $oldcat){
		$oldcat = $res['cat_id'];
		$array['id'] = 'cat';
		$array['name'] = $res['cat_title'];
		$fiche[] = $array;
	}
	$array['titre'] = $res['ft_title'];
	$array['mini'] = $res['miniature'];
	$array['id'] = $res['id'];
	$fiche[] = $array;
}
?>
