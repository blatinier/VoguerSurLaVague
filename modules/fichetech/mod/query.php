<?php
function mod_fichetech($auteur,$titre,$texte,$cat,$id,$pub,$mini){

	$id = (int)$id;
	$auteur = mysql_real_escape_string($auteur);
	$titre = mysql_real_escape_string($titre);
	$texte = mysql_real_escape_string($texte);
	$cat = mysql_real_escape_string($cat);
	$mini = mysql_real_escape_string($mini);
	$req = "UPDATE voguer_fichestech 
		SET auteur='".$auteur."', 
			titre='".$titre."', 
			pubdate='".$pub."', 
			texte='".$texte."',
			cat='".$cat."',
			miniature='".$mini."' 
		WHERE id=".$id;
	mysql_query($req)or die(mysql_error());
	
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
