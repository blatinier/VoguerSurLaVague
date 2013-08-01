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

// ----- Should not be called directly ----------------
if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/'.FILENAME_MAIN ){ 
	header('Location: index.php');
}

//---------- Config Limit affichage ------------------------
// Number of display top referer
if(!isset($number_top_display)) {
	$number_top_display = 40;
}

//Limite pour l'archivage et 1ere edition en cours
$small_limit_keywords = '50'; // For each search engine
$small_limit_pages_view = '100'; 

//Limite affichage liste complète
$max_limit_keywords = '2000';
$max_limit_pages_view = '300';

include_once('includes/display_keys_pages_limit.php');
//----------------------------------------------------------

if (isset($_POST['not_display_keyword_pos']) && isset($limit_keywords)) {
	$not_display_keyword_pos = $_POST['not_display_keyword_pos'];
}

require_once(dirname(__FILE__).'/includes/functions/'.FILENAME_VISTORS_FUNCTIONS);

		if(!function_exists('geoip_open')) { //To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in if in page
			require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
		}
		if(!function_exists('geoip_country_name_by_code')) { //Normally not required here but ... - if previously declared in page but not geoip_add.inc (add for AllMySats) 
			require_once(dirname(__FILE__).'/lib/geoip/geoip_add.inc');
		}

		if($when == ""){ // seulement lorsque 1er appel page
			$when = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		}

?>
<style type="text/css">
<!--
.Style11pxbold {
	font-size: 11px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>

<?php		
		echo '		
		<table style="'.$table_border_CSS.'">
		  <tr>
			<td>
			  <table style="'.$table_frame_CSS.' margin-top: 2px; margin-bottom: 2px;">
				<tr>
				 <td style="'.$table_title_CSS.'">
					<form name="form1" method="post" action="'. $_SERVER['PHP_SELF'].'">
						<input type="hidden" name="when"  value="'.$when.'">
						<input class="submit" name="refresh" type="submit" value="'.MSG_REFRESH.'" alt="'.MSG_REFRESH.'" title="'.MSG_REFRESH.'">
					</form>
					&nbsp;&nbsp;'.MSG_STATISTICS_OF.$when.'
				 </td>
				</tr>
		</table>
		</td></tr></table></td></tr></table><br />';
		
		$mois_actuelle = date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$Mois_Annee_actuelle = explode("/", $mois_actuelle);
		$date_compare = $Mois_Annee_actuelle[1].$Mois_Annee_actuelle[0];

		//Select les mois différents contenu dans la table visiteurs
		$result_month = mysql_query("SELECT DISTINCT substr(date, 4) as month from ".TABLE_UNIQUE_VISITOR." ORDER BY date ASC");
		$todo_archive = false;
		while($row_month = mysql_fetch_array($result_month)){ // while distinct month
			$Mois_Annee_visualise = explode("/", $row_month['month']);
			$date_compare_sql = $Mois_Annee_visualise[1].$Mois_Annee_visualise[0]; // yearmonth

			if($date_compare > $date_compare_sql) {
				$todo_archive = true;
			}
		}
					
		if($todo_archive) {
			echo '<div style="color:#FF0000; text-align:center;">'.MSG_WARNING_MONTH_NOT_CACHED.'<br></div>';
			?>
			<div align="center">
			<form name="form1" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
				<input type="hidden" name="type" value="cumul">
				<input type="hidden" name="when"  value="<?php echo $when; ?>">
				<input class="submit" name="cumul" type="submit" value="<?php echo MSG_ARCHIVE; ?>" alt="<?php echo MSG_ARCHIVE; ?>" title="<?php echo MSG_ARCHIVE; ?>">
			</form><br /><br />
			</div>
			<?php
		} ?> 
		<?php

				//##############################################################################
				//						 TABLEAU DE BORD
				//##############################################################################

		//Nb visiteurs et pages hors bot,crawler, robots et bad user agent inconnus
		//---------------------------------------------------------------------------------------------
		//Visteurs unique du jour
		$result = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_VISITOR." where date like '".$when."'");
		$row = mysql_fetch_array($result);
		$NbpageVues_HorsBots = $row['total_pages_view'];
		$NbVisites_HorsBots = $row['nb_visitors'];
