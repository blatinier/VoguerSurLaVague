<?php
function del_galerie($id){

	$id = (int)$id;

	mysql_query("DELETE FROM voguer_galerie WHERE id=".$id);

}

function get_galerie_byId($id){
	$id = (int)$id;

	return mysql_query("SELECT 
							id,
							titre,
							image,
							cat
							FROM voguer_galerie WHERE id=".$id);
}

?>
