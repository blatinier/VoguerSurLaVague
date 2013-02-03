<?php
function getAllCat(){
	$query = "SELECT id, titre, abstract, type FROM category";
	$q = mysql_query($query)or die("Erreur lors de la récupération des catégories: ".mysql_error()."<br/>".$query);
	return $q;
}

function get_list_typecat(){
	return	array("Article","Astuces","Galerie");	
}
?>
