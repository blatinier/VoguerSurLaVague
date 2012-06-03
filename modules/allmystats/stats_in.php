<?php
/*
  -------------------------------------------------------------------------
 AllMyStats V1.75 - Statistiques site web - Web traffic analysis
 -------------------------------------------------------------------------
 Copyright (C) 2008-2010 - Herve Seywert
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------
French
tats_in.php permet d'afficher publiquement les sections de vos statistiques souhaitées dans les pages de votre site web
Le cache de fichiers est déclenché par les visiteurs en fonction de la valeur de «$ time_update_cache". 
Cette solution ne recalcule pas chaque visiteur toutes les statistiques.

Un exemple d'utilisation est disponible dans:
/sample_stats_in/sample_stats_in_utf8.php
/sample_stats_in/sample_stats_in_iso.php
-----------------------------------------

English
stats_in.php allows the publicly display  the sections of your statistics desired in the pages of your website
The files cache is triggered by visitors according of value of "$time_update_cache". 
This solution does not recalculate every visitor all the statistics.

An example of usage is available in 
/sample_stats_in/sample_stats_in_utf8.php
/sample_stats_in/sample_stats_in_iso.php
*/
//######################################################## Configuration #######################################################################
	
	// ---------------- Ne doit pas être appelé directement -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/stats_in.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------


	$public_StatsIn = false; 	//true pour accès public, si false --> Affiche Vous n'êtes pas autorisé à visiter cette section
								//true for public access, if false --> You are not allowed to visit this section

	$included_in_page_charset = 'iso-8859-1'; //utf-8, iso-8859-1 Charset des pages qui appellent stats_in
											  // charset pages that calls stats_in
											  
	$display_adword_links = 'false'; //'true' --> Affiche les liens Adwords réseau de contenu si Google adwords est utilisé, false --> non affichés
								  	 //true -> Displays links AdWords content network if Google adwords is used, false -> not displayed

	$not_display_keyword_pos = true; // true --> N'affiche pas la position des mots clé
									 // true --> Do not display keyword position
	$StatsIn_in_prot_dir = 'N'; 	// N, Y (Default N) Si AllMyStats est dans un répertoire protégé par .htaccess, les images ne peuvent être affichées. Icônes et graphiques 
									// If AllMyStats is in a protected directory with .htaccess, the images can be displayed. Icons and graphics

	$html_body = false; //Si nouvelle page --> true - Si intégré dans une page existante --> false
						//If new page --> true - If integrated into an existing page --> false
						
//----------------------------------- style Stats in (only) ---------------------
	$txt12pxbold = 'font-family: Verdana, Arial, sans-serif; font-weight: bold; font-size: 12px;';
	$txt11px = 'font-family: Verdana, Arial, sans-serif; font-size: 11px;';

//--------------------------- style table (for Admin && Stats in) ---------------
	$table_border_CSS = 'border: 1px solid #000000; border-collapse: collapse; margin-left: auto; margin-right: auto; background-color: #99CCFF; width: 570px;';
	$table_frame_CSS = 'border: 0px solid #000000; border-collapse: collapse; margin-top: 3px; background-color: #99CCFF; width: 100%;'; 
	$table_data_CSS = 'border: 1px solid #000000; border-collapse: collapse; margin-top: 3px; margin-bottom: 3px; margin-left: 3px; margin-right: 3px; background-color: #FAFAFA; color: #000000; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal; width: 99%;'; //width: 100%; margin-left: auto; margin-right: auto;
	$td_data_CSS = 'border-width: 1px 1px 0px 0px; border-color: #000000;  border-style: solid; border-collapse: collapse; padding: 3px; font-family: Verdana, Arial, sans-serif; font-size: 10px;'; 
	$table_title_CSS = 'border: 0px solid #000000; font-family: Verdana, Arial, sans-serif; font-size: 14px; font-weight: bold; text-align:center; vertical-align:middle;';

//--------------------------- Style Graph (for Admin && Stats in)---------------
	$page_view = 'color: #2000FF; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal;';
	$style_visits = 'color: #8F0080; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal;'; 
	$td_txt_CSS = 'color: #000000; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal;';
