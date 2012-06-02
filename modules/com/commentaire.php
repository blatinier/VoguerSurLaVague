<?php
require_once(dirname(__FILE__).'/../../tools/sql.php');

if (!empty($_GET['art'])) {
    $ida = (int)$_GET['art'];
} else {
    $ida = 0;
}

if ($ida > 0) {
	$req = mysql_query("SELECT id, pseudo, DATE_FORMAT(moment,'à %H:%i \l\e %d/%c/%y') AS heure, commentaire, site, ip
							FROM mellismelau_com WHERE idarticle=".$ida." ORDER BY moment");
	while($res = mysql_fetch_assoc($req)){
		if($res['pseudo'] == "Melmelboo")
			$res['pseudo'] = '<a style="color:#0198b3;" href="http://melmelboo.free.fr">'.$res['pseudo'].'</a>';
		elseif(!empty($res['site']))
			$res['pseudo'] = '<a style="text-decoration:underline;color:black;" href="'.$res['site'].'">'.$res['pseudo'].'</a>';
		echo '	<div class="entry">
					<p><strong>'.$res['pseudo'].'</strong>';
		if(!empty($_SESSION['ok']) && $_SESSION['ok']==1){
			echo ' ('.$res['ip'].') <a href="index.php?p=dcom&amp;c='.$res['id'].'">Supprimer ce commentaire</a>';
		}
		echo			'<br/>
					 <small>'.$res['heure'].'</small><br/>
					'.nl2br(stripslashes(htmlspecialchars($res['commentaire']))).'</p>
					<hr/>
				</div>';
	}
}
?>
