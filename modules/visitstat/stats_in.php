<?
/*
 -------------------------------------------------------------------------
 AllMyStats V1.37 - Statistiques de fréquentation visiteurs et robots
 -------------------------------------------------------------------------
 Copyright (C) 2000 - Cédric TATANGELO (Cedstat)
 Copyright (C) 2008 - Herve Seywert
 -------------------------------------------------------------------------
 Web:   http://www.wertronic.com
 -------------------------------------------------------------------------
 Ce programme est libre, vous pouvez le redistribuer et/ou le modifier
 selon les termes de la Licence Publique Génrale GNU publiée par la Free
 Software Foundation .
 -------------------------------------------------------------------------

Fichier stats_in.php à appelé pour un accès public (ou non si $public = false;) au cumul des statistiques du mois en cours
Le déclenchement des mises à jour et la création du fichier cache est effectuée par les visiteurs 
suivant le réglage en minutes de l'interval entre 2 mises à jour (variable $delai_mise_cache)

Exemple:
$delai_mise_cache est réglé sur 5 (minutes)
Une mise à jour et une mise en cache a été effectuée à 12H00 : pour tous les visiteurs entre 12H00 et 12H05 c'est le fichier cache qui sera afffiché
Le 1er visiteur après 12H05 declenchera un calcul et une mise en cache des stats du mois en cours.
Cette solution permet de ne pas recalculer à chaque visiteur tous les statistiques, et donc menage les ressources serveur.

Note
Ne pas oublier de mettre les mois écoulés en cache via l'admin, simplement visualiser le cumul, afin d'alléger les calculs du mois en cours

-----------------------------------------
Ajout suffixe différent pour chaque combinaison pour permettre plusieurs configurations dans des pages différentes
*/

//######################################################## Configuration #######################################################################
	
	$public = false; //true pour accès public si false on ne peut y accéder seulement si l'on est connecté à l'admin
	$html_body = false; // Si nouvelle page = true - Si intégré dans une page existante = false
//------------------------------------------------------------------------------
/*
//Si ce fichier est appelé à partir d'une ou d'autres pages à l'aide de 
require_once('allmystats/stats_in.php');  // Chemin vers allmystats stats_in.php
il est possible de déplacer ces variables juste avant le require, ce qui permet suivant les pages qui l'appellent d'afficher ce que l'on veut
Dans ce cas commenter les lignes ci-dessous et voir les fichiers exemples dans /exemple_stats_in
*/

//Si le stats_in.php est appelé directement par un simple lien vers stats_in.php, décommenter les lignes ci-dessous ( supprimer /* et */ )
/*
$html_body = true; // Si nouvelle page = true - Si intégré dans une page existante = false
$display_graph_by_day = true;	//true ou false ,affichage du graphique viteurs & pages visitées / jour
$display_keywords = true; 		//true ou false ,affichage du tabbleau des mots clés
$display_page_view = true; 		//true ou false ,affichage du tabbleau pages visitées
$display_org_geo = true; 		//true ou false ,affichage du tabbleau origine géographique
$AfficheOS = true;				//Affichage OS
$AfficheNav = true;				//Affichage Navigateurs utilisés
$AfficheRobots = true;			//Affichage des robots

$delai_mise_cache = 5;		//defaut 5 .Délai en minutes entre 2 mises à jour du fichier cache du mois en cours. Permet de ne pas surcharger le serveur
*/


//------------------------------------------------------------------------------
//#################################################################################################################################################

include_once('application_top.php');
require ("config_allmystats.php");
require('includes/mysql_tables.php');
require ("includes/langues/$langue.php");
mysql_connect($mysql_host,$mysql_login,$mysql_pass);
mysql_select_db($mysql_dbnom);
$mois = trim(date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))))); 

