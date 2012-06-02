<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Document sans nom</title>

</head>
<body>

<p align="center">Vous pouvez mettre ici ce que vous voulez </p>
<strong>Vous devez éditer et configurer le fichier stats_in.php avant d'utiliser cette fonction</strong>

<? 
$display_graph_by_day = false;	//true ou false ,affichage du graphique viteurs & pages visitées / jour
$display_keywords = true; 		//true ou false ,affichage du tabbleau des mots clés
$display_page_view = false; 	//true ou false ,affichage du tabbleau pages visitées
$display_org_geo = true; 		//true ou false ,affichage du tabbleau origine géographique
$AfficheOS = true;				//Affichage OS
$AfficheNav = false;			//Affichage Navigateurs utilisés
$AfficheRobots = true;			//Affichage des robots
$delai_mise_cache = 1;			//defaut 5 .Délai en minutes entre 2 mises à jour du fichier cache du mois en cours. Permet de ne pas surcharger le serveur
require('allmystats/stats_in.php');  // Chemin vers allmystats stats_in.php (sans / au début)
?>

<p align="center">Vous pouvez mettre ici ce que vous voulez </p>
</body>
</html>
