<script type="text/javascript">
$(function(){
    $(".ftBox").hover(function(){//in
            self = $(this);
            self.find(".ftTitle").css('display', 'block');
            self.find(".fader").fadeIn(300, function(){});
        },
        function(){//out
            self = $(this);
            self.find(".ftTitle").css('display', 'none');
            self.find(".fader").fadeOut(300, function(){});
        });
    });
</script>
<h2 id="diy">Do It Yourself !</h2>
<table style="width=100%;" cellspacing="10">
<tr>
<?php
$i=0;
$nbminirow = 3;
if(empty($fiche)){
	?>
	<td>Aucune fiche technique n'a été publiée pour le moment.</td>
	<?php
}
else{
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
                    <span class="ftCatTitle">&nbsp;<?php echo $value['name'];?>&nbsp;</span>
                </td>
            </tr>
			<tr>
			<?php
			$i = 0;
		}
		else{
			?>
			<td class="ftBox">
                &nbsp;
				<a href="index.php?p=viewft&amp;ft=<?php echo $value['id']; ?>">
					<img src="<?php echo $value['mini']; ?>" alt="fiche technique" />
                    <div class="fader"></div>
					<span class="ftTitle"><strong><?php echo stripslashes($value['titre']); ?></strong></div>
				</a>
			</td>
			<?php
			$i++;
			if($i%$nbminirow == 0){
				echo "</tr><tr>";
			}
		}
	}
}
for($j=1;$j<=$nbminirow;$j++){
	echo "<td width=\"".floor(100/$nbminirow-1)."%\">&nbsp;</td>";
}
?>
</tr>
</table>
