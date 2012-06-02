<p>
	<?php
	echo stripslashes($cats[$_GET['img']]['resume']);
	?>
</p>
<table style="width=100%;" cellspacing="10">
<tr>
<?php
$i=0;
$nbminirow = 3;
foreach($fiche as $key => $value){
	if($value['id'] == "cat"){
		if($i%$nbminirow != 0){
			for($j=($i%$nbminirow);$j<$nbminirow;$j++){
				echo "<td width=\"".floor(100/$nbminirow-1)."%\">&nbsp;</td>";
			}
		}
		?>
		</tr>
		<tr>
			<td colspan="<?php echo $nbminirow;?>">
				<img src="./images/<?php echo stripslashes($value['name']);?>.png" alt="<?php echo stripslashes($cats[$value['name']]['titre']);?>" />
			</td>
		</tr>
		<tr>
		<?php
		$i = 0;
	}
	else{
		?>
		<td style="border:1px solid #b44884;padding-top:4px;width:<?php echo floor(100/$nbminirow)-1;?>%;text-align:center;height:260px;">
			<a href="<?php echo $value['mini']; ?>" title="<?php echo stripslashes($value["titre"]); ?>" rel="shadowbox[Gallerie]">
				<img style="width:90%;height:260px;" src="<?php echo $value['mini']; ?>" alt="image" /><br/>
			</a>
			<div class="comconteneur">
				<?php
				if($_SESSION['ok'] == 1){
					echo " - <a class=\"comlink\" href=\"index.php?p=modgal&amp;img=".$value['id']."\">Editer</a> - 
					<a class=\"comlink\" href=\"index.php?p=delgal&amp;img=".$value['id']."\">Supprimer</a>";
				}
				?>
			</div>
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
