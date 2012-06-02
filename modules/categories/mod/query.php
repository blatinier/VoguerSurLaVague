<?php
function get_list_typecat(){
	return	array("Article","Astuces","Galerie");	
}

function get_cat_byId($id){
	$id = (int)$id;
	$q = "SELECT titre, type, abstract FROM voguer_cat WHERE id=".$id;
	$q = mysql_query($q);
	return $q;
} 

function mod_cat($id, $titre, $abstract, $type){
	$titre = mysql_real_escape_string($titre);
	$abstract = mysql_real_escape_string($abstract);
	$type = (int)$type;
	$id = (int)$id;
	mysql_query("UPDATE voguer_cat SET titre='".$titre."', abstract='".$abstract."', type='".$type."' WHERE id=".$id);
}
?>