/*
		//--------------------------------------------
		//Bouton de test force la mise en cache
		?>
		<form name="form" method="post" action="<? $PHP_SELF ?>">
				<input class="submit" name="archive_encours" type="submit" value="<? echo "Force: Mise à jour du cache pour le mois en cours"; ?>" alt="<? echo "Force: Mise à jour du cache pour le mois en cours"; ?>" >
		</form>
		<?
		//------------------------------------------
*/
		//$path_allmystats_abs est défini dans config_allmystats.php
		echo '<link rel="stylesheet" type="text/css" href="'.$path_allmystats_abs.'stylesheet_stats_in.css">';
		
		$mois_Visualise = $mois;
		$Mois_Annee = explode("/", $mois_Visualise);
		$format_date_file_name = $Mois_Annee[1].'-'.$Mois_Annee[0];

		//---------- Construction du suffixe fichier cache -------------------
		$suffixe = "";
		if ($display_graph_by_day) {
		$suffixe .= "gd";
		}
		if ($display_keywords) {
		$suffixe .= "kw";
		}
		if ($display_page_view) {
		$suffixe .= "pv";
		}
		if ($display_org_geo) {
		$suffixe .= "ge";
		}
		if ($AfficheOS) {
		$suffixe .= "os";
		}
		if ($AfficheNav) {
		$suffixe .= "na";
		}
		if ($AfficheRobots) {
		$suffixe .= "ro";
		}
		
		//-------------------------------------------------------------------
		//Mise en en forme ($AllBots) pour preg_match des bot connus (dans la table + bot en générale (bot, spider , etc)
		$result1=mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER." "); 
		$AllBots = '/Bot|Slurp|Scooter|Spider|crawl|';

		while($row=mysql_fetch_array($result1)){
			$Form_chaine = str_replace('/','\/',$row['bot_name']);
			$Form_chaine = str_replace('+','\+',$Form_chaine);
			$Form_chaine = str_replace('(','\(',$Form_chaine);
			$Form_chaine = str_replace(')','\)',$Form_chaine);
			$AllBots .= $Form_chaine.'|';
		}

		$AllBots = substr($AllBots,0,strlen($AllBots)-1); //supp last |
		$AllBots .= '/i';
		//-----------------------------------------------------------------------------
		//------------------------ Mise en tableau de la table bad user agent ---------
		unset($Matrice_bad_user_agent);
		$Bad_User_Agent=mysql_query("select * from ".TABLE_BAD_USER_AGENT.""); //
		while($bad_agents=mysql_fetch_array($Bad_User_Agent)){ // Mise en tableau des bad agents
			$Matrice_bad_user_agent[] = array($bad_agents['user_agent'], $bad_agents['info'],$bad_agents['type']);
		}
		//-----------------------------------------------------------------------------

		// Sinon fait: //Warning: filemtime() [function.filemtime]: SAFE MODE Restriction in effect. ? Pour $path_allmystats_abs
		$Racine_abs = str_replace($_SERVER['PHP_SELF'],"",$_SERVER['SCRIPT_FILENAME']);
		$path_allmystats = $Racine_abs.$path_allmystats_abs;

		$cache_mois_in = "";

	if( ($user_login!=$_SESSION['userlogin'] || $passwd!=$_SESSION['userpass']) && $public==false)	{
		echo "<br><br><center><strong>Vous n'êtes pas autorisé à visiter cette page</center></strong><br>";
		exit;
	} else {
		if ( date('YmdHi') >= date ("YmdHi", @filemtime($path_allmystats."cache/stats_".$site."_".$format_date_file_name."-".$suffixe.".php")) + $delai_mise_cache || isset($archive_encours) ){
			//echo "<br><strong>Todo mise en cache</strong><br>";
			$action_cache_mois_in = true;
			msg_temporaire("Opération en cours, veuillez patienter...");
			echo '<p align="center"><big><strong>Statistiques site: '.$site." - Mois: ".$mois_Visualise.'</strong></big><br>Dernière mise à jour: '.date('d/m/Y à H:i').'<br></p>';
		} else {
			//echo "<br><strong>Pas de mise en cache à faire</strong><br>";
			$action_cache_mois_in = false;
			echo "<center>Dernière mise à jour: ".date("d/m/Y à H:i", filemtime($path_allmystats."cache/stats_".$site."_".$format_date_file_name."-".$suffixe.".php"))."</center>" ; //Date de dèrnière mise à jour du fichier
			include_once($path_allmystats."cache/stats_".$site."_".$format_date_file_name."-".$suffixe.".php");
		}
	}
		//-------------------------------------------------------------------

if ($action_cache_mois_in) {

		//----------------------------------------------
		//Calcul Nb visiteurs et pages hors bot Robots
		$result=mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois'");
		
		$NbpageVues_HorsBots=0;
		$NbVisites_HorsBots=0;
		while($row=mysql_fetch_array($result)){
			if(!preg_match($AllBots, $row[agent])) {
			//---------------------------------------
				$User_Agent=$row[agent];
				$trash=false;
				for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
					if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
						$trash=true;
					}
				}
			//---------------------------------------
			}

			if(!preg_match($AllBots, $row[agent]) && $trash==false) {		
				$NbpageVues_HorsBots = $NbpageVues_HorsBots+$row[nb_visite];
				$NbVisites_HorsBots = $NbVisites_HorsBots+1;
			}			
		}
		//----------------------------------------------

	// -------------------------------------- Affichage -----------------------------------------------------------------------
	$show_footer = '<div align="center"><a href="http://allmystats.wertronic.com" target="_blank" class="Style1">AllMyStats</a> Powered by <a href="http://www.wertronic.com" target="_blank" class="Style1">Wertronic</a></div><br>';
	$show_cumul_page = $show_footer;
/*	
	if($display_counter) {
		$show_cumul_page = "Nombre de visiteurs: ".$NbVisites_HorsBots. "<br />""Nombre de pages vues: ".$NbpageVues_HorsBots;
	}
*/

################################################################################################################################################################
		//#############################################################################################
			// Graphique visiteurs et page visitées par Jour (todo en fonction car dans car existe aussi dans cumulpage.php, stats_in.php, histomaois.php)
		//---------------------------------------------------------------------------------------------
