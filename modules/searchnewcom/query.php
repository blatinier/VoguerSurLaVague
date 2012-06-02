<?php
function getNewComments(){
	$req = mysql_query("SELECT nc.id, nc.idcom, nc.idarticle, c.pseudo, a.titre
					FROM voguer_newcom nc
					LEFT JOIN mellismelau_com c
						ON c.id=nc.idcom
					LEFT JOIN mellismelau_articles a
						ON a.id = nc.idarticle") 
			or die("Récupération des commentaires non lus echoué : fichier:".__FILE__." ligne:".__LINE__."<br.>".mysql_error());
	return $req;
}

function markComment($idcom){
	return mysql_query("DELETE FROM voguer_newcom WHERE id=".(int)$idcom);
}

function markAllComments(){
	return mysql_query("TRUNCATE TABLE voguer_newcom");
}
?>