//------------------------------------------------------------------------------

//#############################################################################################################################################
//#############################################################################################################################################

//dirname(__FILE__); //Path of file same if in include
require(dirname(__FILE__).'/config_allmystats.php');
require(dirname(__FILE__).'/includes/mysql_tables.php');
require(dirname(__FILE__).'/includes/languages/'.$langue.'/main.php');
require(dirname(__FILE__).'/includes/functions/general.php');
require(dirname(__FILE__).'/version.php');

		if(!$display_top_keywords) { $display_top_keywords = 1000; }
		
		mysql_connect($mysql_host,$mysql_login,$mysql_pass);
		mysql_select_db($mysql_dbnom);
		mysql_query("SET NAMES 'utf8'"); 

		$month_year_displayed = trim(date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))))); 

		//#####################################################################################################
		$mois_Visualise = $month_year_displayed;
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
		if ($display_keywords_by_day) {
		$suffixe .= "kwd";
		}
		if ($display_page_view) {
		$suffixe .= "pv";
		}
		if ($display_org_geo) {
		$suffixe .= "ge";
		}
		if ($display_operating_system) {
		$suffixe .= "os";
		}
		if ($display_browsers) {
		$suffixe .= "na";
		}
		if ($display_bots) {
		$suffixe .= "ro";
		}
		
		$cache_mois_in = "";


	if ($included_in_page_charset == 'iso-8859-1') {						
		$MsgNotAllowedThisSection = utf8_decode(MSG_NOT_ALLOWED_THIS_SECTION);
	} else { //utf-8
		$MsgNotAllowedThisSection = MSG_NOT_ALLOWED_THIS_SECTION;						
	} 

	if($public_StatsIn == false )	{
		echo '<br /><br /><center><strong>'.$MsgNotAllowedThisSection.'</strong></center><br />';
		exit;
	} else {
		if ( date('YmdHi') > date("YmdHi", @filemtime(dirname(__FILE__)."/cache/stats_in/stats_".$site."_".$format_date_file_name."-".$suffixe.".php")) + $time_update_cache || isset($archive_encours) ){
			//Todo mise en cache
			$action_cache_mois_in = true;
			msg_temporaire('<div align="center" id="cache"><strong><big><font color="#FF0000">'.MSG_OPERATION_IN_PROGRESS.'</font></big></strong></div>');
		} else {
			//Pas de mise en cache à faire
			$action_cache_mois_in = false;
			include_once(dirname(__FILE__)."/cache/stats_in/stats_".$site."_".$format_date_file_name."-".$suffixe.".php");
		}
	}
		//-------------------------------------------------------------------
		//############################################################# End init ###########################################

