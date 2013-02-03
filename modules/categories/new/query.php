<?php
function get_list_typecat(){
	return	array("Article","Astuces","Galerie");	
}

function new_cat($titre, $abstract, $type){
	$titre = mysql_real_escape_string($titre);
	$abstract = mysql_real_escape_string($abstract);
	$type = (int)$type;
	mysql_query("INSERT INTO category(id,titre,abstract,type) 
					VALUES('','".$titre."','".$abstract."','".$type."')");
	return mysql_insert_id();
}
?>
