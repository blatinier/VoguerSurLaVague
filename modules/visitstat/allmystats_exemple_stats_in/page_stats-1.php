<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Document sans nom</title>

</head>
<body>

<p align="center">Vous pouvez mettre ici ce que vous voulez
<strong>Vous devez �diter et configurer le fichier stats_in.php avant d'utiliser cette fonction</strong>
</p>

<? 
$display_graph_by_day = true;	//true ou false ,affichage du graphique viteurs & pages visit�es / jour
$display_keywords = true; 	//true ou false ,affichage du tabbleau des mots cl�s
$display_page_view = true; 	//true ou false ,affichage du tabbleau pages visit�es
$display_org_geo = true; 	//true ou false ,affichage du tabbleau origine g�ographique
$AfficheOS = true;			//Affichage OS
$AfficheNav = true;			//Affichage Navigateurs utilis�s
$AfficheRobots = true;		//Affichage des robots
$delai_mise_cache = 5;		//defaut 5 .D�lai en minutes entre 2 mises � jour du fichier cache du mois en cours. Permet de ne pas surcharger le serveur
require('allmystats/stats_in.php');  // Chemin vers allmystats stats_in.php (sans / au d�but)
?>

<p align="center">Vous pouvez mettre ici ce que vous voulez </p>
</body>
</html>
