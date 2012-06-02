<?php

$req = get_article_byId($_GET['art']);
$res = mysql_fetch_assoc($req);

if(strtolower($_POST['val'])=='oui'){
	del_articles($_GET['art']);
	echo "L'article a bien ete supprime.";
}
else{
	?>
	<div class="post">
	<form method="post" action="">
		<fieldset>
			<legend>Suppression de <?php echo stripslashes($res['titre']); ?></legend>
	
		<h1 class="title"><a href="index.php?art=<?php echo $res['id']; ?>"><?php echo stripslashes($res['titre']); ?></a></h1>
		<p class="byline"><small>Écrit le <?php echo $res['post_date']; ?> par <?php echo stripslashes($res['auteur']); ?></small></p>
		<div class="entry">
			<p><?php echo nl2br(stripslashes($res['texte'])); ?></p>
		</div>
		<label for="val">Etes-vous sur de vouloir supprimer cet article?<br/>(taper "oui" pour confirmer)</label>
		<input type="text" name="val" id="val" /><br/>
		<input type="submit" value="Envoyer" />
		</fieldset>
	</form>
	</div>
	<?php
}
?>