/*
		//On ajoute Visitors et pages visitées des user agent inconnus --> NO NO
		// 2013-04-16 - Standardization of the date
		$exd_month_date = explode('/', $when);
		if (isset($exd_month_date[2]) && $exd_month_date[2]) { // by day
			$MySQL_month_date = $exd_month_date[2].'-'.$exd_month_date[1].'-'.$exd_month_date[0];
		} else { // by month
			$MySQL_month_date = $exd_month_date[1].'-'.$exd_month_date[0];
		}
		$result = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_BAD_AGENT." where date like '".$MySQL_month_date."%' and type='I'"); 
		$row_bad_agent = mysql_fetch_array($result);
		$NbpageVues_HorsBots = $NbpageVues_HorsBots + $row_bad_agent['total_pages_view'];
		$NbVisites_HorsBots =  $NbVisites_HorsBots + $row_bad_agent['nb_visitors'];
*/
		//Visites bots
		$result = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_BOT." where date like '".$when."'");
		$row_bots = mysql_fetch_array($result);
		$NbpageVues_Bots = $row_bots['total_pages_view'];
		$NbVisites_Bots =  $row_bots['nb_visitors'];

		$Total_pages_visitorsAndBots = $NbpageVues_HorsBots + $NbpageVues_Bots; //TOTAL PAGES VUES
		$Total_visits_visitorsAndBots = $NbVisites_HorsBots + $NbVisites_Bots; //TOTAL VISITEURS

		$visite_par_visiteurs_HorsBots = @bcdiv($NbpageVues_HorsBots, $NbVisites_HorsBots, 2); 
		$visite_par_visiteurs_Bots = @bcdiv($NbpageVues_Bots, $NbVisites_Bots, 2); 
		$visite_par_visiteurs = @bcdiv($Total_pages_visitorsAndBots, $Total_visits_visitorsAndBots, 2);

		//Heure premier, heure dernier et Nb de page vue, PREMIER VISITEUR (hors bot)
		$result = mysql_query("select p.hour from ".TABLE_UNIQUE_VISITOR." v, ".TABLE_PAGE_VISITOR." p where date like '".$when."' and v.code=p.code order by hour");
		//$nb_visite = 0;
		$heure_premier = '';
		$heure_dernier = '';
		while($row = mysql_fetch_array($result)){
			if($heure_premier == ""){
				$heure_premier = $row['hour'];
			}

			$heure_dernier = $row['hour'];
		}

		//Date view - 24H00
		$diffhier = $UTC-24;
		$heure_utc = date('H:i',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$date_view = explode('/', $when);
		$date_view = $date_view[2].'/'.$date_view[1].'/'.$date_view[0]." ".$heure_utc;;
		$date_utc_hier = date('d/m/Y',strtotime($diffhier." hours", strtotime($date_view)));
/*
		//Nb visiteurs veille (sans les robots)(at same time)
		$result = mysql_query("select * from ".TABLE_UNIQUE_VISITOR." v, ".TABLE_PAGE_VISITOR." p where p.code=v.code and p.hour<'".$heure_utc."' and p.hour>'00:00' and v.date = '".$date_utc_hier."' group by ip");
		$yesterday_total_visitors = mysql_num_rows($result);

		//Nb visites des bots veille (at same time)
		$result = mysql_query("select * from ".TABLE_UNIQUE_BOT." v, ".TABLE_PAGE_BOT." p where p.code=v.code and hour<'".$heure_utc."' and hour >'00:00' and date = '".$date_utc_hier."' group by ip");
		$yesterday_total_bot_visits = mysql_num_rows($result);
*/

		//Yesterday Total Visitors and total page view
		$result = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_VISITOR." where date like '".$date_utc_hier."'");
		$row = mysql_fetch_array($result);
		$yesterday_total_pages_visitors = $row['total_pages_view'];
		$yesterday_total_visitors = $row['nb_visitors'];

		//Yesterday Total bots and bots total page view
		$result = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_BOT." where date like '".$date_utc_hier."'");
		$row_bots = mysql_fetch_array($result);
		$yesterday_total_pages_bots = $row_bots['total_pages_view'];
		$yesterday_total_bot_visits =  $row_bots['nb_visitors'];

		//#############################################################################################
							// Nombre visiteur total (avec robots)
		/*
		// on test aussi $_SESSION['ALEXA'][0] && $_SESSION['PAGERANK'] if in php.ini allow_url_fopen = off
		//get_cfg_var donne la valeur dans php.ini et ini_get la valeur courante (qui peut avoir été modifiée) 
		*/
	if( ($display_alexa_pagerank == 'true' || $display_alexa_pagerank == '') && ini_get('allow_url_fopen') && isset($_SESSION['ALEXA'][0]) && isset($_SESSION['PAGERANK'])) {
		$alexa = '<a href="http://www.alexa.com/siteinfo/'.$site.'" target="_blank">Alexa Traffic</a> (3 month)<br />Rank : '.$_SESSION['ALEXA'][0].'<br />Change : '.$_SESSION['ALEXA'][2].'<br />Links : '.$_SESSION['ALEXA'][3];
		$PageRank =	'Google PageRank<br />'.$_SESSION['PAGERANK'];
	} else {
		$alexa = '';
		$PageRank =	'';
	}

