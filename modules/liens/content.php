<?php
$nom_page='pages des liens';
$Racine_abs = str_replace($_SERVER['PHP_SELF'],"",$_SERVER['SCRIPT_FILENAME']);
require $Racine_abs.'/modules/visitstat/visiteur.php';
$cat = $_GET['cat'];
?> 
<br/>
<img src="images/Mes_coups_de_coeur.png" alt="Mes coups de coeur" />
<?php
if(empty($cat) || $cat == 1){
?>
<div style="width:90%; margin-left:20px;">
<h3>Inspiration Déco</h3>
<div>
	<a href="http://www.baudouin.fr/">Baudoin Photographer</a> ~
	<a href="http://www.theselby.com/">The Selby</a> ~
	<a href="http://www.designspongeonline.com/">Design*Sponge</a> ~
	<a href="http://www.vertcerise.com/">Vert Cerise</a> ~ 
	<a href="http://www.brandydaisy.com/">Brandy Daisy</a> ~
	<a href="http://www.bab-la-bricoleuse.net/">Bab la bricoleuse</a> ~
</div>
</div>
<?php
}
if(empty($cat) || $cat == 2){
?>
<div style="width:90%; margin-left:20px;">
<h3>Inspiration Fashion</h3>
<div>	
	<a href="http://karlascloset.blogspot.com/">Klaras's closet</a>  ~
	<a href="http://alicepoint.blogspot.com/">Alice Point</a>  ~
	<a href="http://thestylishwanderer.blogspot.com/">The Stylish Wanderer</a>  ~
	<a href="http://www.taghrid.cc/">Taghrid.cc</a>  ~
	<a href="http://freelancersfashion.blogspot.com/">The Freelancer's Fashionblog</a>  ~	
	<a href="http://www.thecherryblossomgirl.com/">The cherry blossom girl</a>  ~
	<a href="http://www.misspandora.fr/">Pandora</a>  ~
	<a href="http://www.etpourquoipascoline.fr/">Et pourquoi pas Coline</a>  ~
	<a href="http://www.leblogdebetty.com/">Le blog deBetty</a>  ~
	<a href="http://www.leblogdelamechante.fr/">Eleonore Bridge</a>  ~
	<a href="http://www.sushipedro.com/">Sushi & Pedro</a>  ~


</div>
</div>
<?php
}
if(empty($cat) || $cat == 3){
?>
<div style="width:90%; margin-left:20px;">
<h3>BD Blog</h3>
<div>
	<a href="http://lanternebrisee.net/">La Lanterne brisée</a>  ~
	<a href="http://margauxmotin.typepad.fr/margaux_motin/">Margaux Motin</a>  ~
	<a href="http://www.penelope-jolicoeur.com/">Pénélope Jolicoeur</a>  ~
	<a href="http://diglee.com/">Diglee</a>  ~
	<a href="http://www.sanaa-k.com/">Sanaa-k</a>  ~
	<a href="http://grumeautique.blogspot.com/">Petit Précis Grumeautique</a>  ~
</div>
</div>
<?php
}
if(empty($cat) || $cat == 4){
?>
<div style="width:90%; margin-left:20px;">
<h3>Plaisir de Lire</h3>
<div>
	<a href="http://maitremo.fr/">Maître Mô</a>  ~
	<a href="http://www.navie.fr/">Navie</a>  ~
	<a href="http://www.mondedemarion.info/">Le Monde Tranquille de Marion</a>  ~
	<a href="http://ellis-lynen.over-blog.com/">Patate y patata</a>  ~
	<a href="http://blog.grigrifounet.fr/">Le Blog Grigrifounesque</a>  ~
	<a href="http://amelilolilol.blogspot.com/">Amélie en Afrique</a>  ~
	<a href="http://jaffar-chui-coince.over-blog.com/">Jaffar chui coincé !</a>  ~
	<a href="http://lisetbulle.over-blog.com/">Lisez, regardez, écoutez</a>  ~
</div>
</div>
<?php
}
?>
