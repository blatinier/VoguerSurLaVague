<?php
function post_fiche_tech($auteur,$titre,$texte,$cat,$pub,$mini){
	
	$auteur = mysql_real_escape_string($auteur);
	$titre = mysql_real_escape_string($titre);
	$texte = mysql_real_escape_string($texte);
	$cat = mysql_real_escape_string($cat);
	$mini = mysql_real_escape_string($mini);

	mysql_query("INSERT INTO voguer_fichestech(id,auteur,titre,texte,pubdate,cat,miniature) 
				VALUES('','".$auteur."','".$titre."','".$texte."','".$pub."','".$cat."','".$mini."')");
	return mysql_insert_id();
}
?>
