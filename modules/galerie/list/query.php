<?php
function get_gal(){
	$query = "
			SELECT 
				id,
				titre,
				image,
				cat 
			FROM voguer_galerie 
			GROUP BY cat";
	$r = mysql_query($query)or die(mysql_error()."<br/> requete : ".$query);
	return $r;
	
}

