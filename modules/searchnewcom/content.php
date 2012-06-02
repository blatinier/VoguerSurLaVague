<?php echo $nbnlucom; ?> commentaires non lus : <br/>

<ul>
	<?php 
	for($i=0;$i<$nbnlucom;$i++){
		echo '
			<li>
				Commentaire de '.$pseudos[$i].' 
				dans l\'article 
				<a href="index.php?p=nlu&amp;'.$rliens[$i].'">
					'.$titres[$i].'
				</a>
			</li>';
	}
	?>
</ul>
<a href="index.php?p=nlu&amp;all=1">Tout marquer comme lu</a>
