<table style="width=100%;" cellspacing="10">
<tr>
<?php
$i=0;
$nbminirow = 3;
if(empty($fiche)){
	?>
	<td>Aucune galerie n'a été publiée pour le moment.</td>
	<?php
}
else{
	foreach($fiche as $key => $value){
		?>
		<td style="border:1px solid #b44884;padding-top:4px;width:<?php echo floor(100/$nbminirow)-1;?>%;text-align:center;height:260px;">
			<a href="index.php?p=viewgal&amp;img=<?php echo $value['id']; ?>">
				<img style="height:260px;" src="<?php echo $value['mini']; ?>" alt="galerie" /><br/>
				<div style="margin:3px;height:30px;"><strong><?php echo stripslashes($cats[$value["id"]]); ?></strong></div>
			</a>
		</td>
		<?php
		$i++;
		if($i%$nbminirow == 0){
			echo "</tr><tr>";
		}
	}
}
for($j=1;$j<=$nbminirow;$j++){
	echo "<td width=\"".floor(100/$nbminirow-1)."%\">&nbsp;</td>";
}

?>
</tr>
</table>
