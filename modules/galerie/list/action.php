<?php
$req = get_gal();

$list_cat_q = "SELECT titre,id FROM voguer_cat WHERE type=2";
$list_cat_q = mysql_query($list_cat_q)or die(mysql_error());
while($r = mysql_fetch_assoc($list_cat_q)){
	$cats[$r['id']] = $r['titre'];
}
$oldcat = "";
while($res = mysql_fetch_assoc($req)){
	$array['titre'] = $res['titre'];
	$array['mini'] = $res['image'];
	$array['id'] = $res['cat'];
	$fiche[] = $array;
}
?>
