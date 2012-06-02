<?php
function del_cat($id){
	$id = (int)$id;
	mysql_query("DELETE FROM voguer_cat WHERE id=".$id);
}

function get_cat_byId($id){
	$id = (int)$id;

	return mysql_query("SELECT 
							titre,
							type,
							abstract
							FROM voguer_cat WHERE id=".$id);
}
function get_list_typecat(){
	return	array("Article","Astuces","Galerie");	
}

?>
