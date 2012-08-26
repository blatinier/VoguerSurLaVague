<?php
while ($res = mysql_fetch_assoc($req)) {
    $closed_com = $res['closed_com'];
    $captcha_com = $res['captcha_com'];
	?>
	<div class="post">
		<span class="categorie">
			<?php echo $cats[$res['cat']]; ?>
		</span>
		<h2 class="postTitle">
			<a href="art-<?php echo $res['url']; ?>-<?php echo $res['id']; ?>">
				<?php echo stripslashes($res['titre']); ?>
			</a>
		</h2>
		<br/><br/>
		<div class="mainpost">
			<div class="postcontent">
				<?php echo stripslashes($res['texte']); ?>
				<br/><br/>
			</div>
		</div>
		<div class="comconteneur">
			<?php 
			if (empty($_GET['art'])) {
                if(!$closed_com){
                    ?>
                    <a class="comlink" href="art-<?php echo $res['url']; ?>-<?php echo $res['id']; ?>#postcom">
                        Commenter
                    </a>
                     - 
                <?php
                }
                ?>
                    <a href="art-<?php echo $res['url']; ?>-<?php echo $res['id']; ?>">
                        Lire les commentaires (<?php echo ($nbcom[$res['id']])?$nbcom[$res['id']]:0; ?>)
                    </a>
                <?php
            }
			if(!empty($_SESSION['ok']) && $_SESSION['ok'] == 1){
				echo " - <a class=\"comlink\" href=\"index.php?p=mart&amp;art=".$res['id']."\">Editer</a> - 
				<a class=\"comlink\" href=\"index.php?p=dart&amp;art=".$res['id']."\">Supprimer</a><br/>";
			}
			?>
            <span class="postDate">
            <?php
                if($res['ecart'] < 0){
                    $jour = floor(-$res['ecart']/(3600*24));
                    $heure = floor((-$res['ecart']-$jour*3600*24)/3600);
                    $minutes = floor((-$res['ecart']-$jour*3600*24-$heure*3600)/60);
                    echo "<span style=\"color:red;\"> Cet article sera publié dans ".$jour." jours ".$heure." heures et ".$minutes." minutes.</span><br/>";
                }
            ?>
                Écrit le <?php echo $res['post_date']; ?> par <?php echo stripslashes($res['auteur']); ?>
            </span>
			<div id="com<?php echo $res['id']; ?>">
				<?php
				if(!empty($res['id'])){
					include("modules/com/commentaire.php");
				}
				?>
			</div>
			<?php
			if (!$closed_com && !empty($_GET['art'])) {
				include("modules/com/newcom.php");
			}
			?>
		</div>
		<br/>
	</div>
	<br/>
	<hr/>
	<?php
} ?>
<div>
	<?php
	if(empty($_GET['art'])){
        $nombreDePages = get_nb_pages($cat);
		$suiv = ($nombreDePages > 1 && $_GET['page'] != $nombreDePages);
		$prec = ($nombreDePages > 1 && !empty($_GET['page']) && $_GET['page'] > 1);
        $cat = (!empty($_GET['cat'])) ? '&cat='.$_GET['cat'] : '';
        $year = (!empty($_GET['y'])) ? '&y='.$_GET['y'] : '';
        $month = (!empty($_GET['m'])) ? '&m='.$_GET['m'] : '';
		if($prec){
			$lien = "index.php?page=".($_GET['page']-1).$cat.$year.$month;
			echo '<span style="float:left;" class="page_link"><a href="'.$lien.'"><span class="arrow">←</span> NEWER</a></span>';
		}
		if($suiv){
			$lien = "index.php?page=".($_GET['page']+1).$cat.$year.$month;
			echo '<span style="float:right;" class="page_link"><a href="'.$lien.'">OLDER <span class="arrow">→</span></a></span>';
		}
	}
	?>
</div>