echo '
<table style="'.$table_border_CSS.'">
  <tr>
    <td>

      <table style="'.$table_frame_CSS.' margin-top: 0px; margin-bottom: 0px;">
        <tr>
		  	<td>
				<table align="center" style="padding-top: 0px;">
					  <tr>
						<td style="width: 33%;">'
							.$alexa.'&nbsp;
						</td>
						
						<td style="'.$table_title_CSS.'">'.MSG_SCOREBOARD.'<br />';
							echo '
							<form name="form2" method="post" action="'. $_SERVER['PHP_SELF'].'">
								<input type="hidden" name="when"  value="'.$when.'">
								<input border=0 src="'.$path_allmystats_abs.'images/icons/icon_scorebard.gif" height=22px" type=image name="refresh" alt="'.MSG_REFRESH.'" title="'.MSG_REFRESH.'" value="'.MSG_REFRESH.'"/>
							</form>
						</td>';
						
						echo '<td style="text-align: center;  width: 16%;">&nbsp;</td>
						<td style="text-align: center;  width: 17%;">'
							.$PageRank.'&nbsp;
						</td>
					  </tr>
				</table>
			</td>

		</tr>
		<tr>
			<td>

				<table style="'.$table_data_CSS.' margin-top: 0px;">';

		echo "
		<tr>
		 <td style=\"".$td_data_CSS."\">
			".MSG_FIRST_VISITOR." :
		 </td>
		 <td style=\"".$td_data_CSS."\">"; echo $heure_premier; echo "&nbsp;</td>
		 <td style=\"".$td_data_CSS." text-align: center; \">-</td>
			</tr>
				<tr> 
				  <td style=\"".$td_data_CSS."\"> 
					".MSG_LAST_VISITOR." :
				  </td>
				  <td style=\"".$td_data_CSS."\">"; echo $heure_dernier; echo "&nbsp;</td>
				  <td style=\"".$td_data_CSS." text-align: center; \">-</td>
				</tr>
				<tr> 
				  <td style=\"".$td_data_CSS." vertical-align: top;\">
					".MSG_NUMBER_VISTORS." (".MSG_EXCLUDED_BOTS.") :<br />
					".MSG_NB_VISITS_BOTS." :
				  </td>
				  <td style=\"".$td_data_CSS." vertical-align: top;\"><span class=\"Style11pxbold\">".$NbVisites_HorsBots."</span><br>".$NbVisites_Bots."</td>
				  <td style=\"".$td_data_CSS." vertical-align: top;\">&nbsp;&nbsp;".MSG_YESTERDAY." : ".$yesterday_total_visitors."<br>"."&nbsp;&nbsp;".MSG_YESTERDAY." : ".$yesterday_total_bot_visits."</td>
				</tr>
				<tr> 
				  <td style=\"".$td_data_CSS." vertical-align: top;\"> 
					".MSG_NUMBER_VISITED_PAGES." (".MSG_EXCLUDED_BOTS.") :<br />
					".MSG_NB_VISITED_PAGES_BOTS."  :
				  </td>
				  <td style=\"".$td_data_CSS." vertical-align: top;\">
				  		<span class=\"Style11pxbold\">".$NbpageVues_HorsBots."</span><br>".$NbpageVues_Bots."
				  </td>
				  <td style=\"".$td_data_CSS." vertical-align: top;\">
				  		&nbsp;&nbsp;".MSG_YESTERDAY." : ".$yesterday_total_pages_visitors."<br>"."&nbsp;&nbsp;".MSG_YESTERDAY." : ".$yesterday_total_pages_bots."
				  </td>
				</tr>
				<tr> 
				  <td style=\"".$td_data_CSS." vertical-align: top;\">
					".MSG_VISITED_PAGES_BY_VISITOR." (".MSG_EXCLUDED_BOTS.") :<br />
					".MSG_VISITED_PAGES_BY_BOT." :
				  </td>
				  <td style=\"".$td_data_CSS." vertical-align: top;\">"; 
				  	$pages_by_visitors = @bcdiv($NbpageVues_HorsBots, $NbVisites_HorsBots, 2); 
					echo '<span class="Style11pxbold">'.$pages_by_visitors.'</span><br>';
					$pages_by_Bots = @bcdiv($NbpageVues_Bots, $NbVisites_Bots, 2); 
					echo $pages_by_Bots."&nbsp
				  </td>";

					$yesterday_pages_by_visitor = @bcdiv($yesterday_total_pages_visitors, $yesterday_total_visitors, 2); 
 					$yesterday_pages_by_bot = @bcdiv($yesterday_total_pages_bots, $yesterday_total_bot_visits, 2);

				    echo "<td style=\"".$td_data_CSS." vertical-align: top;\">&nbsp;&nbsp;".MSG_YESTERDAY." : ".$yesterday_pages_by_visitor."<br>"."&nbsp;&nbsp;".MSG_YESTERDAY." : ".$yesterday_pages_by_bot."</td>
				   </td>";
