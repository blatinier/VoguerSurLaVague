<?php
function del_fichetech($id){

	$id = (int)$id;

	mysql_query("DELETE FROM voguer_fichestech WHERE id=".$id);
	mysql_query("DELETE FROM voguer_fichestech_com WHERE idfiche=".$id);

}

function get_fichetech_byId($id){
	$id = (int)$id;
	$cond = " AND pubdate < NOW() ";
	if($_SESSION['ok']==1){
		$cond = "";
	}

	return mysql_query("SELECT 
							id,
							auteur,
							titre,
							texte,
							pubdate,
							miniature,
							DATE_FORMAT(pubdate,'%d/%m/%Y à %H:%i') AS post_date,
							cat,
							(UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(pubdate)) AS ecart
							FROM voguer_fichestech WHERE id=".$id.$cond);
}

?>
