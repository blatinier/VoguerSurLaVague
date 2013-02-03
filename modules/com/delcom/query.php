<?php
function get_com_byId($id){
	$id = (int)$id;

	return mysql_query("SELECT pseudo,commentaire,DATE_FORMAT(moment,'%d/%m/%Y à %H:%i') AS moment FROM comments WHERE id=".$id);
}

function del_com($id){	
	$id = (int)$id;
	mysql_query("DELETE FROM comments WHERE id=".$id);
	mysql_query("DELETE FROM new_comments WHERE idcom=".$id);
}