?>
</table></td></tr></table>
</td></tr></table><br>
<?php

		#########################################################################################
		####################### Visites par plage horaire #######################################
		//#######################################################################################
			include(FILENAME_GRAPH_DAY_HOURS);
		//#######################################################################################	

		//#######################################################################################
		//------------------------- Include tableau Keywords ------------------------------
			$when_day = $when;
			$show_cumul_page = '';
			include_once(FILENAME_KEYWORDS_REFERERS_DAY);
			echo $show_cumul_page;
		//#######################################################################################

		//#######################################################################################
		//						 AFFICHAGE TABLEAU TOP REFERER
		//#######################################################################################

echo '
<table style="'.$table_border_CSS.'">
  <tr>
    <td>
      <table style="'.$table_frame_CSS.'">
        <tr>
			<td style=\"width:5%; white-space:nowrap;\">
				&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_top.gif" height="32px" alt="'.MSG_TOP.' '.MSG_VISITEURS.'" title="'.MSG_TOP.' '.MSG_VISITEURS.'">
			</td>
          	<td style="'.$table_title_CSS.'">
		  		'.MSG_TOP.' '.$number_top_display.' '.MSG_VISITEURS.' ('.MSG_EXCLUDED_BOTS.')
		  	</td>
        </tr>
        <tr>
          <td colspan="2">
            <table style="'.$table_data_CSS.'">
              <tr>
                <th style="'.$td_data_CSS.' text-align: center;">IP</th>
                <th style="'.$td_data_CSS.' text-align: center;">'.MSG_REVERSE_DNS.'</th>
				<th style="'.$td_data_CSS.' text-align: center;">'.MSG_REFERERS.'</th>
                <th style="'.$td_data_CSS.' text-align: center;">'.MSG_VISITED_PAGES.'</th>
                <th style="'.$td_data_CSS.' text-align: center;">'.MSG_PAGES_PERCENTAGE.'</th></tr>';


			$result = mysql_query("select * from ".TABLE_UNIQUE_VISITOR." where date='".$when."' order by visits desc"); //limit 0,20
			if (!$result) { //ex: si la table n'existe pas
				echo 'Impossible d\'exécuter la requête : ' . mysql_error();
				exit;
			}
	
		$Nb_lignes_affichees = 0;
		while($Nb_lignes_affichees < $number_top_display && $row = mysql_fetch_array($result)){
	
			$Nb_lignes_affichees = $Nb_lignes_affichees + 1;
			$max = 25;	 // Nombre de caractères max
			$coupe = "";
			if(mb_strlen($row['reverse_dns'],'utf-8') >= $max)  {
				$coupe = 25; 
			}
	
			$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD); //geoip_close($handle); // en fin de fichier
			//col IP - Country flags
			$record_code = geoip_country_code_by_addr($handle, $row['ip']); 
			@geoip_close($handle); 
			
			if (file_exists('images/flags/'.strtolower($record_code).'.png')) {
				$Country_flag = "<img src=\"images/flags/".strtolower($record_code).".png\" height=\"10\" width=\"14\" alt=\"".$row['country']."\" title=\"".$row['country']."\">";
			} else {
				$Country_flag = $row['country'];
			}

			if ($record_code) {
				echo '<tr><td style="'.$td_data_CSS.' white-space: nowrap;">'.$Country_flag.'<br />'.$row['ip'].'</td>';
			} else {
				echo '<tr><td style="'.$td_data_CSS.' white-space: nowrap;">'.MSG_ORIGIN_UNKNOWN.'<br />'.$row['ip'].'</td>';
			}

			//col Reverse DNS
			if($coupe) {
				$chaine1 = mb_substr($row['reverse_dns'], 0, $coupe,'utf-8');
				$chaine2 = mb_substr($row['reverse_dns'], $coupe, mb_strlen($row['reverse_dns'],'utf-8'),'utf-8');
				echo '<td style="'.$td_data_CSS.' white-space: nowrap;">'.$chaine1.'<br>'.$chaine2.'</td>';
			} else {
				echo '<td style="'.$td_data_CSS.' white-space: nowrap;">'.$row['reverse_dns'].'</td>';		  
			}
				
			//col referer
			echo '<td style="'.$td_data_CSS.' white-space: nowrap;">&nbsp;&nbsp;';

			if($host = @parse_url($row["referer"])) {
				$host = parse_url($row["referer"]);
			} else {
				$host = parse_url('http://Invalid URL');
			}
  
			//24-10-2011
			$keyword = trim(search_keyword_in($row["referer"])); // $start_page global in search_keyword_in
			$delimiter = '|;|';
			$exp_keyword = explode($delimiter, $keyword);
			$keyword = stripslashes($exp_keyword[0]);
			$nb_characters = 60; //Maximum characters for keywords and referrers
			if (mb_strlen($keyword,'utf-8') > $nb_characters) { 
				$keyword = mb_substr($keyword, 0, $nb_characters,'utf-8').'...';
			} 

			$keyword_search = strtr(substr($keyword, 1, -1)," ","+"); //remove first [ and last ] and replace space by +

			$keyword_position = '';
			if (@$exp_keyword[1] && @$exp_keyword[2]) {
				$keyword_position = '(pos '.$exp_keyword[1].' page '.$exp_keyword[2].')';
			} elseif (@$exp_keyword[1]) {
				$keyword_position = '(pos '.$exp_keyword[1].')';
			} elseif (@$exp_keyword[2]) {
				$keyword_position = '(page '.$exp_keyword[2].')';
			}

			if(isset($host["host"]) && $host["host"] <> $_SERVER['SERVER_NAME']){
				$aff_referer = $row['referer'];

				// Google Adwords
				//Attention peut être "?" ou "&" (voir visiteur.php)
				if (strstr($aff_referer,'googlesyndication=1') || strstr($aff_referer, 'googlesyndicReseauGCLID=1')) { 
					$aff_referer = str_replace('?googlesyndication=1', '', $aff_referer);
					$aff_referer = str_replace('&googlesyndication=1', '', $aff_referer); 
					$aff_referer = str_replace('?googlesyndicReseauGCLID=1', '', $aff_referer); 
					$aff_referer = str_replace('&googlesyndicReseauGCLID=1', '', $aff_referer); 

					$link = $aff_referer;
					if (mb_strlen($aff_referer.strip_tags(MSG_ADWORDS_CONTENT_NETWORK),'utf-8') > 40) {
						$aff_referer = mb_substr($aff_referer, 0, 40,'utf-8').'...';
					}
					echo MSG_ADWORDS_CONTENT_NETWORK .':<br />&nbsp;&nbsp;<a href="'.htmlspecialchars($link).' target="_new">'.urldecode($aff_referer).'</a><br />';
					// ---------------------------------------------
				
				// Google Adwords
				} elseif (strstr($row["referer"], '/aclk') || strstr($row["referer"], 'googlesyndicKeywordGCLID=1')) { //Mot clé envoyés par Google Adwords - Test on url? ou /aclk or both ?
					// $start_page global in search_keyword_in
					$Google_link_format = 'http://'.$host['host'].'/search?q='.$keyword_search.'&start='.$start_page; //See if $start ok for AdWord Keyword
					$aff_New_link_google = $Google_link_format;
					if (mb_strlen($aff_New_link_google, 'utf-8') > 40) {
						$aff_New_link_google = mb_substr($aff_New_link_google, 0, 40, 'utf-8').'...';
					} 
					echo MSG_ADWORDS_KEYWORD.':<br />&nbsp;&nbsp;<font color="#990000">'.$keyword.'</font> '.$keyword_position.'</font><br />';
					echo '&nbsp;&nbsp;<a href="'.htmlspecialchars(stripslashes($Google_link_format)).'" target="_new">'.stripslashes(urldecode($aff_New_link_google))."</a>";
				
				// Google Search				
				} elseif (strstr($host['host'], 'google') && strstr($row["referer"], 'source=web')) { //source=web, on a la position du keyword
					// $start_page global in search_keyword_in
					$Google_link_format = 'http://'.$host['host'].'/search?q='.$keyword_search.'&start='.$start_page;
					$aff_New_link_google = $Google_link_format;
					if (mb_strlen($aff_New_link_google, 'utf-8') > 40) {
						$aff_New_link_google = mb_substr($aff_New_link_google, 0, 40, 'utf-8').'...';
					} 
					echo $host["host"].' - Link:<br>&nbsp;&nbsp;<font color="#990000">'.$keyword.'</font> '.$keyword_position.'<br>
					&nbsp;&nbsp;<a href="'.htmlspecialchars(stripslashes($Google_link_format)).'" target="_new">'.stripslashes(urldecode($aff_New_link_google))."</a>";
				
				// Google search ? but no keyword position - sa=f&rct=j&url
				} elseif (strstr($host['host'], 'google') && strstr($row["referer"], 'sa=f') && strstr($row["referer"], '&rct=j')) { 
					$Google_link_format = 'http://'.$host['host'].'/search?q='.$keyword_search;
					$aff_New_link_google = $Google_link_format;
					if (mb_strlen($aff_New_link_google, 'utf-8') > 40) {
						$aff_New_link_google = mb_substr($aff_New_link_google, 0, 40, 'utf-8').'...';
					} 
					echo $host["host"].' - Link:<br>&nbsp;&nbsp;<font color="#990000">'.$keyword.'</font> '.$keyword_position.'<br>
					&nbsp;&nbsp;<a href="'.htmlspecialchars(stripslashes($Google_link_format)).'" target="_new">'.stripslashes(urldecode($aff_New_link_google))."</a>";
					//----------------------------------------------
				
				//Google Images new version 
				} elseif (strstr($host['host'], 'google') && strstr($row["referer"], 'tbm=isch') && !strstr($row["referer"], 'sout=1')) { 
					$exp_link_images = explode('&page', $aff_referer); // on supprime &page=&start=&sout=&ved= qui ont été enregistrés dans visiteur.php
					$link_images = $exp_link_images[0];
					$aff_referer = $exp_link_images[0];					
					if (mb_strlen($aff_referer,'utf-8') > 40) { 
						$aff_referer = mb_substr($aff_referer, 0, 40,'utf-8').'...';
					}
					// On affiche le numéro de page séparément (car num page fictif)
					echo $host["host"].' Google Images<br>&nbsp;&nbsp;<font color="#990000">'.$keyword.'</font> '.$keyword_position.'<br />';
					echo '&nbsp;&nbsp;<a href="'.htmlspecialchars($link_images).'" target="_new">'.urldecode($aff_referer).'</a>'; 
				} elseif (strstr($host['host'], 'google') && strstr($row["referer"], 'tbm=isch') && strstr($row["referer"], 'sout=1')) { // Google Images Hold version
					if (mb_strlen($aff_referer,'utf-8') > 40) { 
						$aff_referer = mb_substr($aff_referer, 0, 40,'utf-8').'...';
					}

					// Google Images Hold version - Envoi directement sur la page où se trouve le keyword
					echo 'Google Images hold version<br>&nbsp;&nbsp;<font color="#990000">'.$keyword.'</font> '.$keyword_position.'<br />';
					echo '&nbsp;&nbsp;<a href="'.htmlspecialchars($row['referer']).'" target="_new">'.urldecode($aff_referer).'</a>'; // pour double quote in keyword --> htmlspecialchars  
				} else {
					if (mb_strlen($aff_referer,'utf-8') > 40) { 
						$aff_referer = mb_substr($aff_referer, 0, 40,'utf-8').'...';
					}
					echo $host["host"].' Link:<br>';
					if($keyword) {
						echo '&nbsp;&nbsp;<font color="#990000">'.$keyword.'</font> '.$keyword_position.'<br>';
					}
					echo '&nbsp;&nbsp;<a href="'.htmlspecialchars($row['referer']).'" target="_new">'.urldecode($aff_referer).'</a>'; // pour double quote in keyword --> htmlspecialchars  
				}
			}
					
			echo '</td>
			<td style="'.$td_data_CSS.' text-align: center;">'.$row['visits'].'</td>
			<td style="'.$td_data_CSS.' text-align: center;">'; 
			$nb = $row["visits"]*100; 
			if($NbpageVues_HorsBots != 0){
				$pourcent = bcdiv($nb, $NbpageVues_HorsBots, 2); 
			}
			echo $pourcent."%&nbsp;</td>
			</tr>";
		}
		//------------------------------------------------------------------------------------------------      
