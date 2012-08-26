<?php
if($deleted){
	echo "Le commentaire a bien ete supprimé.";
}
else{
	?>
<div class="comconteneur">
	<form method="post" action="">
        <h3 id="leavecom"><a href="#">Suppression du commentaire de  <?php echo stripslashes($res['pseudo']); ?></a></h3>
		<p class="byline"><small>Écrit le <?php echo $res['moment']; ?></small></p>
		<div class="entry">
			<p><?php echo nl2br(stripslashes($res['commentaire'])); ?></p>
		</div>
		<input class="input_com" type="text" name="val" id="val" />
		<label for="val">Êtes-vous sûr de vouloir supprimer ce commentaire ?<br/>(tapez « oui » pour confirmer)</label><br/>
		<input type="submit" value="Envoyer" />
	</form>
</div>
	<?php
}
?>
