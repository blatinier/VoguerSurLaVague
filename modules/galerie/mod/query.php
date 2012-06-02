<?php
function mod_img($titre,$cat,$id,$mini){

	$id = (int)$id;
	$titre = mysql_real_escape_string($titre);
	$cat = mysql_real_escape_string($cat);
	$mini = mysql_real_escape_string($mini);
	$req = "UPDATE voguer_galerie 
		SET	titre='".$titre."', 
			cat='".$cat."',
			image='".$mini."' 
		WHERE id=".$id;
	mysql_query($req)or die(mysql_error());
	
}

function get_img_byId($id){
	$id = (int)$id;

	return mysql_query("SELECT 
							id,
							titre,
							image,
							cat
							FROM voguer_galerie WHERE id=".$id);
}

function get_cat_from_id($id){
	$id = (int)$id;
	$r = "SELECT cat FROM voguer_galerie WHERE id=".$id;
	$req = mysql_query($r)or die(mysql_error());
	$res = mysql_fetch_assoc($req);
	return $res['cat'];
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