?>    
</table>
</table></td></tr></td></tr></table><br />
<?php
	//############################################################################################
	//						Pages visitées du jour hors robots
	//						TODO Ajouter user agent inconnus
	//############################################################################################
		
		$result = mysql_query("select count(*) as nb_diff_pages, sum(visited_pages) as total_pages from ".TABLE_DAYS_PAGES." where date='".$when."'");
		$row = mysql_fetch_array($result);
		$pages_cumul = $row['total_pages']; // Total Pages

		//$result = mysql_query("select * from ".TABLE_DAYS_PAGES." where date='".$when."' order by visited_pages DESC, pages_name ASC"); //Sort by visited page
		$result = mysql_query("select * from ".TABLE_DAYS_PAGES." where date='".$when."' order by visitors DESC, pages_name ASC ".$limit_pages."");		//Sort by visitors

echo '
<table style="'.$table_border_CSS.'">
  <tr>
    <td>
      <table style="'.$table_frame_CSS.'">
				<tr>
				  <td style="width:5%; white-space:nowrap;">
				  		&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_visited_pages.gif" height="32px" alt="'.MSG_VISITED_PAGES.'" title="'.MSG_VISITED_PAGES.'">
				  </td>
				  <td style="'.$table_title_CSS.'">
				  	'.MSG_VISITED_PAGES.' ('.MSG_EXCLUDED_BOTS.') - '.$when.'
						<div style="font-weight:lighter">
							<small>'.MSG_TOTAL_DIFFERENT_PAGES.': '.$row['nb_diff_pages'].'</small>
						</div>
				  </td>

			<td style="text-align: right;">';		  
			if ($switch_short_complete_list && $row['nb_diff_pages'] >= $small_limit_pages_view) {
			  echo "	 
				<form name=\"form_limi_pages\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
				  <input type=\"hidden\" name=\"type\" value=\"\">
				  <input type=\"hidden\" name=\"when\" value=\"".$when."\">
				  <input type=\"hidden\" name=\"limit_keywords\" value=\"".$limit_keywords."\">
				  <input type=\"hidden\" name=\"limit_pages\" value=\"".$limit_pages."\">
				  <input class=\"submit\" name=\"submit_limit_pages\" type=\"submit\" value=\"".$value_button_pages."\" alt=\"".$value_button_pages."\" title=\"".$value_button_pages."\">
				</form>";		  

				if ( ($row['nb_diff_pages'] > $small_limit_pages_view && $value_button_pages == MSG_COMPLETE_LIST) || ($row['nb_diff_pages'] > $max_limit_pages_view && $value_button_pages == MSG_SHORTLIST) ) {
					echo '<br />Limited pages to : '.$limit_pages.'&nbsp;&nbsp;&nbsp;';
				}
			}
			  
	 		echo '	 
			</td>
			</tr>
			<tr>
				  <td colspan="3">
					<table style="'.$table_data_CSS.'">
					  <tr>
						<th style="'.$td_data_CSS.'">'.MSG_PAGE.'</th>
						<th style="'.$td_data_CSS.' text-align: center;">'.MSG_VISITORS.'</th>
						<th style="'.$td_data_CSS.' text-align: center;">'.MSG_VISITED_PAGES.'</th>
						<th style="'.$td_data_CSS.' text-align: center;">'.MSG_PAGES_PERCENTAGE.'</th></tr>';
		
						while($row = mysql_fetch_array($result)){
							$nb = $row['visited_pages']*100; 
							if($pages_cumul != 0){
								$percents = bcdiv($nb, intval($pages_cumul), 2);
							}
							if(mb_substr($row['pages_name'],0,6,'utf-8') == 'Prod: ') { //affichage couleur différenete pour le page produit oscommerce
								echo '<tr>
								<td style="'.$td_data_CSS.'"><font color="#990000">'.$row['pages_name'].'</font></td>
								<td style="'.$td_data_CSS.' text-align: center;">'.$row['visitors'].'</td>
								<td style="'.$td_data_CSS.' text-align: center;">'.$row['visited_pages'].'</td>
								<td style="'.$td_data_CSS.' text-align: center;">'.$percents.'%</td></tr>';
							} else { 
								echo '<tr>
								<td style="'.$td_data_CSS.'">'.$row['pages_name'].'</td>
								<td style="'.$td_data_CSS.' text-align: center;">'.$row['visitors'].'</td>
								<td style="'.$td_data_CSS.' text-align: center;">'.$row['visited_pages'].'</td>
								<td style="'.$td_data_CSS.' text-align: center;">'.$percents.'%</td></tr>';
							}
						}
				
						echo '
						</table></td></tr></table></td></tr></table><br />
						';


		//########## Affichage Operating system, navigateurs ##################
		$when_date = $when; // date d/m/Y or m/Y

		$display_bad_user_agent = true;
		$display_bads_agents = '';
		include(FILENAME_DISPLAY_BAD_AGENTS);
		echo $display_bads_agents;		
		
		$display_operating_system = true;
		$display_browsers = true;
		include(FILENAME_DISPLAY_OS_BROWSER);
		echo $show_page_os_nav_robots;

		//###############################################################################
		//					 ORIGINE GEO DAY 
		//				TODO Ajouter user agent inconnus
		//###############################################################################		
		
	//$result = mysql_query("select count(country) as visitors_by_country, sum(visits) as pages_by_country, country from ".TABLE_UNIQUE_VISITOR." where date='".$when."' GROUP BY country ORDER BY pages_by_country DESC");
	$result = mysql_query("select count(country) as visitors_by_country, sum(visits) as pages_by_country, country from ".TABLE_UNIQUE_VISITOR." where date='".$when."' GROUP BY country ORDER BY visitors_by_country DESC ");
	$total_differents_countries = mysql_num_rows($result);
		
	$result_max_pages = mysql_query("select sum(visits) as pages_by_country from ".TABLE_UNIQUE_VISITOR." where date='".$when."' GROUP BY country ORDER BY pages_by_country DESC");
	$row_max_pages = mysql_fetch_array($result_max_pages); //Car on tri mintenant sur lrs visiteurs donc 1er pas obligatoirement OK
	$indice = @bcdiv(1, ($row_max_pages['pages_by_country']/200), 3); //proportion en rapport au plus grand nb de pages visités

