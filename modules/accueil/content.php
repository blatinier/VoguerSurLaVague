<?php
while($res = mysql_fetch_assoc($req)){
    $closed_com = $res['closed_com'];
    $captcha_com = $res['captcha_com'];
	?>
	<div class="post">
		<span class="categorie">
			<?php echo $cats[$res['cat']]; ?>
		</span>
		<h2 class="postTitle">
			<a href="index.php?art=<?php echo $res['id']; ?>">
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
        	if(!$closed_com){
				?>
				<a class="comlink" href="index.php?art=<?php echo $res['id']; ?>&amp;com=1#postcom">
					Commenter
				</a>
				 - 
            <?php
		    }
            ?>
				<a class="comlink" href="index.php?art=<?php echo $res['id']; ?>">
					Lire les commentaires (<?php echo ($nbcom[$res['id']])?$nbcom[$res['id']]:0; ?>)
				</a>
				<?php
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
			if (!empty($_GET['com']) && $_GET['com'] && !$closed_com) {
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
        if(!empty($_GET['cat'])){
            $cat = $_GET['cat'];
        }
        else{
            $cat = "";
        }
        $nombreDePages = get_nb_pages($cat);
		$suiv = ($nombreDePages > 1 && $_GET['page'] != $nombreDePages);
		$prec = ($nombreDePages > 1 && !empty($_GET['page']) && $_GET['page'] > 1);
		if($prec){
			$lien = "index.php?page=".($_GET['page']-1)."&amp;cat=".$cat;
			echo '<span style="float:left;"><a href="'.$lien.'"><img src="/images/billets_recents.png" alt="Billets plus récents" /></a></span>';
		}
		if($suiv){
			$lien = "index.php?page=".($_GET['page']+1)."&amp;cat=".$cat;
			echo '<span style="float:right;"><a href="'.$lien.'"><img src="/images/billets_anciens.png" alt="Billets plus anciens" /></a></span>';
		}
	}
	?>
</div>
