<?php
function get_galerie_byId($id){
	$id = mysql_real_escape_string(stripslashes($id));

	$r = "SELECT 
				id,
				titre,
				image,
				cat
			FROM 
				voguer_galerie 
			WHERE 
				cat='".$id."'
			ORDER BY 
				image";
	$req = mysql_query($r)or die(mysql_error());
	return $req;
}

function get_list_cat_img(){
	$q = "SELECT id,titre,abstract FROM voguer_cat WHERE type=2";
	$req = mysql_query($q)or die(mysql_error());
	$array = array();
	while($res = mysql_fetch_assoc($req)){
		$array[$res['id']]['titre'] = $res['titre'];
		$array[$res['id']]['resume'] = $res['abstract'];
	}
	return $array;
}

?>