echo '
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
					$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD); //geoip_close($handle); // en fin de fichier
					$record_code = geoip_country_name_by_code($handle, $row['country']); // Function ajouté dans geoip.inc
					@geoip_close($handle); 

					if (file_exists("images/flags/".strtolower($record_code).".png")) {
						$Country = "<img src=\"images/flags/".strtolower($record_code).".png\" height=\"10\" width=\"14\" alt=\"".$row['country']."\" title=\"".$row['country']."\"> ".$row['country'];
					} else {
						$Country= $row['country'];
					}

					//Only $first_show_countries
					if ($nb_countries > $first_show_countries) {
						$Country_name_pie[$first_show_countries] = "Others";
					} else {
						$Country_name_pie[] = $row['country'];
					}
				}
				
			echo "
			<tr>
				<td style=\"".$td_data_CSS." white-space: nowrap;\"> 
				<b>".$Country."</b>
				</td>
				<td style=\"".$td_data_CSS." white-space: nowrap;\">
				<img src=\"images/histo-h.gif\" width=\""; 
				$hauteur = bcmul($row['visitors_by_country'] , $indice, 2);  
			echo $hauteur .	"\" height=\"8\" alt=\"".$row['visitors_by_country']."\" title=\"".$row['visitors_by_country']."\">".$row['visitors_by_country'].
				"</td>
				<td style=\"".$td_data_CSS." white-space: nowrap;\">
				<img src=\"images/histo-h.gif\" width=\""; 
				$hauteur = bcmul($row['pages_by_country'], $indice, 2);  
			echo $hauteur."\" height=\"8\" alt=\"".$row['pages_by_country']."\" title=\"".$row['pages_by_country']."\">".$row['pages_by_country'].
				"</td>";	

				//Only $first_show_countries				
				if ($nb_countries >$first_show_countries) {
					$Country_visitors_pie[$first_show_countries] = $Country_visitors_pie[$first_show_countries] + $row['visitors_by_country'];
				} else {
					$Country_visitors_pie[] = $row['visitors_by_country'];
				}

				$nb_countries++;
			}

			################################################################
			################### CAMENBERT With GD ##########################
		
			if($total_differents_countries > 0) {
				create_img_piegraph('graph_org_geo_day_temp.png', $Country_visitors_pie, $Country_name_pie, $first_show_countries, $total_differents_countries);
			
				$rand = rand(); // To force refresh img.png if mod expire is insatlled on server and cache img.png
				echo "
				<tr>
				<td colspan=\"3\" style=\"".$td_data_CSS." text-align: center;\">
					<img src=\"cache/graph_org_geo_day_temp.png?rand=".$rand."\"/>
				</td>
				</tr>";
			}
			// ---------------------------------------------------
		 
			echo '</table></td></tr></table></td></tr></table><br />';
?>    
