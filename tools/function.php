<?php
require_once('sql.php');

function get_list_cat(){
	$cats = array(
				7=>"Gloubiboulga",
				5=>"Tambouille",
				4=>"Ethique et libre",
				3=>"Fringues et fripes",
				2=>"Astuce belle bouille",
				1=>"Idées brico déco",
				6=>"Sweet home écolo"
			);
	return $cats;
}

function get_nb_pages($cat){

	$whereclause = " WHERE ";

	if(!empty($cat)){
		$cat = mysql_real_escape_string($cat);
		$whereclause = " WHERE cat='".$cat."' AND ";
	}

	if(!empty($_SESSION['ok']) && $_SESSION['ok']==1){
		$whereclause .= "1";
	}
	else{
		$whereclause .= "pubdate < NOW()";
	}
	$req = mysql_query("SELECT COUNT(*) FROM mellismelau_articles ".$whereclause);
	return ceil(mysql_result($req,0,0)/5);
}

function get_articles($orderbyc="pubdate",$cat="",$orderbyt="ASC",$page=""){
	if(!empty($page)){
		$page = (int)$page;
		$limit = " LIMIT ".(($page-1)*4).",4";
	}
	
	$orderbyt = mysql_real_escape_string($orderbyt);
	$orderbyc = mysql_real_escape_string($orderbyc);
    $where = array(1);
	if(!empty($cat)){
		$cat = mysql_real_escape_string($cat);
		$where[] = " cat=".(int)$cat;
	}

	if(empty($_SESSION['ok']) || $_SESSION['ok']!=1){
		$where[] = "pubdate < NOW()";
	}

    if (!empty($_GET['y'])) {
        $where[] = "YEAR(pubdate) = ".(int)$_GET['y'];
    }

    if (!empty($_GET['m'])) {
        $where[] = "MONTH(pubdate) = ".(int)$_GET['m'];
    }
    
	$query = "
			SELECT 
				id,
				titre,
				url,
				auteur,
				texte,
				pubdate,
				DATE_FORMAT(pubdate,'%d/%m/%Y') AS post_date,
				cat, 
				(UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(pubdate)) AS ecart,
                closed_com,
                captcha_com
			FROM mellismelau_articles
            WHERE ".implode(" AND ", $where)."
			ORDER BY ".$orderbyc." ".$orderbyt.$limit;
	return mysql_query($query);
}

function nbcomments($ida=NULL){
    $cond = "";
	if (!empty($ida)) {
		$cond = "WHERE idarticle=".(int)$ida;
    }
	$query = "SELECT idarticle,COUNT(idarticle) AS nbcom FROM `mellismelau_com` ".$cond." GROUP BY idarticle";
	$req=mysql_query($query)or die(mysql_error());
	while($res = mysql_fetch_assoc($req)){
		$nbcoms[$res['idarticle']]=$res['nbcom'];
	}

	return (!empty($nbcoms)) ? $nbcoms : 0;
}

function post_articles($auteur,$titre,$texte,$cat,$pub){
	$auteur = mysql_real_escape_string($auteur);
	$titre = mysql_real_escape_string($titre);
	$texte = mysql_real_escape_string($texte);
	$cat = mysql_real_escape_string($cat);
	mysql_query("INSERT INTO mellismelau_articles(id,auteur,titre, url,texte,pubdate,cat) 
				VALUES('','".$auteur."','".$titre."', '".sanitize_string($titre)."', '".$texte."','".$pub."','".$cat."')");
	return mysql_insert_id();
}

function mod_articles($auteur, $titre, $texte, $cat, $id, $pub, $closed_com=false, $captcha_com=false){
	$id = (int)$id;
	$closed_com = (int)$closed_com;
	$captcha_com = (int)$captcha_com;
	$auteur = mysql_real_escape_string($auteur);
	$titre = mysql_real_escape_string($titre);
	$texte = mysql_real_escape_string($texte);
	$cat = mysql_real_escape_string($cat);
	return mysql_query("UPDATE mellismelau_articles 
		SET auteur='".$auteur."', 
			titre='".$titre."', 
			url='".sanitize_string($titre)."',
			pubdate='".$pub."', 
			texte='".$texte."',
			cat='".$cat."',
			closed_com='".$closed_com."' ,
			captcha_com='".$captcha_com."' 
		WHERE id=".$id)or die(mysql_error());
}

function del_articles($id){
	$id = (int)$id;
	mysql_query("DELETE FROM mellismelau_articles WHERE id=".$id);
	mysql_query("DELETE FROM mellismelau_com WHERE idarticle=".$id);
}

function get_title($id) {
    $req = get_article_byId($id);
    $res = mysql_fetch_assoc($req);
	return $res['titre'];
}

function get_article_byId($id){
	$id = (int)$id;
	$cond = " AND pubdate < NOW() ";
	if(!empty($_SESSION['ok']) && $_SESSION['ok']==1){
		$cond = "";
	}

	return mysql_query("SELECT 
							id,
							auteur,
							titre,
							url,
							texte,
							pubdate,
							DATE_FORMAT(pubdate,'%d/%m/%Y') AS post_date,
							cat,
							(UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(pubdate)) AS ecart,
                            closed_com,
                            captcha_com
							FROM mellismelau_articles WHERE id=".$id.$cond);
}

function sanitize_string($s, $glue='-') {
    // Lower case
    $s = strtolower($s);
    // Replaces accentuated chars by their non-accentuated version
    $s = iconv('UTF-8', 'US-ASCII//TRANSLIT', $s);
    // Replaces other chars by "-"
    $s = preg_replace('#([^a-z0-9'.$glue.'])#', $glue, $s);
    // Remove consecutives "-"
    $s = preg_replace('#(['.$glue.']+)#', $glue, $s);
    // Trim glue
    $s = trim($s, $glue);
    return $s;
}
?>
