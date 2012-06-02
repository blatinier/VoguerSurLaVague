<?php
function get_fiches_tech($cat=""){
	if(!empty($_SESSION['ok']) && $_SESSION['ok'] == 1){
		$whereclause = "1";
	}
	else{
		$whereclause = "pubdate < NOW()";
	}

	if($cat){
		$whereclause .= " AND cat='".mysql_real_escape_string($cat)."' ";
	}

	$query = "
			SELECT 
				ft.id,
				ft.titre AS ft_title,
				ft.miniature,
				c.titre AS cat_title, 
                c.id AS cat_id,
				(UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(pubdate)) AS ecart
			FROM voguer_fichestech ft
            LEFT JOIN voguer_cat c
                ON ft.cat = c.id
            WHERE ".$whereclause." 
			ORDER BY cat,pubdate";
	$r = mysql_query($query)or die(mysql_error()."<br/> requete : ".$query);
	return $r;
	
}