if ($display_graph_by_day) {

		//recup des données
		$result=mysql_query("select agent, referer, date, code, ip, nb_visite from ".TABLE_VISITEUR." where date like '%$mois' order by code ASC");
			unset ($val_jour);
			while($row=mysql_fetch_array($result)){
				if(!preg_match($AllBots, $row['agent']) && $trash==false) {		
					$date_comp = $row['date'];
					$cpt_jour = substr($date_comp,0,2)+0; // + 0 pour faire disparaitre les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)
					$val_jour[$cpt_jour][0] = $val_jour[$cpt_jour][0] + 1;
					$val_jour[$cpt_jour][1] = $val_jour[$cpt_jour][1] + $row['nb_visite'];

					$total_nb_visiteurs = $total_nb_visiteurs + 1;
					$total_nb_pages_visitees = $total_nb_pages_visitees + $row['nb_visite'];

					//Nombre max de pages visité, pour height graph
					if($val_jour[$cpt_jour][1]>$max_pages){
						$max_pages=$val_jour[$cpt_jour][1];
					}
					
					//Pour affichage echelle y
					if($max_pages!=0){
						$indice_echelle = bcdiv($val_jour[$cpt_jour][1],$max_pages,2); $hauteur=bcmul($indice_echelle,180.00,2);
						if ($MaxHauteur_echelle <= $hauteur) { $MaxHauteur_echelle = $hauteur; $EchyMin = '0'; }
					} else { // pour ne pas afficher 0 si $max_pages = 0
						$EchyMin  = '';	
						$max_pages = '';
					}
				}
			}
			$Nb_jours =  substr($date_comp,0,2);


$graph_byday = "";

		//---------- Affichage --------------------------------------------------------------------	
$graph_byday .= "
<TABLE align=center CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD class=\"TDstatsin\"><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE>"
		  	.$MSG_STAT_GRAF_JOUR_TITRE." (".$MSG_ROBOTS_EXCLUS.") - ".$mois."
		  </TH>
          </TR>
        <TR>
          <TD colSpan=2 class=\"TDstatsin\"><!-- Rows BEGIN -->
		  	<small>Total visiteurs = ".$total_nb_visiteurs."<br>
			Total pages visitées = ".$total_nb_pages_visitees."</small>
            <TABLE border=0 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
          		<td rowspan=\"2\" class=\"TDstatsin\">
					<B><SPAN class=PAGESVUES>".$MSG_PAGESVISITES."</SPAN><BR>
					& 
		  			<SPAN class=VISITES>".$MSG_VISITE."</SPAN></B>
		  		</TD>";

$graph_byday .= "
		  	<td nowrap=nowrap valign=\"top\" class=\"TDstatsin\">".$max_pages."</td>
		  	<td rowspan=\"2\" valign=\"bottom\" class=\"TDstatsin\"><img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"".$MaxHauteur_echelle."\" width=\"1\" alt=\"\" title=\"\"></td>";
			
          for($i=1;$i<=$Nb_jours;$i++){
				$graph_byday .= "<td rowspan=\"2\" valign=\"bottom\" class=\"TDstatsin\">";
				if($max_pages!=0) {
					$indice=bcdiv($val_jour[$i][1],$max_pages,2); $hauteur=bcmul($indice,180.00,2);
				}
				$graph_byday .= "<img src=\"".$path_allmystats_abs."images/histo-v.gif\" height=\"".$hauteur."\" width=\"7\" alt=\"".$val_jour[$i][1]."\" title=\"".$val_jour[$i][1]."\">";

				if($max_pages!=0){
					$indice=bcdiv($val_jour[$i][0],$max_pages,2); $hauteur=bcmul($indice,180.00,2);  
				}
				$graph_byday .=  "<img src=\"".$path_allmystats_abs."images/histo-vv.gif\" height=\"".$hauteur."\" width=\"7\" alt=\"".$val_jour[$i][0]."\" title=\"".$val_jour[$i][0]."\"></td>";
          }

$graph_byday .= "
		  </TR>
		  <tr>
			 <td align=\"right\" valign=\"bottom\" class=\"TDstatsin\">".$EchyMin."</td>
		  </tr>
		  
              <tr>
                <td class=\"TDstatsin\"><B>". $MSG_GRAF_JOUR."</B></TD>

 	    <td align=center class=\"TDstatsin\">&nbsp;</td><td align=center class=\"TDstatsin\">&nbsp;</td>"; // Pour echelle x

		//----------------- calcul jour du mois et week end pour echelle x ------------------------
		$jm = explode("/",$mois);
		$nbjourdumois = maxDaysInMonth($jm[0], $jm[1]);
		
		// Premier samedi du mois (6eme jour de la semaine) fonction get_first_day
		$premiersamedi =  strftime("%d", get_first_day(6, $jm[0], $jm[1])); 
		$weekend = "/";
		if ($premiersamedi == 7) { $weekend .= sprintf("%02d",1).'|'; } //Le 1er jour du mois est un dimanche
		for($i=$premiersamedi;$i<=$nbjourdumois;$i=$i+7){
			$week = $i;
			$week = $week+0;
			$weekend .= sprintf("%02d",$week).'|'; 
			$weekend .= sprintf("%02d",$week+1) .'|';
		}
		$weekend = substr($weekend,0,strlen($weekend)-1); //supp last |
		$weekend .= "/";
		//------------------ Affichage echelle x -----------------
		for($i=1;$i<=$nbjourdumois;$i++){
			$num=$i;
			if(!preg_match($weekend, sprintf("%02d",$num))) {	//$num+0 pour supprimer les 0 devant 01, 02 ,03 etc
		  		$graph_byday .=  "<td align=center class=\"TDstatsin\">". sprintf("%02d", $num)."</td>";
		  	} else {
		  		$graph_byday .=  "<td align=center class=\"TDstatsin\"><b><font color=#990000>". sprintf("%02d", $num)."</font></b></td>";
			}
		}
		//---------------------------------------------------------------------------------------

$graph_byday .= "
</TR></TBODY></TABLE><!-- Rows END --></TD></TR><!-- footer -->
        <TR>
		  </TR></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><br>";
 
		 //echo $graph_byday; //Affichage graph
		 $show_cumul_page .= $graph_byday;
		 $graph_byday ="";

} // End if ($display_graph_by_day) {

