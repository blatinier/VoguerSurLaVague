<?php
$mysql_host="localhost"; //Adresse du serveur MySQL
$mysql_dbnom="melmelboo"; //Nom de la base de donn�es
$mysql_login="melmelboo"; //Login pour acc�der � la base de donn�es
$mysql_pass="rastsaa"; //Mot de passe pour acc�der � la base de donn�es
$site= $_SERVER['HTTP_HOST'];	//Or http://www.site.tld 
$langue="francais"; //francais, english 
$UTC="0"; //D�calage horaire ( -12 � +12 ) Vous pouvez V&eacute;rifier la valeur avec &quot;Test UTC&quot; dans l'admin 
$horloge="24";	//Type 24 or 12 hours 
$IpExlues = array("");	//exclude ip ex: array("123.117.86.38","220.181"); 220.181 --> plage 220.181.0.0 � 220.181.255.255 
$Flag_Exclus_by_IP = "";	//Flag ip exclus ex: "AllMyStats: IP non comptabilis�e"; 
$Flag_Exclus_by_cookie = "";	//Falg cookie installed ex: "AllMyStats: Visites non comptabilis�es (cookie)" 
$path_allmystats_abs = "/"; //Pour stats_in.php - Chemin absolu de allmystats (� partir de la racine du site ) ex : /allmystats/ (avec / au d�but) 
$user_login='melmelboo';
$passwd='rastsaa';
?>
