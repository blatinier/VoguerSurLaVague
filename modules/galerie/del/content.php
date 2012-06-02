<?php
if($del){
	echo "La fiche technique à bien été supprimée.";
}
else{
	?>
	<form method="post" action="">
		<fieldset>
			<legend>Suppression de <?php echo stripslashes($res['titre']); ?></legend>
	
		<img style="width:100%;" src="<?php echo $res['image']; ?>" alt="image" /><br/>
		<?php echo stripslashes($res['titre']); ?>(<?php echo $res['image']; ?>)<br/>
		<label for="val">Etes-vous sur de vouloir supprimer cette image?<br/>(taper "oui" pour confirmer)</label>
		<input type="text" name="val" id="val" /><br/>
		<input type="submit" value="Envoyer" />
		</fieldset>
	</form>
	<?php
}
?>
