<?php
if($del){
	echo "La catégorie à bien été supprimée.";
}
else{
	$cats = get_list_typecat();
	?>
	<div class="post">
	<form method="post" action="">
		<fieldset>
			<legend>Suppression de <?php echo stripslashes($res['titre']); ?> de type <?php echo $cats[$res['type']]; ?></legend>

		<p style="line-height:1em;"><?php echo nl2br(stripslashes($res['abstract'])); ?><br/><br/>
		<label for="val">Etes-vous sur de vouloir supprimer cette fiche technique?<br/>
		(taper "oui" pour confirmer)</label><br/>
		<input type="text" name="val" id="val" /><br/></p>

		<input type="submit" value="Envoyer" />
		</fieldset>
	</form>
	</div>
	<?php
}
?>
