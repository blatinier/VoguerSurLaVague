<?php
if(!empty($res)){
    ?>
    <div class="post">
        <?php
        if($old){
            ?>
            <a href="index.php?p=viewft&amp;ft=<?php echo $old; ?>"><span class="ftCatTitle" style="float:left;">&nbsp;DIY pr&eacute;c&eacute;dant&nbsp;</span></a>
            <?php
        }
        if($next){
            ?>
            <a href="index.php?p=viewft&amp;ft=<?php echo $next; ?>"><span class="ftCatTitle" style="float:right;">&nbsp;DIY suivant&nbsp;</span></a>
            <?php
        }
        ?>
        <br/><br/><br/><br/><br/>
        <span class="categorie">
            <?php echo $cats[$res['cat']]; ?>
        </span>
        <h2 class="postTitle">
            <a href="#">
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
            if($_SESSION['ok'] == 1){
                echo " - <a class=\"comlink\" href=\"index.php?p=modft&amp;ft=".$res['id']."\">Editer</a> - 
                <a class=\"comlink\" href=\"index.php?p=delft&amp;ft=".$res['id']."\">Supprimer</a>";
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
                Ecrit le <?php echo $res['post_date']; ?> par <?php echo stripslashes($res['auteur']); ?>
            </span>
        </div>
        <br/>
    </div>
<?php
}
else{
    ?>
    Aucune fiche technique demandée ?
    <?php
}
?>
