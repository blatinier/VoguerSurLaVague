<?php 
function post_fiche_tech($auteur,$titre,$texte,$cat,$pub,$mini){ 
     
    $auteur = mysql_real_escape_string($auteur); 
    $titre = mysql_real_escape_string($titre); 
    $texte = mysql_real_escape_string($texte); 
    $cat = mysql_real_escape_string($cat); 
    $mini = mysql_real_escape_string($mini); 
 
    mysql_query("INSERT INTO voguer_fichestech(id,auteur,titre, texte,pubdate,cat,miniature)  
                VALUES('','".$auteur."','".$titre."', '".$texte."','".$pub."','".$cat."','".$mini."')"); 
    return mysql_insert_id(); 
} 

function poster_articles($auteur, $titre, $texte, $cat, $pub, $is_diy){ 
    $auteur = mysql_real_escape_string($auteur); 
    $titre = mysql_real_escape_string($titre); 
    $texte = mysql_real_escape_string($texte); 
    $cat = mysql_real_escape_string($cat); 
    $is_diy = ($is_diy == 'on') ? 1 : 0;
 
    mysql_query("INSERT INTO articles(id, auteur, titre, url, texte, pubdate, cat, is_diy)
                VALUES('','".$auteur."','".$titre."', '".sanitize_string($titre)."','".$texte."','".$pub."','".$cat."', ".$is_diy.")"); 
    return mysql_insert_id(); 
} 

function get_list_cat_img(){
	$q = "SELECT id,titre FROM category WHERE type=0";
	$req = mysql_query($q)or die(mysql_error());
	$array = array();
	while($res = mysql_fetch_assoc($req)){
		$array[$res['id']] = $res['titre'];
	}
	return $array;
}

?>
