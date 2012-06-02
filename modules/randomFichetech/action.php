<?php
$cat = $_GET['cat'];
$req = get_rand_fiche();

$res = mysql_fetch_assoc($req);
	
$rand_fiche['titre'] = $res['titre'];
$rand_fiche['mini'] = $res['miniature'];
$rand_fiche['id'] = $res['id'];
}
?>
