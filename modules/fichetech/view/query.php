<?php
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

function get_fiches_tech(){
	if(!empty($_SESSION['ok']) && $_SESSION['ok']==1){
		$whereclause = "1";
	}
	else{
		$whereclause = "pubdate < NOW()";
	}

	$query = "
			SELECT 
				id,
				titre
			FROM voguer_fichestech WHERE ".$whereclause." 
			ORDER BY cat,pubdate";
	$r = mysql_query($query)or die(mysql_error()."<br/> requete : ".$query);
	return $r;
	
}

function get_list_cat_ft(){
	$q = "SELECT id,titre FROM voguer_cat WHERE type=1";
	$req = mysql_query($q)or die(mysql_error());
	$array = array();
	while($res = mysql_fetch_assoc($req)){
		$array[$res['id']] = $res['titre'];
	}
	return $array;
}

?>
