<?php
if(empty($_POST['pass'])&&$_SESSION['ok'] != 1){
	?>
	<form method="post" action="">
		<fieldset>
			<legend>Accès protégé par mot de passe</legend>
			<label for="pass">Entrez le mot de passe : </label>
			<input type="password" name="pass" id="pass" /><br/>
			<input type="submit" value="Envoyer" />
		</fieldset>
	</form>
	<?php
}
elseif($_POST['pass'] == "PipoPouet"){
	$_SESSION['ok'] = 1;
}
elseif($_SESSION['ok'] != 1){
	echo "Hé non spas ça, essaye encore.";
}

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
		<legend>Ajout un article</legend>
		<table>
			<tr>
				<td><label for="auteur">Auteur</label></td>
				<td><input type="text" name="auteur" id="auteur" value="<?php echo $_SESSION['nart_auteur'];?>" /></td>
			</tr>
			<tr>
				<td><label for="titre">Titre</label></td>
				<td><input type="text" name="titre" id="titre" value="<?php echo $_SESSION['nart_titre'];?>" /></td>
			</tr>
			<tr>
				<td><label for="cat">Catégorie</label></td>
				<td>
					<select name="cat" id="cat">
					<?php
					$listcat = get_list_cat_img();
					foreach($listcat as $k => $l){
						if($k == $_SESSION['nart_cat']){
							$selected = "selected";
						}
						echo '<option value="'.$k.'" '.$selected.'>'.$l.'</option>';
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><label for="texte">Le paragraphe</label></td>
			</tr>
			<tr>
				<td colspan="2"><textarea name="texte" id="texte" rows="30" cols="85"><?php echo $_SESSION['nart_art'];?></textarea></td>
			</tr>
			<tr>
                <td><label for="mini">Adresse de la miniature
					<br/>(indiquer une adresse ici sauvegardera l'article en double : une fois dans les articles et une fois dans les astuces) : </label></td>
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
