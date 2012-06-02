<?php
function get_rand_fiche($cat=""){
	if($_SESSION['ok']==1){
		$whereclause .= "1";
	}
	else{
		$whereclause .= "pubdate < NOW()";
	}

	if($cat){
		$whereclause .= " AND cat='".mysql_real_escape_string($cat)."' ";
	}

	$query = "
			SELECT 
				id,
				titre,
				miniature,
				cat, 
				(UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(pubdate)) AS ecart
			FROM voguer_fichestech WHERE ".$whereclause." 
			ORDER BY RAND()
			LIMIT 0,1";
	$r = mysql_query($query)or die(mysql_error()."<br/> requete : ".$query);
	return $r;
	
}
