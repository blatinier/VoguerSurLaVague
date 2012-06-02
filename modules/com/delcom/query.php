<?php
function get_com_byId($id){
	$id = (int)$id;

	return mysql_query("SELECT pseudo,commentaire,DATE_FORMAT(moment,'%d/%m/%Y à %H:%i') AS moment FROM mellismelau_com WHERE id=".$id);
}

function del_com($id){	
	$id = (int)$id;
	mysql_query("DELETE FROM mellismelau_com WHERE id=".$id);
	mysql_query("DELETE FROM voguer_newcom WHERE idcom=".$id);
}
