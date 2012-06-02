<?php
function getAllCat(){
	$query = "SELECT id, titre, abstract, type FROM voguer_cat";
	$q = mysql_query($query)or die("Erreur lors de la récupération des catégories: ".mysql_error()."<br/>".$query);
	return $q;
}

function get_list_typecat(){
	return	array("Article","Astuces","Galerie");	
}
?>
