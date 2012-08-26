<?php
if(!empty($_POST['auteur']) && !empty($_POST['titre']) && !empty($_POST['cat']) && !empty($_POST['texte'])){
	mod_articles($_POST['auteur'],$_POST['titre'],$_POST['texte'],$_POST['cat'],$_GET['art'],$_POST['pub']);
	echo "<p>Ok c'est édité.</p>";
} elseif (!empty($_POST)) {
	echo "<p>Tous les champs doivent être remplis.</p>";
}

$req = get_article_byId($_GET['art']);
$res = mysql_fetch_assoc($req);
$res['titre'] = str_replace("\'","'",$res['titre']);
$res['titre'] = str_replace('\"',"",$res['titre']);
?>
<p><a href="index.php?<?php echo $ws; ?>art=<?php echo $_GET['art']; ?>">Lien vers l'article</a></p>
<div class="comconteneur">
<form method="post" action="">
	<input class="input_com" type="text" name="auteur" id="auteur" value="<?php echo stripslashes($res['auteur']); ?>"/>
    <label for="auteur">Auteur</label><br />
	<input class="input_com" type="text" name="titre" id="titre" value="<?php echo ($res['titre']); ?>" />
	<label for="titre">Titre</label><br />
	<select class="input_com" name="cat">
	<?php
	$list_cat_q = "SELECT titre,id FROM voguer_cat WHERE type=0";
	$list_cat_q = mysql_query($list_cat_q)or die(mysql_error());
	while($r = mysql_fetch_assoc($list_cat_q)){
		if($r['id']==$res['cat'])
			echo '<option value="'.$r['id'].'" selected="selected">'.$r['titre'].'</option>';
		else
			echo '<option value="'.$r['id'].'">'.$r['titre'].'</option>';
	}
	?>
	</select>
    <label for="cat">Catégorie</label><br />
	<textarea class="new_art" name="texte" id="commentaire"><?php echo stripslashes($res['texte']); ?></textarea><br /><br />
    <input class="input_com" type="text" name="pub" id="pub" value="<?php echo $res['pubdate']; ?>" />
	<label for="pub">Date de publication de l'article(AAAA-MM-JJ HH-MM-SS) : </label><br />
    <input type="submit" value="Envoyer" />
</form>
	Besoin d'aide pour de la mise en page? <strong><a href="modules/helphtml/html.php">C'est par ici.</a></strong> (Attention si tu ne veux pas perdre ce que tu es en train d'écrire, ouvre le lien dans un nouvel onglet.)
</div>
