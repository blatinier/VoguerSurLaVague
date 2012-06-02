<?php

$ft_query = "SELECT id, titre, miniature 
                FROM voguer_fichestech
                WHERE pubdate < NOW() 
                ORDER BY RAND() LIMIT 0,1";
$ft_req = mysql_query($ft_query);
$ft_res = mysql_fetch_assoc($ft_req);
$ftid = $ft_res['id'];
$ftmini = $ft_res['miniature'];
$fttitre = $ft_res['titre'];

$list_cat_q = "SELECT titre,id FROM voguer_cat WHERE type=0";
$list_cat_q = mysql_query($list_cat_q)or die(mysql_error());

$requete = "SELECT COUNT(*) AS nba, cat FROM mellismelau_articles GROUP BY cat";
$req = mysql_query($requete)or die(mysql_error());

$poids = array();
$sum = 0;
while($res = mysql_fetch_assoc($req)){
	$poids[$res['cat']] = $res['nba'];
	$sum += $res['nba'];
}
foreach($poids as $key => $val){
	$poids[$key] = $val/$sum+0.7;
}
?>
<div class="menuBlock">
    <a href="index.php?p=contact">
        <img style="width:237px;" src="images/Moi.png" alt="À propos" />
    </a>
</div>
<hr />

<div class="menuTitle">Articles récents</div>
<ul class="ulmenuV">
	<?php 
	$requete = "SELECT id, titre FROM mellismelau_articles WHERE pubdate < NOW() ORDER BY pubdate DESC LIMIT 0,5";
	$req = mysql_query($requete)or die(mysql_error());
	while($res = mysql_fetch_assoc($req)){
		echo '<li><a href="index.php?art='.$res['id'].'">'.stripslashes($res['titre']).'</a></li>';
	}
	?>
</ul>
<hr />
<div id="menuArchiveCat">
    <div id="menuBlockRight">
        <div class="menuTitle">Catégories</div>
        <ul class="ulmenuV">
            <?php 
                while($r = mysql_fetch_assoc($list_cat_q)){
                    echo '<li><a href="index.php?cat='.$r['id'].'">'.$r['titre'].'</a></li>';
                }
            ?>
        </ul>
    </div>

    <div id="menuBlockLeft">
        <div class="menuTitle">Archives</div>
        <ul class="ulmenuV">
        <?php
            include("modules/archives/archive.php");
        ?>
        </ul>
    </div>
</div>
<div style="clear: left;"></div>
<hr />

<div class="menuBlock">
    <div class="menuTitle">Liens</div>
    <ul class="ulmenuV">
        <li><a href="http://www.baudouin.fr/">Baudoin Photographer</a></li>
        <li><a href="http://www.theselby.com/">The Selby</a></li>
        <li><a href="http://www.vertcerise.com/">Vert Cerise</a></li> 
        <li><a href="http://karlascloset.blogspot.com/">Klaras's closet</a> </li>
        <li><a href="http://thestylishwanderer.blogspot.com/">The Stylish Wanderer</a> </li>
        <li><a href="http://www.taghrid.cc/">Taghrid.cc</a> </li>
        <li><a href="http://freelancersfashion.blogspot.com/">The Freelancer's Fashionblog</a> </li>	
        <li><a href="http://www.thecherryblossomgirl.com/">The cherry blossom girl</a> </li>
        <li><a href="http://www.misspandora.fr/">Pandora</a> </li>
        <li><a href="http://www.etpourquoipascoline.fr/">Et pourquoi pas Coline</a> </li>
        <li><a href="http://www.leblogdebetty.com/">Le blog deBetty</a> </li>
        <li><a href="http://www.leblogdelamechante.fr/">Eleonore Bridge</a> </li>
        <li><a href="http://margauxmotin.typepad.fr/margaux_motin/">Margaux Motin</a> </li>
    </ul>
</div>
<!--<div class="menuTitle">
    Do It Yourself !
</div>
<div class="menuBlock">
    <a href="index.php?p=viewft&amp;ft=<?php echo $ftid; ?>">
        <div id="vignetteAstuce">
            <img id="astuceMenuV" src="<?php echo $ftmini; ?>" alt="fiche technique" /><br/>
            <img id="cadreAstuceMenuV" src="images/Cadre03.png" alt="Cadre" />
        </div>
        <div id="astuceLegendMenuV">
            <strong><?php echo stripslashes($fttitre); ?></strong>
        </div>
    </a>
</div>-->
