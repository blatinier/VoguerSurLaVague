<?php
if($_SESSION['ok'] == 1){
	
	if($poste){
		echo "<p>Ok c'est posté.</p>";
	}
	else{
		echo "<p>Tous les champs doivent être remplis.</p>";
	}
	?>
	<p>
	<form method="post" action="">
		<fieldset>
		<legend>Ajout d'une catégorie</legend>
		<table>
			<tr>
				<td><label for="titre">Titre</label></td>
				<td><input type="text" name="titre" id="titre"/></td>
			</tr>
			<tr>
				<td><label for="type">Type</label></td>
				<td>
					<select name="type" id="type">
					<?php
					$listcat = get_list_typecat();
					foreach($listcat as $k => $l){
						echo '<option value="'.$k.'">'.$l.'</option>';
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><label for="texte">Le résumé</label></td>
			</tr>
			<tr>
				<td colspan="2"><textarea name="texte" id="texte" rows="10" cols="85"></textarea></td>
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