################################################################################################################################################################

				//-------------------- Affichage tableau keywords -----------------------------
	if ($display_keywords) {
		$show_cumul_page .= "
		<table align=center cellpadding=1 cellspacing=0 class=TABLEBORDER>
		  <tr>
			<td class=\"TDstatsin\"><!-- Data BEGIN -->
			  <table cellpadding=5 cellspacing=0 class=TABLEFRAME><!-- header -->
				<tr>
				  <th class=tabletitle>". $MSG_REF_TITRE. ' '. $mois."</th>
				  </tr>
				<tr>
				  <td colSpan=2 class=\"TDstatsin\"><!-- Rows BEGIN -->
					<table border=1 cellpadding=2 cellspacing=0 class=TABLEDATA>
					  <tr>
						<th class=\"THstatsin\">". $MSG_REFERANT."</th>
						<th class=\"THstatsin\">". $MSG_REF_MOTCLE."</th>
						<th class=\"THstatsin\">". $MSG_VISITEURS."</th>
					  </tr>";
			
			require "config_moteur.php"; 
		
			//-------------------------------------------------------------------------------------------
			unset($Tab_referer);
			$result=mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois' order by referer");
			$nb_unknow = 0;	
			while($row=mysql_fetch_array($result)){
				$referer=parse_url($row[referer]);
				
				if(!preg_match($AllBots, $row['agent'])) { // && $trash == false A voir exlus aussi les "bad user agent"
					//--------------------- extraction mots clés -------------
					$url=parse_url($site);
					$ref=$referer["host"];
			
					if($ref<>$url["host"]){
						//Pour les adwords l'url est trouvée car dans visiteur.php est ajouté au referer $Referer = urldecode($Url_syndication[0]). '?googlesyndication=1' ou '&googlesyndication=1';
						$test_keword[] = array($ref,MotsCles($row[referer],$ref));
					}
					//--------------- ext referer et nb visite ---------------
					if ($row['referer']) { //
						$Tab_referer[] = $referer['host'];
					} else { //Visiteurs avec user agent et referer vide modif 31-08-2009
						$Tab_referer[] = "Unknown or direct";
						$nb_unknow = $nb_unknow + 1;
					}
				}
			}
				
			//Attention array_unique ne garde que les clés différentes mais garde la chronologie --> faire usort($Tab_referer, "CompareValeurs"); après
			$Tab_referer_unique = @array_unique($Tab_referer);
			@usort($Tab_referer_unique, "CompareValeurs");
		
			unset($Tab_aff_ref);
			
				for($i=0;$i<count($Tab_referer_unique);$i++){ 
					$result_agent = mysql_query("select agent from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and date like '%$mois'");
					$test_agent = mysql_fetch_array($result_agent);
					//if(!preg_match($AllBots, $test_agent['agent'])) n'est pas nécessaire mais certainement plus rapide comme ça
					if(!preg_match($AllBots, $test_agent['agent'])) { // && $trash == false A voir exlus aussi les "bad user agent" modif 31-08-2009

						if (trim($Tab_referer_unique[$i])<>'') {
							// compte le nombre de visiteurs par referant
							if ( strstr(trim($Tab_referer_unique[$i]), 'google') ) {
								$result = mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and date like '%$mois'");
							} else {
								$result = mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and referer not like '%source=web%' and referer not like '%source=hp%' and date like '%$mois'");
							}

							if (!$result) {
							   echo 'Impossible d\'exécuter la requête : ' . mysql_error();
							   exit;
							}
							$row_nb_visites=mysql_fetch_row($result);
							
							//------------------------------------------
							//tab mots clé/referant
							for($i2=0;$i2<count($test_keword);$i2++){ 
								if(strstr($test_keword[$i2][0],$Tab_referer_unique[$i])) {
										$referer_kew[$i] .= $test_keword[$i2][1].' ';
								}
							}
							//------------------------------------------
							if ($row_nb_visites[0] == 0){ //si = 0 => referer vide (donc result mysql vide) user agent et referer vide modif 31-08-2009
								$row_nb_visites[0] = $nb_unknow;
							}

							$Tab_aff_ref[] = array($row_nb_visites[0], $Tab_referer_unique[$i],$referer_kew[$i]); //dans ce sens car on trie sur nb visites
						}
					}
				}
				//--------------------------------------------------
				// Mise en forme mots clés googlesyndication et Affichage
				@array_multisort($Tab_aff_ref,SORT_DESC);
		
				for($i=0;$i<count($Tab_aff_ref);$i++){ 
		
					$Mots_by_referer =  str_replace('] [', ']+-+[', $Tab_aff_ref[$i][2]);
					$Mots_by_referer =  str_replace(']  [', ']+-+[', $Mots_by_referer); // car certains comportent 2 espaces --> A voir où ils sont mis ou supprimmer espaces multiples
					$Mots_by_referer =  str_replace(']   [', ']+-+[', $Mots_by_referer);// car certains comportent 3 espaces --> A voir où ils sont mis
					$Mots_by_referer = strtolower($Mots_by_referer); // Tout en minuscule
					
					unset($Tab_motcles);
					$Tab_motcles = explode('+-+',$Mots_by_referer);
					for($j=0;$j<count($Tab_motcles);$j++){ 
						$Tab_motcles[$j] = trim($Tab_motcles[$j]); 
					}
					
					unset($Tab_motcles_unique);
					$Tab_motcles_unique = array_unique($Tab_motcles);
					
					//----------------------
					unset($tab_keywords);
					for($j=0;$j<count($Tab_motcles_unique);$j++){ 
						if (trim($Tab_motcles_unique[$j])) {
							$tab_keywords[] = array($Tab_motcles_unique[$j], substr_count($Mots_by_referer, $Tab_motcles_unique[$j]));
						}
					}
					
					// Obtient une liste de colonnes
					unset($Mot);
					unset($Nb);
					if ($tab_keywords) {
						foreach ($tab_keywords as $key => $row) {
							$Mot[$key]  = $row[0];
							$Nb[$key] = $row[1];
						}
					}
					
					// Trie les données par volume croissant
					// Ajoute $tab_keywords en tant que dernier paramètre, pour trier par la clé commune
					@array_multisort($Nb, SORT_DESC, $tab_keywords);
					//---------------------------------------------------------------
		
					//Affichage
					$lenmax=35;
					if (strlen($Tab_aff_ref[$i][1])>$lenmax) {
						$chaine1=substr($Tab_aff_ref[$i][1], 0, $lenmax);
						$chaine2=substr($Tab_aff_ref[$i][1], $lenmax);
						$Tab_aff_ref[$i][1] = $chaine1."<br>".$chaine2;
					}
		
					//-----------------------------
					//Pour affichage googlesyndication
						$show_cumul_page .= "<tr><td valign=top class=\"TDstatsin\">".$Tab_aff_ref[$i][1]."</td>
						<td nowrap class=\"TDstatsin\">"; 
					
						if (count($Tab_motcles_unique)>1) {	
							$show_cumul_page .= "<span> Différents: ". count($Tab_motcles_unique)."</span>";
						}
						for($j=0;$j<count($tab_keywords);$j++){ //Affiche les phrases clés
							
							if (strstr($tab_keywords[$j][0],'googlesyndication=1')) { 
								$tab_keywords[$j][0] = str_replace('?googlesyndication=1', '<font color=#666666>&nbsp;&nbsp;'.utf8_encode($MSG_ADWORDS_CONTENT_NETWORK).'</font><br>', $tab_keywords[$j][0]);
								$tab_keywords[$j][0] = str_replace(htmlentities('&googlesyndication=1'), '<font color=#666666>&nbsp;&nbsp;'.utf8_encode($MSG_ADWORDS_CONTENT_NETWORK).'</font><br>', $tab_keywords[$j][0]);//ici il faut htmlentities('&googlesyndication=1'), voir si ne serait pas mieux au niveau de config moteur
								$tab_keywords[$j][0] = substr(trim($tab_keywords[$j][0]),0,-5); //supp le dernier '<br> '
							}
							
							$show_cumul_page .= "<br><span><font color=#990000>".utf8_decode(urldecode($tab_keywords[$j][0]))."</font></span>";
						}
						$show_cumul_page .= "&nbsp;</td>
						<td nowrap class=\"TDstatsin\">";
						
						$show_cumul_page .= "<span> Total: ".$Tab_aff_ref[$i][0]."</span>";
						for($j=0;$j<count($tab_keywords);$j++){ //affiche le nombre de visite pour chaque mot ou référant
								$show_cumul_page .= "<br><span><font color=#990000>".utf8_decode(urldecode($tab_keywords[$j][1]))."</font></span>"; 
						}
						$show_cumul_page .= "&nbsp;</td></tr>";
					//----------------------------
				}
				//--------------------------------------------------
				unset($Tab_referer);
				unset($Tab_referer_unique);
				unset($row_nb_visites);
				unset($Tab_aff_ref);
	
		$show_cumul_page .= '
		</table><!-- Rows END --></td></tr><tr>
			  </tr><!-- no footer --></table><!-- Data END --></td></tr></table><br>' ;
			  
	} // End if ($display_keywords ) {
	
			//------------------------ Affichage pages visités --------------------------------------------------------

	if ($display_page_view) {		

		if ($mois==""){
			$mois = date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		}
	
		$result=mysql_query("select * from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where date like '%$mois' and v.code=p.code order by heure");
		$nb_visite=0;
		$heure_premier="";
		while($row=mysql_fetch_array($result)){
			if($heure_premier==""){
				$heure_premier=$row[heure];
			}
			$nb_visite=$nb_visite+$row[nb_visite]; //Pour calcul %
			$heure_dernier=$row[heure];
		}
		
		$max_visite=$NbpageVues_HorsBots;
		
		$result=mysql_query("select * from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where v.date like '%$mois' and v.code=p.code order by page");
		$row=mysql_fetch_array($result);
		$url=$row[page];
		$nb_url=0;
		$nb_vis=0;
		$result=mysql_query("select * from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where v.date like '%$mois' and v.code=p.code order by page");
	
		while($row=mysql_fetch_array($result)){
			$page=$row[page];
	
			if(!preg_match($AllBots, $row[agent])) {
				//---------------------------------------
					$User_Agent=$row[agent];
					$trash=false;
					for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
						if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
							$trash=true;
						}
					}
				//---------------------------------------
			}
	
			if(!preg_match($AllBots, $row[agent]) && $trash==false) {
				if($url==$page){
					$nb_url=$nb_url+$row[nb_visite];
					$nb_vis++;	
				} else {
					$nb=$nb_url*100; 
					if($max_visite!=0){
						$pourcent=bcdiv($nb,$max_visite,2);
					}
					$page_vue[]= array($url,$nb_vis, $nb_url, $pourcent);
					$url=$page;
					$nb_url=$row[nb_visite];
					$nb_vis=1;
				}
			}
		} // Fin if(!preg_match($AllBots, $row[agent])) {
	
			$nb=$nb_url*100; 
			if($max_visite!=0){
				$pourcent=bcdiv($nb,$max_visite,2);
			}
	
	$show_cumul_page .= '
	<table align=center cellpadding=1 cellspacing=0 class=TABLEBORDER>
	  <tr>
		<td class="TDstatsin"><!-- Data BEGIN -->
		  <table cellpadding=5 cellspacing=0 class=TABLEFRAME><!-- header -->
			<tr>
			  <th class=tabletitle>'. $MSG_PAGESVISITES.' ('.$MSG_ROBOTS_EXCLUS.')<br>'.$mois.'</th>
			  </tr>
	
			<tr>
			  <td colSpan=2 class="TDstatsin"><!-- Rows BEGIN -->
				<table border=1 cellpadding=2 cellspacing=0 class=TABLEDATA>
					<tr>
					<th valign="top" align="right" class="THstatsin">'. $MSG_TOTAL.' : <br><br></th>
					<td valign="top" align="center"class="TDstatsin">'. $NbVisites_HorsBots.'<br></td>
					<td valign="top" align="center" class="TDstatsin">'. $NbpageVues_HorsBots.'<br></td>
					<td valign="top" align="center" class="TDstatsin">&nbsp;<br></td>
					</tr>
				  <tr>
					<th class="THstatsin">'.$MSG_PAGE.'</th>
					<th class="THstatsin">'.$MSG_VISITE.'</th>
					<th class="THstatsin">'.$MSG_PAGESVISITES.'</th>
					<th class="THstatsin">'.$MSG_PAGES_POURCENTAGE.'</th></tr>';
					
		$page_vue[]= array($url,$nb_vis, $nb_url, $pourcent);
		@usort($page_vue, "CompareValeurs");
		$cpt=0;
		while ($page_vue[$cpt][0]<>""){
			$show_cumul_page .= "<tr><td class=\"TDstatsin\">".utf8_decode($page_vue[$cpt][0])."</td><td align=center class=\"TDstatsin\">".$page_vue[$cpt][1]."</td><td align=center class=\"TDstatsin\">".$page_vue[$cpt][2]."</td><td align=center class=\"TDstatsin\">".$page_vue[$cpt][3]."%</td></tr>";
			$cpt++;
		}
		 
		$show_cumul_page .= '
		</table><!-- Rows END --></td></tr><!-- no footer --></table><!-- Data END --></td></tr></table><BR>
		';
	
	} // End de if ($display_page_view) {	
	
		//############################################################################################
		//------------ Affichage Origine géographique des viteurs (hors robots) ----------------------
	
	if ($display_org_geo) {
			$result=mysql_query("select agent, nb_visite ,domaine from ".TABLE_VISITEUR." where date like '%$mois'");
			while($row=mysql_fetch_array($result)){
				$Country[] .= $row['domaine'];
			}
			$Country = @array_unique($Country);
			@array_multisort ($Country, SORT_ASC); 
	
			//----------------------------------
			//Important mise à 0 des variables
			unset($Tab_country_pages_visiteurs);
			for($i=0;$i<count($Country);$i++){ //Comment faire autrement
				$Nb_visites = 'Nb_'.$Country[$i];
				$$Nb_visites = 0;
		
				$Nb_pages_visites = $Country[$i];
				$$Nb_pages_visites = 0;
			}
			//-----------------------------------
	
			$result=mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois'");
			while($row=mysql_fetch_array($result)){
				//---------------------------------------
				$User_Agent=$row[agent];
				$trash=false;
				for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
					if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
						$trash=true;
					}
				}
					//---------------------------------------
				if(!preg_match($AllBots, $row[agent]) && $trash==false) {
					for($i=0;$i<count($Country);$i++){
						if($row[domaine]==$Country[$i]) {
							$Nb_visites = 'Nb_'.$Country[$i];
							$$Nb_visites = $$Nb_visites + 1;
		
							$Nb_pages_visites = $Country[$i];
							$$Nb_pages_visites = $$Nb_pages_visites + $row['nb_visite'];
		
							$Tab_country_pages_visiteurs[$i] = array($Country[$i],$$Nb_pages_visites,$$Nb_visites);
						}
					}
				}
			}
			//--------- Affichage des résultats --------------------------
	
			@usort($Tab_country_pages_visiteurs,"CompareValeurs");
			$indice = bcdiv(1,($Tab_country_pages_visiteurs[0][1]/300),2); //proportion en rapport au plus grand nb de pages visités
	
	$show_cumul_page .= '
	<table align=center cellpadding=1 cellspacing=0 class=TABLEBORDER>
	  <tr>
		<td class=\"TDstatsin\"><!-- Data BEGIN -->
		  <table align=center cellpadding=5 cellspacing=0 class=TABLEFRAME><!-- header -->
			<tr>
			  <th class=tabletitle>'.$MSG_DOMAIN_TITRE.' ('.$MSG_ROBOTS_EXCLUS.')</th>
			  </tr>
			<tr>
			  <td colSpan=2 class=\"TDstatsin\"><!-- Rows BEGIN -->
				<table border=1 cellpadding=2 cellspacing=0 class=TABLEDATA>
				  <tr>
					<th class=\"THstatsin\">'.$MSG_DOMAIN.' ('.count($Tab_country_pages_visiteurs).')</th>
					<th class=\"THstatsin\">'.$MSG_NB_VISITEURS.'</th>
					<th class=\"THstatsin\">'.$MSG_PAGESVISITES.'</th>
				  </tr>
	';
	
			for($i=0;$i<count($Tab_country_pages_visiteurs);$i++){
				if ($Tab_country_pages_visiteurs[$i][0]=='') { $Tab_country_pages_visiteurs[$i][0] = $MSG_ORIGIN_UNKNOWN;}
				$show_cumul_page .= "<tr>
				<td class=\"TDstatsin\"> 
				<b>".$Tab_country_pages_visiteurs[$i][0]."</b>
				</td>
				<td align=\"left\" class=\"TDstatsin\">
				<img src=\"".$path_allmystats_abs."images/histo-h.gif\" width=\""; 
				$hauteur=bcmul($Tab_country_pages_visiteurs[$i][2],$indice,2);  
				$show_cumul_page .= $hauteur .	"\" height=\"8\">".$Tab_country_pages_visiteurs[$i][2].
				"</td>
				<td align=\"left\" class=\"TDstatsin\">
				<img src=\"".$path_allmystats_abs."images/histo-h.gif\" width=\""; 
				$hauteur=bcmul($Tab_country_pages_visiteurs[$i][1],$indice,2);  
				$show_cumul_page .= $hauteur."\" height=\"8\">".$Tab_country_pages_visiteurs[$i][1].
				"</td>";	
			}
	
			$show_cumul_page .= '    
			</table><!-- Rows END --></td></tr><!-- no footer --></table><!-- Data END --></td></tr></table><br>
			';
			
		} // End if ($display_org_geo) {
		//--------------------------------------------------------------------------
		
		echo $show_cumul_page; // Affichage des tableaux
		
		
		$result=mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois'");
		$nbr_result=mysql_num_rows($result);
	
	//$AfficheOS=true;
	//$AfficheNav=true;
	//$AfficheRobots=true;
	$dislpay_button_tool_bots = "false"; //Important "false" entre guillemets car affiche le bouton si  $dislpay_button_tool_bots = "";
		?>
		<table width="90%" border="0" align="center">
		  <tr>
			<td align="center"><?  
				include_once('tab_os_nav_robots.php'); //Calcul et affichage des tableaux
				mysql_close(); ?>
			</td>
		  </tr>
		</table>
		<?
	
		message($msg , "");	//efface le message Patientez
	
		//--------- Footer -------------------
	
		echo $show_footer ;
		//----------------------------------
		
	########################################## Mise en cache du mois encours actuel ######################################################################################	
	
			$mois_actuelle = date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			$mois_Visualise = $mois;
		
			$Mois_Annee_visualise = explode("/", $mois_Visualise);
			$mois_visualise = $Mois_Annee_visualise[0]; // mois
			$annee_visualise = $Mois_Annee_visualise[1]; //année
			
			$Mois_Annee_actuelle = explode("/", $mois_actuelle);
			$mois_actuelle = $Mois_Annee_actuelle[0];
			$annee_actuelle = $Mois_Annee_actuelle[1];
	
			if ($action_cache_mois_in || isset($archive_encours) ) { //on met en cache
			
				if (!is_dir($path_allmystats."cache")) {
					mkdir ($path_allmystats."cache");
				}

				//--------------------------------------------------------------------------------------
				//Ne sert pas pour ici (stats_in) mais est pour l'instant nécessaire pour les pages mises en cache avec cumulpage.php
				if (!is_dir($path_allmystats."cache/images")) {
					mkdir ($path_allmystats."cache/images");
				}
				if (!file_exists("cache/images/histo-v_black.gif")) {
					copy($path_allmystats."images/histo-v_black.gif", $path_allmystats."cache/images/histo-v_black.gif");
				}
				if (!file_exists("cache/images/histo-v.gif")) {
					copy($path_allmystats."images/histo-v.gif", $path_allmystats."cache/images/histo-v.gif");
				}
				if (!file_exists("cache/images/histo-vv.gif")) {
					copy($path_allmystats."images/histo-vv.gif", $path_allmystats."cache/images/histo-vv.gif");
				}
				if (!file_exists("cache/images/histo-h.gif")) {
					copy($path_allmystats."images/histo-h.gif", $path_allmystats."cache/images/histo-h.gif");
				}
				//--------------------------------------------------------------------------------------

				$Mois_Annee = explode("/", $mois_Visualise);
				$format_date_file_name = $Mois_Annee[1].'-'.$Mois_Annee[0];
				
				//$Fnm = $path_allmystats."cache/stats_".$site."_".$format_date_file_name."-".$suffixe.".php"; //erreur open_basedir restriction in effect PHP5.2??
				//$Fnm = "//".$path_allmystats_abs.$site."_".$format_date_file_name."-".$suffixe.".php"; //erreur open_basedir restriction in effect PHP5.2??
				//$Fnm = "stats/allmystats/cache/stats_".$site."_".$format_date_file_name."-".$suffixe.".php"; //OK si appelle à partir de la racine
				//$Fnm = "/stats/allmystats/cache/stats_".$site."_".$format_date_file_name."-".$suffixe.".php";//erreur open_basedir restriction in effect PHP5.2??

				 //---------- Pour contourner le pb open_basedir restriction in effect avec fopen ----------------
				 $nbSlashes = substr_count($_SERVER['SCRIPT_NAME'], '/'); // on compte le nombre total de slashes contenu dans le lien relatif du fichier courant
				 $nbSlashes --; // on ne compte pas le slash de la racine (placé au début du lien relatif)
				 $remontee = ''; // on initialise la remontée dans l'arborescence
				 for($i = 0; $i < $nbSlashes; $i++)
				 {
					 $remontee .= '../';
				 }
				//------------------------------------------------------------------------------------------------
				//Supprime 1er "/" de $path_allmystats_abs Important si $remontee = ""
				substr('abcdef', 1);
				$path_allmystats_rel = substr($path_allmystats_abs, 1);
				$Fnm = $remontee.$path_allmystats_rel."cache/stats_".$site."_".$format_date_file_name."-".$suffixe.".php";

				if (!$inF = @fopen($Fnm,"w")){
					echo "Erreur create file<br />";
				}
				//$inF = fopen($Fnm,"w");

if ($html_body) {
	$page_html = 
	'<?
	if( ($user_login!=$_SESSION["userlogin"] || $passwd!=$_SESSION["userpass"]) && $public==false)	{
		echo "<br><br><center><strong>Vous n\'êtes pas autorisé à visiter cette page</center></strong><br>";
		exit;
	}
	?>
	
	<html>
		<head>
		<title>AllMyStats - '. $site.' - '.$format_date_file_name.'</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" type="text/css" href="../stylesheet_stats_in.css">
		</head>
		<body><table width="100%" border="0" align="center">
			<tr>
				<td align="center"><big><strong>Statistiques site: '.$site." - Mois: ".$mois_Visualise.'</strong></big><br><br></td>
			</tr>
			<tr>
				<td align="center">'.$show_cumul_page . $show_page_os_nav_robots.$show_footer.'</td>
			</tr>
			</table></body></html>';
} else {
	$page_html = 
	'<?
	if( ($user_login!=$_SESSION["userlogin"] || $passwd!=$_SESSION["userpass"]) && $public==false)	{
		echo "<br><br><center><strong>Vous n\'êtes pas autorisé à visiter cette page</center></strong><br>";
		exit;
	}
	?>
	
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" type="text/css" href="../stylesheet_stats_in.css">
		
		<table width="100%" border="0" align="center">
			<tr>
				<td align="center"><big><strong>Statistiques site: '.$site." - Mois: ".$mois_Visualise.'</strong></big><br><br></td>
			</tr>
			<tr>
				<td align="center">'.$show_cumul_page . $show_page_os_nav_robots.$show_footer.'</td>
			</tr>
			</table>';
}



				if(!@fwrite($inF,$page_html)){
					echo "Erreur write file<br />";
				}
				//fwrite($inF,$page_html);

				if(!@fclose($inF)){
					echo "Erreur close file";
				}
				//fclose($inF); 
			}

} // End if ($action_cache_mois_in) {

