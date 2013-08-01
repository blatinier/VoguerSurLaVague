<?php
/*
  -------------------------------------------------------------------------
 AllMyStats V1.80 - Statistiques site web - Web traffic analysis
 -------------------------------------------------------------------------
 Copyright (C) 2008 - 2013 - Herve Seywert
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------
*/
	
	// ---------------- Should not be called directly -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/stats_in.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

require(dirname(__FILE__).'/config_stats_in.php');

// PHP5.4 Suppress DateTime warnings (if not set in php.ini) => date_default_timezone_set -> UTC
if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) {
	date_default_timezone_set(@date_default_timezone_get());
}

//dirname(__FILE__); //Path of file same if in include
require(dirname(__FILE__).'/config_allmystats.php');
require(dirname(__FILE__).'/includes/filename.php');
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

?>

<?php
	if($public_StatsIn == false )	{
		echo '<br /><br /><table  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
		  <tr>
			<td>
				<center>Stats_in<br /><strong>MSG_NOT_ALLOWED_THIS_SECTION</strong></center>
			</td>
		  </tr>
		</table><br />';
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

?>	

<?php
		//-------------------------------------------------------------------
		//############################################################# End init ###########################################

if ($action_cache_mois_in) {

	$Fnm = strtolower(dirname(__FILE__)."/cache/stats_in/stats_".$site."_".$format_date_file_name."-".$suffixe.".php");

	//$time = time() - 30; //ajoute 30 seconde //date du fichier = + 30 secondes à la date actuelle 
	@touch($Fnm); // modifie date du fichier sur la date actuelle

	// -------------------------------------- Affichage -----------------------------------------------------------------------
	
	if ($included_in_page_charset == 'iso-8859-1') {						
		$show_footer = '<div style="'.$txt11px.' text-align: center;"><a href="http://allmystats.wertronic.com" target="_blank">AllMyStats '.VERSION.'</a> '.utf8_decode(MSG_DEVELOPED_BY).' <a href="http://www.wertronic.com" target="_blank">Wertronic</a></div>';
	} else { //utf-8
		$show_footer = '<div style="'.$txt11px.' text-align: center;"><a href="http://allmystats.wertronic.com" target="_blank">AllMyStats '.VERSION.'</a> '.MSG_DEVELOPED_BY.' <a href="http://www.wertronic.com" target="_blank">Wertronic</a></div>';
	} 

	$show_cumul_page = '';

	//###################################################################################################################
	//										GRAPH DU MOIS PAR JOUR
	//###################################################################################################################

	if ($display_graph_by_day && $StatsIn_in_prot_dir <> 'Y') { 
		// ################## GRAPH DU MOIS PAR JOUR ######################
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
		$small_limit_keywords = $display_top_keywords;
		$val_limit_keywords = $small_limit_keywords+1;
		$limit_keywords = 'LIMIT '.$val_limit_keywords;		
		$when_day = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));		
		include_once(FILENAME_KEYWORDS_REFERERS_DAY);
	} 

	//############################################################################################
	//									KEYWORDS MONTHLY
	//############################################################################################	

	if ($display_keywords) {
		$small_limit_keywords = $display_top_keywords;
		$val_limit_keywords = $small_limit_keywords+1;
		$limit_keywords = 'LIMIT '.$val_limit_keywords;		
		include_once(FILENAME_KEYWORDS_REFERERS_MONTH);
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
					<td style="'.$td_data_CSS.' text-align: left; vertical-align: top;">'.$charset_pages_name.'</td>
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
						<td style=\"".$td_data_CSS." text-align: left; vertical-align: top; white-space: nowrap;\"> 
						<b>".$Country."</b>
						</td>
						<td style=\"".$td_data_CSS." text-align: left; vertical-align: top; white-space: nowrap;\">
						<img src=\"".$path_allmystats_abs."images/histo-h.gif\" width=\""; 
						$hauteur = @bcmul($row['visitors_by_country'] , $indice, 2);  
					$show_cumul_page .= $hauteur .	"\" height=\"8\" alt=\"".$row['visitors_by_country']."\" title=\"".$row['visitors_by_country']."\">".$row['visitors_by_country'].
						"</td>
						<td style=\"".$td_data_CSS." text-align: left; vertical-align: top; white-space: nowrap;\">
						<img src=\"".$path_allmystats_abs."images/histo-h.gif\" width=\""; 
						$hauteur = @bcmul($row['pages_by_country'], $indice, 2);  
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

	if($display_bad_user_agent) {
		$when_date = $month_year_displayed; // date d/m/Y or m/Y
		$display_bad_user_agent = true;
		$display_bads_agents = '';
		include(FILENAME_DISPLAY_BAD_AGENTS);
		$show_cumul_page .= $display_bads_agents;		
	}

	if( ($display_operating_system || $display_browsers) && $StatsIn_in_prot_dir <> 'Y'){
		$dislpay_button_tool_bots = "false"; //Important "false" entre guillemets car affiche le bouton si  $dislpay_button_tool_bots = "";
		$when_date = $month_year_displayed; // date d/m/Y or m/Y
		$show_page_os_nav_robots = '';
		include(dirname(__FILE__).'/'.FILENAME_DISPLAY_OS_BROWSER);	 
		$show_cumul_page .= $show_page_os_nav_robots;
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
			if(!isset($msg)) { $msg = ''; }
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
				
				$Fnm = strtolower(dirname(__FILE__)."/cache/stats_in/stats_".$site."_".$format_date_file_name."-".$suffixe.".php");

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
								<td>
									<div align="center">
									<table style="'.$table_border_CSS.'">
										<tr>
										  <td style="text-align: center;" >'
										  	.$show_footer;
										  	if (!$tagcloud_optimized) {
												$page_html .= MSG_WEBSITE_STATISTICS.' '.$site; 
											}											
											
											$page_html .= "
											- Mois: ".$mois_Visualise.'
											<br />'.MSG_LAST_UPDATE.' '.date('d/m/Y - H:i').'
										  </td>
										 </tr>
									</table>
									</div>
								</td>
							</tr>
							<tr>
								<td>'
								.$show_cumul_page.'								
									<table style="'.$table_border_CSS.'">
										<tr>
										  <td style="text-align: center;" >'
										  	.$show_footer.'
										  </td>
										 </tr>
									</table>								
								</td>
							</tr>
						</table>
						</body>
						</html>';
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
								<td>
									<div align="center">
									<table style="'.$table_border_CSS.'">
										<tr>
										  <td style="text-align: center;" >'
										  	.$show_footer;
										  	if (!$tagcloud_optimized) {
												$page_html .= MSG_WEBSITE_STATISTICS.' '.$site; 
											}											
											
											$page_html .= "
											- Mois: ".$mois_Visualise.'
											<br />'.MSG_LAST_UPDATE.' '.date('d/m/Y - H:i').'
										  </td>
										 </tr>
									</table>
									</div>
								</td>
							</tr>
							<tr>
								<td>'
								.$show_cumul_page .'
									<table style="'.$table_border_CSS.'">
										<tr>
										  <td style="text-align: center;" >'
										  	.$show_footer.'
										  </td>
										 </tr>
									</table>
								</td>
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
