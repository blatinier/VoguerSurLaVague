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
*/
	// ---------------- Ne doit pas être appelé directement -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/monthly_archives_edit.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

//----------------------------------------------------------
// TEST debug
$time_test = false; //affiche les temps d'exécution
// ARCHIVAGE - SI true Ne delete pas le mois des tables MySQL et simule un archivage sur le mois en cours avec écriture du fichier en /cache
$archivage_test = false;
//----------------------------------------------------------
//---------- Config Limit affichage ------------------------
//Limite pour l'archivage et 1ere edition en cours
$first_limit_keywords = '50'; //for each search engine
$first_limit_pages = '100'; //200
		
//Limite affichage liste complète
$complete_list_limit_keywords = '2000';
$complete_list_limit_pages = '300';
		
//Limite archives
$archives_limit_keywords = '1000';
$archives_limit_pages = '400';
include_once('includes/display_keys_pages_limit.php');
//----------------------------------------------------------


//------------------------------------------------------------------------------
	//Only monthly_archives_edit.php
	$txt_style = 'style="font-family: Verdana, Arial, sans-serif; font-size: 11px"';

		//Init Org geo
		if(!function_exists('geoip_open')) { //To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in if in page
			require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
		}

		//---------------------------------------------------------------------------

		msg_waiting_hidden("waiting");
		?><div align="center" id="waiting"><strong><big><font color="#FF0000">Opération en cours, veuillez patienter...</font></big></strong></div><?php
		msg_waiting_hidden("waiting");

		$mois = $_POST["mois"];
		//Date today
		$month_year_today = date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$ex_month_year_today = explode("/", $month_year_today);
		$year_today = trim($ex_month_year_today[1]);
		$month_today = trim($ex_month_year_today[0]);
			
		// Date displayed
		$mois = $_POST["mois"];
		$month_year_displayed =  $mois; // mm/Y
		$ex_month_year_displayed = explode("/", $month_year_displayed);
		$year_displayed = trim($ex_month_year_displayed[1]);
		$month_displayed = trim($ex_month_year_displayed[0]);
		
		$format_date_file_name = $year_displayed.'-'.$month_displayed;
	
		if ($archivage_test) {	//TEST - Archivage
			$month_year_today = '12/2020';
			$ex_month_year_today = explode("/", $month_year_today);
			$year_today = trim($ex_month_year_today[1]);
			$month_today = trim($ex_month_year_today[0]);
		}
		
		//----------------------------------------------------
		//display msg waiting
		if ($year_today.$month_today > $year_displayed.$month_displayed) { 
			msg_waiting_visible("waiting");
		}	
		//----------------------------------------------------

	//------------------------------------------------------------------------------------------------------
	//--------------- If archivage --> open file & prepare write file archive ------------------------------
	
	if ($year_today.$month_today > $year_displayed.$month_displayed) { 
			
		$time_test = true;
		$dislpay_button_tool_bots = 'false'; //Important 'false' entre guillemets car affiche le bouton si  $dislpay_button_tool_bots = ""; 

		$val_archives_limit_keywords = $archives_limit_keywords+1;
		$limit_pages = 'LIMIT '.$archives_limit_pages; //Pour archivage (On ne peut pas tout archiver si > pages différentes par moteur de recherche
		$switch_short_complete_list = false;
		$limit_keywords = 'LIMIT '.$val_archives_limit_keywords; //Pour archivage (On ne peut pas tout archiver si > 5 000 mots clé différents par moteur de recherche
		$switch_short_complete_list = false;

		if($archivage_test) {
			$day_now = date('d',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			$Fnm = dirname(__FILE__)."/cache/stats_".$site."_".$format_date_file_name."-".$day_now."_archiving_test.php";
		} else {
			$Fnm = dirname(__FILE__)."/cache/stats_".$site."_".$format_date_file_name.".php";		
		}

		$inF = fopen($Fnm, "w");
		
		$show_footer = '<div align="center" '.$txt_style.'><a href="http://allmystats.wertronic.com" target="_blank">AllMyStats '.VERSION.'</a> '.MSG_DEVELOPED_BY.' <a href="http://www.wertronic.com" target="_blank">Wertronic</a></div><br />';
		
		$page_html = 
		'<?php
		@header(\'Content-Type: text/html; charset=utf-8\');
		//###############################################################################################
		//									Read Protection
		//###############################################################################################
		//Si le fichier est hors du répertoire cache et se trouve dans un répertoire quelconque du site
		// Accès public par défaut
		if (is_dir("../cache")) { // On test les droits d\'accès au fichier
			include_once("../application_top.php");
			require ("../config_allmystats.php");
	
			if( ($user_login!=$_SESSION["userlogin"] || $passwd!=$_SESSION["userpass"]) && !$public_StatsIn)	{
				header("location: ../'.FILENAME_INDEX_FRAME.'");
			}
		}
		//###############################################################################################
		//###############################################################################################
		?>
		<html>
		<head>
		<title>AllMyStats - '. $site.' - '.$format_date_file_name.'</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../stylesheet.css">
		</head>
		<body><table width="100%" border="0" align="center">
		<tr>
			<td align="center"><big><strong>Archives site: '.$site." - Date: ".$month_year_displayed.'</strong></big><br></td>
		</tr>
		<tr>
			<td align="center">'.$show_footer;
		
		fwrite($inF, $page_html);
	}

	//#################################################################################################################
	//#################################################################################################################
	// ################## GRAPH DU MOIS PAR JOUR ######################

	//---------- AFFICHAGE OR ARCHIVAGE -----------------
	include(FILENAME_GRAPH_MONTH_DAYS);
	if ($year_today.$month_today == $year_displayed.$month_displayed) { 
		echo $graph_byday;
	} elseif ($year_today.$month_today > $year_displayed.$month_displayed) {
		fwrite($inF, $graph_byday);
	}
	$graph_byday = '';
	//--------------------------------------------------- 

	###################################################################
	// ################## KEYWORDS MONTHLY ############################

	//---------- AFFICHAGE --- OR ARCHIVAGE ------------
	$show_cumul_page = '';
	if ($year_today.$month_today == $year_displayed.$month_displayed) { 
		$do_archives = false;
		include('keywords_referers_month.php');
		echo $show_cumul_page;
	} elseif ($year_today.$month_today > $year_displayed.$month_displayed) {
		$do_archives = true;
		include('keywords_referers_month.php');
		fwrite($inF, $show_cumul_page);
	}
	$show_cumul_page = '';
	//--------------------------------------------------- 

	//##################################################################
	//			Pages visitées A partir de TABLE_DAYS_PAGES
	//				TODO Ajouter user agent inconnus
	//##################################################################
	if($time_test == true) {
		$start = (float) array_sum(explode(' ',microtime())); 
	}
		 
	$result = mysql_query("select sum(visited_pages) as total_pages from ".TABLE_DAYS_PAGES." where date like '%".$month_year_displayed."'");
	$row = mysql_fetch_array($result);
	$pages_cumul = $row['total_pages']; // Total Pages

	$result = mysql_query("select pages_name, sum(visitors) as total_visitors, sum(visited_pages) as total_pages from ".TABLE_DAYS_PAGES." where date like '%".$month_year_displayed."' GROUP BY pages_name order by total_pages DESC, pages_name ASC");
	$differents_pages = mysql_num_rows($result); // Pages différentes

	//Sort by visited pages
	//$result = mysql_query("select pages_name, sum(visitors) as total_visitors, sum(visited_pages) as total_pages from ".TABLE_DAYS_PAGES." where date like '%".$month_year_displayed."' GROUP BY pages_name order by total_pages DESC, pages_name ASC  ".$limit_pages."");
	//Sort by visitors
	$result = mysql_query("select pages_name, sum(visitors) as total_visitors, sum(visited_pages) as total_pages from ".TABLE_DAYS_PAGES." where date like '%".$month_year_displayed."' GROUP BY pages_name order by total_visitors DESC, pages_name ASC  ".$limit_pages."");

	$show_cumul_page .= "
	<table style=\"".$table_border_CSS."\">
	  <tr>
		<td>
		  <table style=\"".$table_frame_CSS."\">
			<tr>
			  <td style=\"width:5%; white-space:nowrap;\">
			  		&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/icon_visited_pages.gif\" height=\"32px\" alt=\"".MSG_VISITED_PAGES." (".MSG_EXCLUDED_BOTS.")".$month_year_displayed."\" title=\"".MSG_VISITED_PAGES." (".MSG_EXCLUDED_BOTS.")".$month_year_displayed."\">
			  </td>
			  <td style=\"".$table_title_CSS."\">". MSG_VISITED_PAGES." (".MSG_EXCLUDED_BOTS.")".$month_year_displayed."<br><small><font style=\"font-weight:lighter\">".MSG_TOTAL_DIFFERENT_PAGES.": ".$differents_pages."</small></font></td>

			  <td style=\"text-align: right;\">";	  

			if ($switch_short_complete_list && $differents_pages >= $first_limit_pages) {
			  $show_cumul_page .= "	 
				<form name=\"form_limi_pages\" method=\"post\" action=\"".FILENAME_INDEX_FRAME."\">
				  <input type=\"hidden\" name=\"type\" value=\"cumulpage\">
				  <input type=\"hidden\" name=\"mois\" value=\"".$month_year_displayed."\">
				  <input type=\"hidden\" name=\"limit_keywords\" value=\"".$limit_keywords."\">
				  <input type=\"hidden\" name=\"limit_pages\" value=\"".$limit_pages."\">
				  <input class=\"submit\" name=\"submit_limit_pages\" type=\"submit\" value=\"".$value_button_pages."\" alt=\"".$value_button_pages."\" title=\"".$value_button_pages."\">
				</form>";		  

				if ( ($differents_pages > $first_limit_pages && $value_button_pages == MSG_COMPLETE_LIST) || ($$differents_pages > $complete_list_limit_pages && $value_button_pages == MSG_SHORTLIST) ) {
					$show_cumul_page .= '<br />Limited pages to : '.$limit_pages.'&nbsp;&nbsp;&nbsp;';
				}
			} else {
				if ($differents_pages > $archives_limit_pages) {
					$show_cumul_page .= "<br />Limited pages to : ".$limit_pages;
				}
			}
			  
	 $show_cumul_page .= "	 
			  </td>
			  </tr>
			<tr>
			  <td colspan=\"3\">
				<table style=\"".$table_data_CSS."\">
				  <tr>
					<th style=\"".$td_data_CSS."\">".MSG_PAGE."</th>
					<th style=\"".$td_data_CSS." text-align: center;\">".MSG_VISITORS."</th>
					<th style=\"".$td_data_CSS." text-align: center;\">".MSG_VISITED_PAGES."</th>
					<th style=\"".$td_data_CSS." text-align: center;\">".MSG_PAGES_PERCENTAGE."</th></tr>";
	
				while($row = mysql_fetch_array($result)){
					$nb = $row['total_pages']*100; 
					if($pages_cumul != 0){
						$percents = bcdiv($nb, intval($pages_cumul), 2);
					}

					if(substr($row['pages_name'],0,6) == 'Prod: ') { //affichage couleur différenete pour le page produit oscommerce
						$show_cumul_page .= "
						<tr>
						<td style=\"".$td_data_CSS."\">
							<font color='#990000'>".$row['pages_name']."</font>
						</td>
						<td style=\"".$td_data_CSS." text-align: center;\">".$row['total_visitors']."</td>
						<td style=\"".$td_data_CSS." text-align: center;\">".$row['total_pages']."</td>
						<td style=\"".$td_data_CSS." text-align: center;\">".$percents."%
						</td>
						</tr>";
					} else { 
						$show_cumul_page .= "
						<tr>
						<td style=\"".$td_data_CSS."\">".$row['pages_name']."</td>
						<td style=\"".$td_data_CSS." text-align: center;\">".$row['total_visitors']."</td>
						<td style=\"".$td_data_CSS." text-align: center;\">".$row['total_pages']."</td>
						<td style=\"".$td_data_CSS." text-align: center;\">".$percents."%
						</td>
						</tr>";
					}
				}
	
	$show_cumul_page .= '
	</table></td></tr></table></td></tr></table><br />
	';

	if($time_test == true) {
		$end = (float) array_sum(explode(' ',microtime()));  
		echo '<pre>										Pages différentes Traitement : '.$differents_pages.' : '.sprintf("%.4f", $end-$start) . ' sec</pre>';  
	}
	//---------- AFFICHAGE --- OR ARCHIVAGE ------------
	if ($year_today.$month_today == $year_displayed.$month_displayed) { 
		echo $show_cumul_page;
	} elseif ($year_today.$month_today > $year_displayed.$month_displayed) {
		fwrite($inF, $show_cumul_page);
	}
	$show_cumul_page = '';
	//--------------------------------------------------- 

	//##################################################################
	// 					ORIGINE GEO MONTH
	//				TODO Ajouter user agent inconnus
	//##################################################################

	if($time_test == true) {
		$start = (float) array_sum(explode(' ',microtime())); 
	}

	$result = mysql_query("select count(country) as visitors_by_country, sum(visits) as pages_by_country, country from ".TABLE_UNIQUE_VISITOR." where date like '%".$month_year_displayed."' GROUP BY country ORDER BY visitors_by_country DESC ".$country_limit."");
	$total_differents_countries = mysql_num_rows($result);


	$result_max_pages = mysql_query("select sum(visits) as pages_by_country from ".TABLE_UNIQUE_VISITOR." where date like '%".$month_year_displayed."' GROUP BY country ORDER BY pages_by_country DESC");
	$row_max_pages = mysql_fetch_array($result_max_pages); //On tri mintenant sur lrs visiteurs donc 1er pas obligatoirement OK
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
			$nb_countries = 0;
			$first_show_countries = 15;
			while($row = mysql_fetch_array($result)){
				if ($row['country'] == '') { 
					$Country = MSG_ORIGIN_UNKNOWN;
					$Country_name_pie[] = 'Origin Unknown'; //MSG_ORIGIN_UNKNOWN; (utf-8 pb cyrillic)
				} else {
					//Country flags
					$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
					$record_code = geoip_country_name_by_code($handle, $row['country']); // Function ajouté dans geoip.inc
					@geoip_close($handle); //GEOIP n'est plus appelé après --> on ferme $handle

					if (file_exists("images/flags/".strtolower($record_code).".png")) { //"images/flags/" relative path else Warning: file_exists() [function.file-exists]: open_basedir restriction in effect.
						$Country = "<img src=\"".$path_allmystats_abs."images/flags/".strtolower($record_code).".png\" height=\"10\" width=\"14\" alt=\"".$row['country']."\" title=\"".$row['country']."\"> ".$row['country'];
					} else {
						$Country = $row['country'];
					}
					
					//Only $first_show_countries
					if ($nb_countries > $first_show_countries) {
						$Country_name_pie[$first_show_countries] = "Others";
					} else {
						$Country_name_pie[] = $row['country'];
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
				"</td>";	
				
				//Only $first_show_countries				
				if ($nb_countries >$first_show_countries) {
					$Country_visitors_pie[$first_show_countries] = $Country_visitors_pie[$first_show_countries] + $row['visitors_by_country'];
				} else {
					$Country_visitors_pie[] = $row['visitors_by_country'];
				}
				
				$nb_countries++;
			}

			//---------- AFFICHAGE --- OR ARCHIVAGE ------------
			if ($year_today.$month_today == $year_displayed.$month_displayed) { 
				echo $show_cumul_page;
			} elseif ($year_today.$month_today > $year_displayed.$month_displayed) {
				fwrite($inF, $show_cumul_page);
			}
			$show_cumul_page = '';
			//--------------------------------------------------- 

	//##################################################################
	//################ CAMENBERT With GD ###############################
		
		if ($gdv = gdVersion()) {
			if ($gdv >=2) {
				//echo 'TrueColor functions may be used.';
				$GD_ver = 'TrueColor';
			} else {
				//echo 'GD version is 1.  Avoid the TrueColor functions.';
				$GD_ver = 'NOT_TrueColor';
			}
		} else {
			//echo "The GD extension isn't loaded.";
			$GD_ver = 'NOT_loaded';
		}
		//--------------------------------------------------------------------------------------------------------------------------------------------     
		
		if ($GD_ver == 'TrueColor' && $total_differents_countries > 0) {
		
			///* ---------------------------------------------------- 
			//Commenter tout ci-dessous si gd non utilisé
			 
			 $pChart_path = 'lib/pChart.1.27d_GD';	
			 
			 // Standard inclusions   
			 include_once($pChart_path.'/pChart/pData.class');
			 include_once($pChart_path.'/pChart/pChart.class');
			
			 // Dataset definition 
			 $DataSet = new pData;
			
			 $DataSet->AddPoint($Country_visitors_pie,"Serie1");
			 $DataSet->AddPoint($Country_name_pie,"Serie2");
			
			 $DataSet->AddAllSeries();
			 $DataSet->SetAbsciseLabelSerie("Serie2");
			
			 // Initialise the graph
			 $Test = new pChart(550,350);
			
			 $Test->loadColorPalette($pChart_path.'/includes/tones-20c.txt'); // Ajouter pour le camenbert couleurs cycliques
			 // Draw the pie chart
			 $Test->setShadowProperties(0,0,200,200,200); // Ajouter
			 $Test->setFontProperties($pChart_path.'/Fonts/tahoma.ttf',7);
			 $Test->AntialiasQuality = 0;
			 //														position hrz camenbert, position hauteur camenbert, diamètre, PIE_PERCENTAGE_LABEL,FALSE, perspective,hauteur camenbert, espace tranches
			 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),270,180,200,PIE_PERCENTAGE_LABEL,TRUE,50,20,5); //
		
			//Graph TITLE
			if ($nb_countries > $first_show_countries) {	
				$Test->setFontProperties($pChart_path.'/Fonts/tahoma.ttf',10);  
				$Test->setShadowProperties(1,1,0,0,0);  
				$Test->drawTitle(0,0,"Top ".$first_show_countries." Countries",0,0,0,550,50,TRUE); 
				$Test->clearShadow();  
			}
			
			 $Test->Render("cache/graph_org_geo_".$format_date_file_name.".png");
			
			 $show_cumul_page .= "
			 		<tr>
					<td colspan=\"3\" style=\"".$td_data_CSS." text-align: center;\">
						<img src=\"".$path_allmystats_abs."/cache/graph_org_geo_".$format_date_file_name.".png\"/>
					</td>
					</tr>";
		
		}
		//--------------------------------------------------------------------------------
		  
		$show_cumul_page .= '</table></td></tr></table></td></tr></table><br />';

		if($time_test == true) {
			$end = (float) array_sum(explode(' ',microtime()));  
			echo '<pre>										Org Géo Traitement + GD Traitement: '.sprintf("%.4f", $end-$start) . ' sec</pre>';
		}

		//---------- AFFICHAGE OR ARCHIVAGE ------------
		if ($year_today.$month_today == $year_displayed.$month_displayed) { 
			echo $show_cumul_page;
		} elseif ($year_today.$month_today > $year_displayed.$month_displayed) {
			fwrite($inF, $show_cumul_page);
		}
		$show_cumul_page = '';
		//---------------------------------------------- 

	//##################################################################
	//################### BROWSER, OS, BAD AGENT #######################
		
		//---------- AFFICHAGE OR ARCHIVAGE ------------
		$display_operating_system = true;
		$display_browsers = true;
		$display_bad_user_agent = true;
		$when_date = $month_year_displayed; // date d/m/Y or m/Y

		$show_page_os_nav_robots = '';
		include(FILENAME_DISPLAY_OS_BROWSER);
		if ($year_today.$month_today == $year_displayed.$month_displayed) { 
			echo $show_page_os_nav_robots;
		} elseif ($year_today.$month_today > $year_displayed.$month_displayed) {
			fwrite($inF, $show_page_os_nav_robots);
		}
		$show_page_os_nav_robots = '';
		//---------------------------------------------- 

		//################################################################
		//################### BOTS #######################################

		$when_date = '%'.$month_year_displayed; // date d/m/Y or m/Y
		$display_bots = true;
		$affiche_only_other_bots = false;
		include(FILENAME_DISPLAY_BOTS);
		//---------- AFFICHAGE --- OR ARCHIVAGE ------------
		if ($year_today.$month_today == $year_displayed.$month_displayed) { 
			echo $show_page_os_nav_robots;
		} elseif ($year_today.$month_today > $year_displayed.$month_displayed) {
			fwrite($inF, $show_page_os_nav_robots);
		}
		$show_page_os_nav_robots = '';
		//--------------------------------------------------- 
		
		//###################################################################################################
		//###################################################################################################

		//################## IF archivage write footer & close file ######################
		if ($year_today.$month_today > $year_displayed.$month_displayed) { 
			$page_html = $show_footer.'</td>
			</tr>
			</table></body></html>';
			fwrite($inF, $page_html);
			fclose($inF); 
		
		//################################################################################
		//############### Archive tables allmystats MySQL & delete ############################

			$Total_visits = $total_nb_visiteurs + $Total_distinct_ip_bots;
			$Total_pages_view = $total_nb_pages_visitees + $Total_pages_bots;
	
			if (!$archivage_test) {	//TEST - Archivage
				$result = mysql_query("insert into ".TABLE_ARCHIVE." (annee, mois, visite, visiteur, visites_hors_bot, pages_hors_bot, visites_robot,pages_robots) 
				values('".$year_displayed."','".$month_displayed."','".$Total_pages_view."','".$Total_visits."','".$total_nb_visiteurs."','".$total_nb_pages_visitees."','".$Total_distinct_ip_bots."','".$Total_pages_bots."')") or die('Erreur SQL! TABLE_ARCHIVE: '.$result.'<br>'.mysql_error()); 
		
				$result = mysql_query("delete from ".TABLE_MONTHLY_KEYWORDS." where date='".$month_year_displayed."'") or die('Erreur SQL! TABLE_MONTHLY_KEYWORDS: '.$result.'<br>'.mysql_error()); ;
				
				$result = mysql_query("delete from ".TABLE_DAYS_KEYWORDS." where date like '%".$month_year_displayed."'") or die('Erreur SQL! TABLE_DAYS_KEYWORDS: TABLE_DAYS_PAGES: '.$result.'<br>'.mysql_error()); ;
				$result = mysql_query("delete from ".TABLE_DAYS_PAGES." where date like '%".$month_year_displayed."'") or die('Erreur SQL! '.$result.'<br>'.mysql_error()); ;
				
				$result = mysql_query("delete from ".TABLE_UNIQUE_VISITOR." where date like '%".$month_year_displayed."'") or die('Erreur SQL! TABLE_UNIQUE_VISITOR: '.$result.'<br>'.mysql_error()); ;
				$result = mysql_query("delete from ".TABLE_PAGE_VISITOR." where code like '".$year_displayed.$month_displayed."%'") or die('Erreur SQL! TABLE_PAGE_VISITOR: '.$result.'<br>'.mysql_error()); ;
		
				$result = mysql_query("delete from ".TABLE_UNIQUE_BOT." where date like '%".$month_year_displayed."'") or die('Erreur SQL! TABLE_UNIQUE_BOT: '.$result.'<br>'.mysql_error());
				$result = mysql_query("delete from ".TABLE_PAGE_BOT." where code like '".$year_displayed.$month_displayed."%'") or die('Erreur SQL! TABLE_PAGE_BOT: '.$result.'<br>'.mysql_error());
		
				$result = mysql_query("delete from ".TABLE_UNIQUE_BAD_AGENT." where date like '%".$month_year_displayed."'") or die('Erreur SQL! TABLE_UNIQUE_BAD_AGENT: '.$result.'<br>'.mysql_error());
				$result = mysql_query("delete from ".TABLE_PAGE_BAD_AGENT." where code like '".$year_displayed.$month_displayed."%'") or die('Erreur SQL! TABLE_PAGE_BAD_AGENT: '.$result.'<br>'.mysql_error());
	
				//If allmystats_days_keywords & allmystats_monthly_keywords empty --> Set to 0 autoincrement
				$result = mysql_query("select * from ".TABLE_DAYS_KEYWORDS);
				if (mysql_num_rows($result) == 0) {
					mysql_query("ALTER TABLE ".TABLE_DAYS_KEYWORDS." AUTO_INCREMENT=0");
				}
				$result = mysql_query("select * from ".TABLE_MONTHLY_KEYWORDS);
				if (mysql_num_rows($result) == 0) {
					mysql_query("ALTER TABLE ".TABLE_MONTHLY_KEYWORDS." AUTO_INCREMENT=0");
				}
			}
	
			//---------------------------------------------------------------------------------------
			
			//	Archivage terminé - Redirect to month cache
			msg_waiting_hidden("waiting");
			echo '
			<table align="center" width="50%"  border="0">
			  <tr>
				<td align="center"><b>'.MSG_MONTH_COMPLETED_CACHE_FILE.' : '.$month_year_displayed.'</b><br><br>';
	
				if (file_exists($Fnm)) {
					echo '				
						<form name="form1" method="post" action="'.FILENAME_INDEX_FRAME.'">
						<input type="hidden" name="type" value="cumul">
						<input name="datemois" class="submitDate" type="submit" size="1" value="&nbsp;OK&nbsp;" title="OK">
					</form>';
				} 

		} // END ($year_today.$month_today > $year_displayed.$month_displayed) { 
				?>
	
				</td>
			  </tr>
			</table>
			<p>&nbsp;</p>
<?php

//############################################################################################################
//########################################### Functions ######################################################

function msg_waiting_visible($id) {
 	?>
	<script type="text/javascript">
	 document.getElementById("<?php echo $id; ?>").style.visibility = "visible";
	 </script> <?php
 }

function msg_waiting_hidden($id){
 	?>
 	<script type="text/javascript">
		document.getElementById("<?php echo $id; ?>").style.visibility = "hidden";
	</script> <?php
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