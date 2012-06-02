<h2>Liste des catégories</h2>
<table>
	<tr>
		<th width="20%">Titre</th>
		<th width="20%">Résumé</th>
		<th width="20%">Type</th>
		<th width="20%">Modifier</th>
		<th width="20%">Supprimer</th>
	</tr>
	<?php
	$cats = get_list_typecat();
	foreach($id as $key => $v){
		?>
		<tr>
			<td><?php echo $titre[$key]; ?></td>
			<td><?php echo substr(stripslashes($abstract[$key]),0,20)."..."; ?></td>
			<td><?php echo $cats[$type[$key]]; ?></td>
			<td><a href="index.php?p=modcat&amp;id=<?php echo $v; ?>">Modifier</a></td>
			<td><a href="index.php?p=delcat&amp;id=<?php echo $v; ?>">Supprimer</a></td>
		</tr>
		<?php
	}
	?>
</table>