if ($action_cache_mois_in) {

/*
				//--------- Pour éviter de lancer le script par plusieurs visiteurs ------------
				 $nbSlashes = substr_count($_SERVER['SCRIPT_NAME'], '/'); // on compte le nombre total de slashes contenu dans le lien relatif du fichier courant
				 $nbSlashes --; // on ne compte pas le slash de la racine (placé au début du lien relatif)
				 $root_site = ''; // on initialise la remontée dans l'arborescence
				 for($i = 0; $i < $nbSlashes; $i++)
				 {
					 $root_site .= '../';
				 }
				//---------------------------------------
				//Supprime 1er "/" de $path_allmystats_abs Important si $root_site = ""
				$path_allmystats_rel = substr($path_allmystats_abs, 1);
				$Fnm = $root_site.$path_allmystats_rel."cache/stats_in/stats_".$site."_".$format_date_file_name."-".$suffixe.".php";
*/					
				$Fnm = dirname(__FILE__)."/cache/stats_in/stats_".$site."_".$format_date_file_name."-".$suffixe.".php";

				//$time = time() - 30; //ajoute 30 seconde //date du fichier = + 30 secondes à la date actuelle 
				@touch($Fnm); // modifie date du fichier sur la date actuelle
				//-----------------------------------------------------------------------------

	// -------------------------------------- Affichage -----------------------------------------------------------------------
	
	if ($included_in_page_charset == 'iso-8859-1') {						
		$show_footer = '<div style="'.$txt11px.' text-align: center;"><a href="http://allmystats.wertronic.com" target="_blank">AllMyStats '.VERSION.'</a> '.utf8_decode(MSG_DEVELOPED_BY).' <a href="http://www.wertronic.com" target="_blank">Wertronic</a></div><br />';
	} else { //utf-8
		$show_footer = '<div style="'.$txt11px.' text-align: center;"><a href="http://allmystats.wertronic.com" target="_blank">AllMyStats '.VERSION.'</a> '.MSG_DEVELOPED_BY.' <a href="http://www.wertronic.com" target="_blank">Wertronic</a></div><br />';
	} 

	$show_cumul_page = $show_footer;

	//###################################################################################################################
	//										GRAPH DU MOIS PAR JOUR
	//###################################################################################################################

	if ($display_graph_by_day && $StatsIn_in_prot_dir <> 'Y') { 
		// ################## GRAPH DU MOIS PAR JOUR ######################
		require(dirname(__FILE__).'/includes/filename.php');	
		$mois = $month_year_displayed;
		include(FILENAME_GRAPH_MONTH_DAYS);
		$show_cumul_page .= $graph_byday;
		$graph_byday ="";
		// ################################################################
	} elseif ($display_graph_by_day && $StatsIn_in_prot_dir == 'Y') { 
		$show_cumul_page .= '<table><tr><td>If stats_in is in a protected directory, it is not possible to display "Chart"</td></tr></table><br /><br />';
	}
 // End if ($display_graph_by_day) {

	//############################################################################################
	//								KEYWORDS BY DAY
	//############################################################################################	

	if ($display_keywords_by_day) {
		$first_limit_keywords = $display_top_keywords;
		$val_limit_keywords = $first_limit_keywords+1;
		$limit_keywords = 'LIMIT '.$val_limit_keywords;		
		$when_day = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));		
		include_once(FILENAME_KEYWORDS_REFERERS_DAY);
	} 

	//############################################################################################
	//									KEYWORDS MONTHLY
	//############################################################################################	

	if ($display_keywords) {
		$first_limit_keywords = $display_top_keywords;
		$val_limit_keywords = $first_limit_keywords+1;
		$limit_keywords = 'LIMIT '.$val_limit_keywords;		
		include('keywords_referers_month.php');
	} 

	//############################################################################################
	//						Pages visitées
	//############################################################################################
