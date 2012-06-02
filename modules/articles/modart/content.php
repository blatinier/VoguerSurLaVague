<?php

if(!empty($_POST['auteur']) && !empty($_POST['titre']) && !empty($_POST['cat']) && !empty($_POST['texte'])){
	mod_articles($_POST['auteur'],$_POST['titre'],$_POST['texte'],$_POST['cat'],$_GET['art'],$_POST['pub']);
	echo "<p>Ok c'est édité.</p>";
}
else{
	echo "<p>Tous les champs doivent être remplis.</p>";
}

$req = get_article_byId($_GET['art']);
$res = mysql_fetch_assoc($req);

$res['titre'] = str_replace("\'","'",$res['titre']);
$res['titre'] = str_replace('\"',"",$res['titre']);

?>
<p><a href="index.php?<?php echo $ws; ?>art=<?php echo $_GET['art']; ?>">Lien vers l'article</a></p>
<form method="post" action="">
	<fieldset>
	<legend>Ajout un article</legend>
	<table>
		<tr>
			<td><label for="auteur">Auteur</label></td>
			<td><input type="text" name="auteur" id="auteur" value="<?php echo stripslashes($res['auteur']); ?>"/></td>
		</tr>
		<tr>
			<td><label for="titre">Titre</label></td>
			<td><input type="text" name="titre" id="titre" value="<?php echo ($res['titre']); ?>" /></td>
		</tr>
		<tr>
			<td><label for="cat">Catégorie</label></td>
			<td>
				<select name="cat">
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
			</td>
		</tr>
		<tr>
			<td colspan="2"><label for="texte">Le paragraphe</label></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="texte" id="texte" rows="30" cols="85"><?php echo stripslashes($res['texte']); ?></textarea></td>
		</tr>
		<tr>
			<td><label for="pub">Date de publication de l'article(AAAA-MM-JJ HH-MM-SS) : </label></td>
				<td><input type="text" name="pub" id="pub" value="<?php echo $res['pubdate']; ?>" /></td>
		<td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Envoyer" /></td>
		</tr>
	</table>
	</fieldset>
</form>
Besoin d'aide pour de la mise en page? <strong><a href="modules/helphtml/html.php">C'est par ici.</a></strong> (Attention si tu veux pas prendre ce que tu es en train d'ecrire, ouvre le lien dans un nouvel onglet.)
