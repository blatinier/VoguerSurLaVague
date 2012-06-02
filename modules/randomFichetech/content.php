<?php
if(empty($rand_fiche)){
	?>
	Aucune fiche technique n'a été publiée pour le moment.
	<?php
}
else{
	?>
	<div style="border:1px solid #b44884;padding-top:4px;width:<?php echo floor(100/$nbminirow)-1;?>%;text-align:center;height:260px;">
		<a href="index.php?p=viewft&amp;ft=<?php echo $rand_fiche['id']; ?>">
			<img style="width:90%;" src="<?php echo $rand_fiche['mini']; ?>" alt="fiche technique" /><br/>
			<div style="margin:3px;height:30px;"><strong><?php echo stripslashes($rand_fiche["titre"]); ?></strong></div>
		</a>
	</div>
	<?php
}