if ($display_page_view) {
		
	$result = mysql_query("select count(*) as nb_diff_pages, sum(visited_pages) as total_pages from ".TABLE_DAYS_PAGES." where date like '%".$month_year_displayed."'");
	$row = mysql_fetch_array($result);
	$pages_cumul = $row['total_pages']; // Total Pages

	$result = mysql_query("select pages_name, sum(visitors) as total_visitors, sum(visited_pages) as total_pages from ".TABLE_DAYS_PAGES." where date like '%".$month_year_displayed."' GROUP BY pages_name order by total_pages DESC, pages_name ASC");
	$differents_pages = mysql_num_rows($result); // Pages différentes
		
	$show_cumul_page .= '
	<table style="'.$table_border_CSS.'">
		<tr>
		<td>
			<table style="'.$table_frame_CSS.'">
			<tr>
			  <td style="width:5%; white-space:nowrap;">'; //width: %; in px ne fonctionne pas;
				  if ($StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
					$show_cumul_page .= '
					&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_visited_pages.gif" height="32px" alt="'.MSG_VISITED_PAGES.' ('.MSG_EXCLUDED_BOTS.')'.$month_year_displayed.'" title="'.MSG_VISITED_PAGES.' ('.MSG_EXCLUDED_BOTS.')'.$month_year_displayed.'">';
				  }	
			  
			  $show_cumul_page .= '
			  </td>
			  <td style="'.$table_title_CSS.'">'.MSG_VISITED_PAGES.' ('.MSG_EXCLUDED_BOTS.') '.$month_year_displayed.'<br>
			  	<font style="font-weight:lighter;"><small>'.MSG_TOTAL_DIFFERENT_PAGES.': '.$differents_pages.'</small></font>
			  </td>
			</tr>
	
			<tr>
			  <td colspan="2">
				<table style="'.$table_data_CSS.'">
				  <tr>
					<th style="'.$td_data_CSS.' text-align: center;\">'.MSG_PAGE.'</th>
					<th style="'.$td_data_CSS.' text-align: center;\">'.MSG_VISITORS.'</th>
					<th style="'.$td_data_CSS.' text-align: center;\">'.MSG_VISITED_PAGES.'</th>
					<th style="'.$td_data_CSS.' text-align: center;\">'.MSG_PAGES_PERCENTAGE.'</th>
				  </tr>';
	
				while($row=mysql_fetch_array($result)){
					$nb = $row['total_pages']*100; 
					if($pages_cumul != 0){
						$percents = bcdiv($nb, intval($pages_cumul), 2);
				}

				if ($included_in_page_charset == 'iso-8859-1') {						
					$charset_pages_name = utf8_decode($row['pages_name']);
				} else { //utf-8
					$charset_pages_name = $row['pages_name'];						
				} 

				$show_cumul_page .= '
				<tr>
					<td style="'.$td_data_CSS.'">'.$charset_pages_name.'</td>
					<td style="'.$td_data_CSS.' text-align: center;">'.$row['total_visitors'].'</td>
					<td style="'.$td_data_CSS.' text-align: center;">'.$row['total_pages'].'</td>
					<td style="'.$td_data_CSS.' text-align: center;">'.$percents.'%</td></tr>';
			}
	
		$show_cumul_page .= "
		</table></td></tr></table></td></tr></table><br />";

	} // End de if ($display_page_view) {	
	
			//############################################################################################
			//						ORIGINE GEO MONTH
			//############################################################################################
		if ($display_org_geo && $StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
				
			//ORG GEO
			if(!function_exists('geoip_open')) { //To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in page)
				require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
			}
			if(!function_exists('geoip_country_name_by_code')) { //if geoip.inc previously declared in page but not geoip_add.inc (add for AllMySats)
				require_once(dirname(__FILE__).'/lib/geoip/geoip_add.inc');
			}

			$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);

			$result = mysql_query("select count(country) as visitors_by_country, sum(visits) as pages_by_country, country from ".TABLE_UNIQUE_VISITOR." where date like '%".$month_year_displayed."' GROUP BY country ORDER BY visitors_by_country DESC");
			$total_differents_countries = mysql_num_rows($result);
							
			$result_max_pages = mysql_query("select sum(visits) as pages_by_country from ".TABLE_UNIQUE_VISITOR." where date like '%".$month_year_displayed."' GROUP BY country ORDER BY pages_by_country DESC");
			$row_max_pages = mysql_fetch_array($result_max_pages); //Car on tri mintenant sur lrs visiteurs donc 1er pas obligatoirement OK
			$indice = @bcdiv(1, ($row_max_pages['pages_by_country']/200), 3); //proportion en rapport au plus grand nb de pages visités
		
		$show_cumul_page .= '
		<table style="'.$table_border_CSS.'">
		  <tr>
			<td>
			  <table style="'.$table_frame_CSS.'">
					<tr>
					  <td style="width:5%; white-space:nowrap;">
							&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_geolocation.gif" height="32px" alt="'.MSG_TITLE_ORG_GEO.'" title="'.MSG_TITLE_ORG_GEO.'">
					  </td>
					  
					  <td style="'.$table_title_CSS.'">'.MSG_TITLE_ORG_GEO.' ('.MSG_EXCLUDED_BOTS.')<br><small><font style="font-weight:lighter">'.MSG_TOTAL_DIFFERENT_COUNTRIES.': '.$total_differents_countries.'</font><small></td>
					</tr>
					<tr>
					  <td colspan="2">
						<table style="'.$table_data_CSS.'">
					<tr>
					   <th style="'.$td_data_CSS.' text-align: center;">'.MSG_COUNTRY.'</th>
					   <th style="'.$td_data_CSS.' text-align: center;">'.MSG_NB_VISITORS.'</th>
					   <th style="'.$td_data_CSS.' text-align: center;">'.MSG_VISITED_PAGES.'</th>
					 </tr>';
	
					@mysql_data_seek($result, 0); //reset($result) to 0;
					while($row = mysql_fetch_array($result)){
						if ($row['country'] == '') { 
							if ($included_in_page_charset == 'iso-8859-1') {						
								$Country = utf8_decode(MSG_ORIGIN_UNKNOWN);
							} else { //utf-8
								$Country = MSG_ORIGIN_UNKNOWN;						
							} 
						} else {
							if ($row['country'] == 'Kazakhstan'){  // with an older version of geoip.inc that contains the error: Kazakstan for Kazakhstan
								$record_code = 'kz';  // Function ajouté dans geoip.inc) { $record_name = 'Kazakhstan'; } 
							} else {
								//Country flags
								$record_code = geoip_country_name_by_code($handle, $row['country']); // Function ajouté dans geoip.inc
							}

							if ($included_in_page_charset == 'iso-8859-1') {						
								$charset_country_name = utf8_decode($row['country']);
							} else { //utf-8
								$charset_country_name = $row['country'];						
							} 

							if (file_exists(dirname(__FILE__)."/images/flags/".strtolower($record_code).".png")) { 
								$Country = "<img src=\"".$path_allmystats_abs."images/flags/".strtolower($record_code).".png\" height=\"10\" width=\"14\" alt=\"".$charset_country_name."\" title=\"".$charset_country_name."\"> ".$charset_country_name;
							} else {
								$Country = $charset_country_name;
							}
						}
						
					$show_cumul_page .= "
					<tr>
						<td style=\"".$td_data_CSS." white-space: nowrap;\"> 
						<b>".$Country."</b>
						</td>
						<td style=\"".$td_data_CSS." white-space: nowrap;\">
						<img src=\"".$path_allmystats_abs."images/histo-h.gif\" width=\""; 
						$hauteur = @bcmul($row['visitors_by_country'] , $indice, 2);  
					$show_cumul_page .= $hauteur .	"\" height=\"8\" alt=\"".$row['visitors_by_country']."\" title=\"".$row['visitors_by_country']."\">".$row['visitors_by_country'].
						"</td>
						<td style=\"".$td_data_CSS." white-space: nowrap;\">
						<img src=\"".$path_allmystats_abs."images/histo-h.gif\" width=\""; 
						$hauteur = bcmul($row['pages_by_country'], $indice, 2);  
					$show_cumul_page .= $hauteur."\" height=\"8\" alt=\"".$row['pages_by_country']."\" title=\"".$row['pages_by_country']."\">".$row['pages_by_country'].
						"</td>
						</tr>";	
					}
					
					@geoip_close($handle); //GEOIP n'est plus appelé après --> close $handle
		
		$show_cumul_page .= '</table></td></tr></table></td></tr></table><br />';
		
		
		} elseif ($display_org_geo && $StatsIn_in_prot_dir == 'Y') { // End if ($display_org_geo) {
			$show_cumul_page .= '<table><tr><td>If stats_in is in a protected directory, it is not possible to display "geographical origin"</td></tr></table><br /><br />';
		}


		//############################################################################################
		//			display_operating_system display_browsers display_bad_user_agent
		//############################################################################################

	if( ($display_operating_system || $display_browsers || $display_bad_user_agent) && $StatsIn_in_prot_dir <> 'Y'){
		require_once("includes/filename.php");
		$dislpay_button_tool_bots = "false"; //Important "false" entre guillemets car affiche le bouton si  $dislpay_button_tool_bots = "";
		?>
		<table width="90%" border="0" align="center">
		  <tr>
			<td align="center"><?php  
				$when_date = $month_year_displayed; // date d/m/Y or m/Y
				$show_page_os_nav_robots = '';
				include(dirname(__FILE__).'/'.FILENAME_DISPLAY_OS_BROWSER);	 
				$show_cumul_page .= $show_page_os_nav_robots;
				?>
			</td>
		  </tr>
		</table>
		<?php
	} elseif (($display_operating_system || $display_browsers || $display_bad_user_agent) && $StatsIn_in_prot_dir == 'Y') { 
		$show_cumul_page .= '<table><tr><td>If stats_in is in a protected directory, it is not possible to display "OS, Browser, Bad User Agent"</td></tr></table><br /><br />';
	}

		//############################################################################################
		//			display_bots
		//############################################################################################
			if ($display_bots == true) {	
				$dislpay_button_tool_bots = "false"; //Important "false" entre guillemets car affiche le bouton si  $dislpay_button_tool_bots = "";
					?>
					<table width="90%" border="0" align="center">
					  <tr>
						<td align="center">
							<?php  
							$when_date = $month_year_displayed;
							$show_page_os_nav_robots = '';
							include_once('display_bots.php'); //Calcul et affichage des tableaux
							$show_cumul_page .= $show_page_os_nav_robots;
							?>
						</td>
					  </tr>
					</table>
					<?php
			}
		//###############################################################################################
		//########################################## AFFICHAGE ##########################################
		
			//echo $show_cumul_page;	// Affichage des tableaux

			message($msg , "");		//efface le message Patientez
	
			//--------- Footer -------------------
			//echo $show_footer;
			//------------------------------------					

		//###############################################################################################	
		//###############################################################################################
		
	########################################## Mise en cache du mois encours actuel #######################################
	
			$mois_actuelle = date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			$mois_Visualise = $month_year_displayed;
		
			$Mois_Annee_visualise = explode("/", $mois_Visualise);
			$mois_visualise = $Mois_Annee_visualise[0]; // mois
			$annee_visualise = $Mois_Annee_visualise[1]; //année
			
			$Mois_Annee_actuelle = explode("/", $mois_actuelle);
			$mois_actuelle = $Mois_Annee_actuelle[0];
			$annee_actuelle = $Mois_Annee_actuelle[1];
	
			if ($action_cache_mois_in || isset($archive_encours) ) { //on met en cache
			
				if (!is_dir(dirname(__FILE__)."/cache")) {
					mkdir (dirname(__FILE__)."/cache");
				}

				if (!is_dir(dirname(__FILE__)."/cache/stats_in")) {
					mkdir (dirname(__FILE__)."/cache/stats_in");
				}

				
				$Mois_Annee = explode("/", $mois_Visualise);
				$format_date_file_name = $Mois_Annee[1].'-'.$Mois_Annee[0];
				
				$Fnm = dirname(__FILE__)."/cache/stats_in/stats_".$site."_".$format_date_file_name."-".$suffixe.".php";

				if (!$inF = @fopen($Fnm,"w")){
					echo "Erreur create file<br />";
				}

				if ($html_body) {
					$page_html = 
		'<?php
		//###############################################################################################
		//					Read Protection
		//###############################################################################################
		//Si le fichier est hors du répertoire cache et se trouve dans un répertoire quelconque du site
		// Accès public par défaut
		if (is_dir(dirname(__FILE__)."/cache")) { // On test les droits d\'accès au fichier
			require (dirname(__FILE__)."/config_allmystats.php");
	
			if(!$public_StatsIn)	{
				echo "<br /><br /><center><strong>".$MsgNotAllowedThisSection."</center></strong><br />";
				exit;
			}
		}
		//###############################################################################################
		//###############################################################################################
		?>
					
						<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 						"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml">
						<head>
						<title>AllMyStats - '. $site.' - '.$format_date_file_name.'</title>
				
						<style type="text/css">
						<!--
						.StyleBleuClair {color: #006DB7}
						.NoirMoyen {font-size: 120%; color: #333333; }
						.plop {
						   text-decoration: blink;
						}
						.Noirclair {
							color: #2D2D2D;
							font-weight: bold;
						}
						.Style41 {color: #003C64; font-size: 110%; }
						.Style63 {color: #009900}
						.Style65 {color: #003C64; font-size: 110%; font-weight: bold; }
						.Style61 {color: #006600;
							font-weight: bold;
						}
						.Style56 {font-size: 14px;
							color: #002779;
							font-weight: bold;
						}
						-->
						</style>
						<meta http-equiv="Content-Type" content="text/html; charset='.$included_in_page_charset.'">
						</head>
						<body>
							<table style="align: center; width:100%; border: 0px;">
								<tr>
									<td style="'.$txt12pxbold.' text-align: center;">'.MSG_WEBSITE_STATISTICS.' '.$site." - ".MSG_MONTH." ".$mois_Visualise.'<br /><br /></td>
								</tr>
								<tr>
									<td style="'.$txt12pxbold.' text-align: center;">'.MSG_LAST_UPDATE.' '.date('d/m/Y - H:i').'<br /><br /></td>
								</tr>
								<tr>
									<td>'.$show_cumul_page . $show_footer.'</td>
								</tr>
							</table>
						</body></html>';
				} else {
					$page_html = 
		'<?php
		//###############################################################################################
		//					Read Protection
		//###############################################################################################
		//Si le fichier est hors du répertoire cache et se trouve dans un répertoire quelconque du site
		// Accès public par défaut
		if (is_dir(dirname(__FILE__)."/cache")) { // On test les droits d\'accès au fichier
			require (dirname(__FILE__)."/config_allmystats.php");
	
			if(!$public_StatsIn)	{
				echo "<br /><br /><center><strong>".$MsgNotAllowedThisSection."</center></strong><br />";
				exit;
			}
		}
		//###############################################################################################
		//###############################################################################################
		?>
				
						<table style="align: center; width:100%; border: 0px;">
							<tr>
								<td style="'.$txt12pxbold.' text-align: center;">'.MSG_WEBSITE_STATISTICS.' '.$site." - Mois: ".$mois_Visualise.'</td>
							</tr>
							<tr>
								<td style="'.$txt12pxbold.' text-align: center;">'.MSG_LAST_UPDATE.' '.date('d/m/Y - H:i').'<br /><br /></td>
							</tr>
							<tr>
								<td>'.$show_cumul_page . $show_footer.'</td>
							</tr>
						</table>';
				}

				if(!@fwrite($inF,$page_html)){
					echo "Erreur write file<br />";
				}

				if(!@fclose($inF)){
					echo "Erreur close file";
				}

			} //End if ($action_cache_mois_in || isset($archive_encours) )

			include_once(dirname(__FILE__)."/cache/stats_in/stats_".$site."_".$format_date_file_name."-".$suffixe.".php");

} // End if ($action_cache_mois_in) {

//-------------------------------------------------------------------------------------------
//#################################################################################################
//										Functions
//#################################################################################################

function msg_temporaire ($temp_msg) {
 	flush();
 	echo $temp_msg; ?>
	<script type="text/javascript">
	document.getElementById("cache").style.visibility = "visible";
	</script><?php
 	flush();
}
 
function message($msg, $title){ ?> 
	<script type="text/javascript">
 		document.getElementById("cache").style.visibility = "hidden";
	</script><?php
}

function copy_dir($dir_to_copy,$dir_paste) {
	// On vérifie si $dir_to_copy est un dossier
	if (is_dir($dir_to_copy)) {
		// Si oui, on l'ouvre
		if ($dh = opendir($dir_to_copy)) {     
			// On liste les dossiers et fichiers de $dir_to_copy
			while (($file = readdir($dh)) !== false) {
				// Si le dossier dans lequel on veut coller n'existe pas, on le créé
				if (!is_dir($dir_paste)) {
					mkdir ($dir_paste, 0777);
				}
						   
				// S'il s'agit d'un dossier, on relance la fonction récursive
				if(is_dir($dir_to_copy.$file) && $file != '..'  && $file != '.') {				
					copy_dir ( $dir_to_copy.$file.'/' , $dir_paste.$file.'/' );
				} elseif ($file != '..'  && $file != '.') { // S'il sagit d'un fichier, on le copue simplement
					copy ( $dir_to_copy.$file , $dir_paste.$file );
				}
			}
			// On ferme $dir_to_copy
			closedir($dh);
		}
	}       
}

?>