//-------------------------------------------------------------------------------------------
//#########################################"## Functions ######################################################
//Il faut la fonction CompareValeurs($val1, $val2) qui ne se trouve normalement seulement dans index_frame.php
function CompareValeurs($val1, $val2) {
	if ($val2[1] == $val1[1])
		return(strcmp($val1[0],$val2[0]));
	else
		return($val2[1] - $val1[1]);
}

function msg_temporaire ($temp_msg) {
 //ob_flush();
 flush();
?> 
<p align="center" ID="cache"><strong><big><font color="#FF0000"><?php echo $temp_msg;?></font></big></strong></p> 
<script type="text/javascript">
 // Le message d'attente est masqué par défaut au cas ou java script
 // serait désactivé sur le navigateur client donc on l'affiche :
 document.getElementById("cache" ).style.visibility = "visible";
 </script> 
<?
 //ob_flush();
 flush();
 }
 
function message($msg, $title){
?> 
<script type="text/javascript">
 		document.getElementById("cache" ).style.visibility = "hidden";
	</script> 
<?php
//flush();
}

//-------------------------------------------------------------------------------------------
//TODO rep functions car existe aussi dans cumulpage.php, stats_in.php, histomaois.php
/**
* Fonction retournant le nombre de jours dans un mois.
* @param integer $month Mois de 1 à 12
* @param integer $year Année
* @return integer Nombre de jours
*/
function maxDaysInMonth($month, $year)
{
  $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  return $days;
}

  /**
   *
   *  Gets the first weekday of that month and year
   *
   *  @param  int   The day of the week (0 = sunday, 1 = monday ... , 6 = saturday)
   *  @param  int   The month (if false use the current month)
   *  @param  int   The year (if false use the current year)
   *
   *  @return int   The timestamp of the first day of that month
   *
   **/ 
  function get_first_day($day_number=1, $month=false, $year=false)
  {
    $month  = ($month === false) ? strftime("%m"): $month;
    $year   = ($year === false) ? strftime("%Y"): $year;
   
    $first_day = 1 + ((7+$day_number - strftime("%w", mktime(0,0,0,$month, 1, $year)))%7);
 
    return mktime(0,0,0,$month, $first_day, $year);
  }

//##############################################################################################################
?>

