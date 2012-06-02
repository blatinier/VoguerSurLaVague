<?php
if($_SESSION['ok'] == 1){
	
	if(!empty($_POST['titre']) && !empty($_POST['cat'])){
		echo "<p>Ok c'est posté.</p>";
	}
	else{
		echo "<p>Tous les champs doivent être remplis.</p>";
	}

	?>
	<p>
	<form method="post" action="">
		<fieldset>
		<legend>Ajout une image de diaporama</legend>
		<table>
			<tr>
				<td><label for="titre">Titre</label></td>
				<td><input type="text" name="titre" id="titre" /></td>
			</tr>
			<tr>
				<td><label for="cat">Catégorie</label></td>
				<td>
					<select name="cat">
					<?php
					$listcat = get_list_cat_img();
					foreach($listcat as $k => $l){
						echo '<option value="'.$k.'">'.$l.'</option>';
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="cat">Nouvelle catégorie (si rempli alors j'ignore la catégorie sélectionnée au dessus)</label></td>
				<td><input type="text" name="cat2" id="cat2" /></td>
			</tr>
			<tr>
				<td><label for="mini">Adresse de l'image : </label></td>
				<td><input type="text" name="mini" id="mini" /></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="Envoyer" /><td>
			</tr>
		</table>
		</fieldset>
	</form>
	<?php
}
?>
