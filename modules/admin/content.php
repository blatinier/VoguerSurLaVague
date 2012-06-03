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
elseif($_POST['pass'] == "Melmelbo0!"){
	$_SESSION['ok'] = 1;
}
elseif($_SESSION['ok'] != 1){
	echo "Hé non spas ça, essaye encore.";
}

if($_SESSION['ok']==1){
	?>
	<ul>
		<li><a href="modules/allmystats">Statistiques visiteurs</a></li>
		<li><a href="index.php?p=deconnexion">Deconnexion</a></li>
		<li><a href="index.php?p=nlu">Derniers commentaires</a></li>
		<li><a href="index.php?p=nart">Nouveau message</a></li>
		<li><a href="index.php?p=newft">Nouvelle fiche technique</a></li>
		<li><a href="index.php?p=newgal">Nouvelle image</a></li>
		<li><a href="index.php?p=newcat">Nouvelle catégorie</a></li>
		<li><a href="index.php?p=listcat">Modifier/Supprimer une catégorie</a></li>
	</ul>
	<?php
}

?>
