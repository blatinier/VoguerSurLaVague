<?php
$q = getAllCat();

$id = array();
$titre = array();
$abstract = array();
$type = array();

while($r = mysql_fetch_assoc($q)){
	$id[] = $r['id'];
	$titre[] = $r['titre'];
	$abstract[] = $r['abstract'];
	$type[] = $r['type'];
}
?>
