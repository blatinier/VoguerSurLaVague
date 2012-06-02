<?php
if($_SESSION['ok'] == 1){
	
	if(!empty($_POST['auteur']) && !empty($_POST['titre']) && !empty($_POST['cat']) && !empty($_POST['texte'])){
		echo "<p>Ok c'est posté.</p>";
	}
	else{
		echo "<p>Tous les champs doivent être remplis.</p>";
	}

	?>
	<p>
	<form method="post" action="">
		<fieldset>
		<legend>Ajout une fiche technique</legend>
		<table>
			<tr>
				<td><label for="auteur">Auteur</label></td>
				<td><input type="text" name="auteur" id="auteur" /></td>
			</tr>
			<tr>
				<td><label for="titre">Titre</label></td>
				<td><input type="text" name="titre" id="titre" /></td>
			</tr>
			<tr>
				<td><label for="cat">Catégorie</label></td>
				<td>
					<select name="cat">
					<?php
					$list_cat_q = "SELECT titre,id FROM voguer_cat WHERE type=1";
					$list_cat_q = mysql_query($list_cat_q)or die(mysql_error());
					while($r = mysql_fetch_assoc($list_cat_q)){
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
				<td colspan="2"><textarea name="texte" id="texte" rows="30" cols="85"></textarea></td>
			</tr>
			<tr>
				<td><label for="mini">Adresse de la miniature : </label></td>
				<td><input type="text" name="mini" id="mini" /></td>
			</tr>
			<tr>
				<td><label for="pub">Date de publication de l'article(AAAA-MM-JJ HH-MM-SS) : </label></td>
				<td><input type="text" name="pub" id="pub" value="<?php echo $maintenant; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="Envoyer" /><td>
			</tr>
		</table>
		</fieldset>
	</form>
	Besoin d'aide pour de la mise en page? <strong><a href="modules/helphtml/html.php">C'est par ici.</a></strong> (Attention si tu veux pas prendre ce que tu es en train d'ecrire, ouvre le lien dans un nouvel onglet.)</p>
	<?php
}
?>
