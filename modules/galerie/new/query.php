<?php
function post_img($titre,$cat,$mini){
	
	$titre = mysql_real_escape_string($titre);
	$cat = mysql_real_escape_string($cat);
	$mini = mysql_real_escape_string($mini);

	mysql_query("INSERT INTO voguer_galerie(id,titre,cat,image) 
				VALUES('','".$titre."','".$cat."','".$mini."')");
	return mysql_insert_id();
}

function get_list_cat_img(){
	$q = "SELECT id,titre FROM voguer_cat WHERE type=2";
	$req = mysql_query($q)or die(mysql_error());
	$array = array();
	while($res = mysql_fetch_assoc($req)){
		$array[$res['id']] = $res['titre'];
	}
	return $array;
}
?>
