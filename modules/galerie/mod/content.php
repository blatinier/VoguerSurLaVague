<?php
if($ed){
	echo "<p>Ok c'est édité.</p>";
}
if($err){
	echo "<p>Tous les champs doivent être remplis.</p>";
}
?>
<p><a href="index.php?p=viewgal&amp;img=<?php echo $galcat; ?>">Lien vers l'image.</a></p>
<form method="post" action="">
	<fieldset>
	<legend>Modification d'une image</legend>
	<table>
		<tr>
			<td><label for="titre">Titre</label></td>
			<td><input type="text" name="titre" id="titre" value="<?php echo ($res['titre']); ?>" /></td>
		</tr>
		<tr>
			<td><label for="cat">Catégorie</label></td>
			<td>
				<select name="cat">
					<?php
					$listcat = get_list_cat_img();
					foreach($listcat as $k => $l){
						if($l==$res['cat'])
							echo '<option value="'.$k.'" selected="selected">'.$l.'</option>';
						else
							echo '<option value="'.$k.'">'.$l.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="mini">Adresse l'image : </label></td>
			<td><input type="text" name="mini" id="mini" value="<?php echo $res['image']; ?>" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Envoyer" /></td>
		</tr>
	</table>
	</fieldset>
</form>
