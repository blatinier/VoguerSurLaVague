<?php
if($deleted){
	echo "Le commentaire a bien ete supprimé.";
}
else{
	?>
	<div class="post">
	<form method="post" action="">
		<fieldset>
			<legend>Suppression du commentaire de  <?php echo stripslashes($res['pseudo']); ?></legend>
		<p class="byline"><small>Écrit le <?php echo $res['moment']; ?></small></p>
		<div class="entry">
			<p><?php echo nl2br(stripslashes($res['commentaire'])); ?></p>
		</div>
		<label for="val">Etes-vous sur de vouloir supprimer ce commentaire?<br/>(taper "oui" pour confirmer)</label>
		<input type="text" name="val" id="val" /><br/>
		<input type="submit" value="Envoyer" />
		</fieldset>
	</form>
	</div>
	<?php
}
?>
