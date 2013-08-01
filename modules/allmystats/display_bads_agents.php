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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/'.FILENAME_DISPLAY_BAD_AGENTS ){ 
		header('Location: index.php'); //Si appelle direct de la page redirect
	}
	// -----------------------------------------------------------------------

if($display_bad_user_agent) {

	if(!isset($StatsIn_in_prot_dir)) { $StatsIn_in_prot_dir = ''; } // Si n'est pas include de stats_in

	//Org geo
		if(!function_exists('geoip_open')) { //To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in if in page
			require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
		}
		if(!function_exists('geoip_country_name_by_code')) { //Normally not required here but ... - if previously declared in page but not geoip_add.inc (add for AllMySats) 
			require_once(dirname(__FILE__).'/lib/geoip/geoip_add.inc');
		}

	/*
	//SELECT que les agent différents A GARDER
	$result = mysql_query("select agent, type, count(visits) as nb_visits_unique, sum(visits) as nb_pages_visits from ".TABLE_UNIQUE_BAD_AGENT." where date like '%".$when_date."' GROUP BY agent"); //$when_date = d/m/Y or m/Y suivant appelle
	$nb_distinct_bad_agent = mysql_num_rows($result);
	echo $nb_distinct_bad_agent."<br>";
	
	while($row = mysql_fetch_array($result)){
	echo $row['agent']." - ".$row['type']." - ".$row['nb_visits_unique']." - ".$row['nb_pages_visits']."<br>";
	}
	*/

	// Actuellement format seulement pour TABLE_UNIQUE_BAD_AGENT
	// TODO toutes les tables
	$exd_when_date = explode('/', $when_date);
	
	if (isset($exd_when_date[2])) { // by day
		$MySQL_when_date = $exd_when_date[2].'-'.$exd_when_date[1].'-'.$exd_when_date[0];
	} else { // by month
		$MySQL_when_date = $exd_when_date[1].'-'.$exd_when_date[0];
	}

	$result = mysql_query("select agent, type, referer, ip, reverse_dns, sum(visits) as nb_pages_visits, country from ".TABLE_UNIQUE_BAD_AGENT." where date like '".$MySQL_when_date."%' GROUP BY ip"); //$MySQL_when_date = Y/m/d or Y/m suivant appel

	$nb_distinct_ip_bad_agent = mysql_num_rows($result);

	if ($nb_distinct_ip_bad_agent > 0) {
		$display_bads_agents .= "
	<table style=\"".$table_border_CSS."\">
	  <tr>
		<td>
		  <table style=\"".$table_frame_CSS."\">
					<tr>";
		 			
					if (isset($StatsIn_in_prot_dir) && $StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed 
				  		$display_bads_agents .= "
						<td style=\"width:5%; white-space:nowrap;\">
							&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/icon_matrix_agent.gif\" style=\"vertical-align:middle\" alt=\"".MSG_LEFT_VISITORS."\" title=\"".MSG_LEFT_VISITORS."\">
						</td>";
					}
					
					$display_bads_agents .= "
					<th style=\"".$table_title_CSS."\">
						".MSG_LEFT_VISITORS."&nbsp;&nbsp;<small><font style=\"font-weight:lighter\">Total: ".$nb_distinct_ip_bad_agent."<br />".MSG_USER_AGENT_SPAM." - ".MSG_USER_AGENT_UNKNOWN." - ".MSG_USER_AGENT_OTHER."</font></small>
					</th>
					  </tr>
					<tr>
					  <td colspan=\"2\">
						<table style=\"".$table_data_CSS."\">
						  <tr>
							<th style=\"".$td_data_CSS." width=40%; ext-align: center;\">".MSG_USER_AGENT."</th>
							<th style=\"".$td_data_CSS." text-align: center;\">Type</th>
							<th style=\"".$td_data_CSS." text-align: center;\">IP</th>
							<th style=\"".$td_data_CSS." text-align: center;\">".MSG_BOTS_NB_PAGES_SCANNED."</th>
							<th style=\"".$td_data_CSS." text-align: center;\">Reverse DNS</th>
							<th style=\"".$td_data_CSS." text-align: center;\">Referer</th>
							<th style=\"".$td_data_CSS." text-align: center;\">".MSG_COUNTRY."</th>
							<th style=\"".$td_data_CSS." text-align: center;\">".MSG_COMMENTS."</th>
						  </tr>";
		
						while($row = mysql_fetch_array($result)){
							//Country flag
							$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
							$record_code = geoip_country_code_by_addr($handle, $row['ip']); 
							@geoip_close($handle); // GEOIP n'est plus appelé --> on ferme $handle
							$Country_flag = "<img src=\"".$path_allmystats_abs."images/flags/".strtolower($record_code).".png\" height=\"10\" width=\"14\" alt=\"".$row['country']."\" title=\"".$row['country']."\">";
						
							// ------------------------- TODO function ----------------------------
							$max = 50;	 // Nombre de caractères max
							$coupe = "";
							if(mb_strlen($row['agent'],'utf-8') >= $max)  {
								$coupe = 50; 
							}
							if($coupe) {
								$chaine1 = mb_substr($row['agent'], 0, $coupe,'utf-8');
								$chaine2 = mb_substr($row['agent'], $coupe, mb_strlen($row['agent'],'utf-8'),'utf-8');
								$useragent_format = htmlentities($chaine1).'<br>'.htmlentities($chaine2);
							} else {
								$useragent_format = htmlentities($row['agent']);		  
							}

							$max = 50;	 // Nombre de caractères max
							$coupe = "";
							if(mb_strlen($row['reverse_dns'],'utf-8') >= $max)  {
								$coupe = 50; 
							}
							if($coupe) {
								$chaine1 = mb_substr($row['reverse_dns'], 0, $coupe,'utf-8');
								$chaine2 = mb_substr($row['reverse_dns'], $coupe, mb_strlen($row['reverse_dns'],'utf-8'),'utf-8');
								$useragent_format = $chaine1.'<br>'.$chaine2;
							} else {
								$reversedns_format = $row['reverse_dns'];		  
							}

							// -----------
							$exp_referer = explode('<br>',$row['referer']); // if cross scripting '<br>' put in visiteur.php 
							$referer = $exp_referer[0];
							if(isset($exp_referer[1])) {
								$request_url = $exp_referer[1];
							}
							
							$max = 50;	 // Nombre de caractères max
							$coupe = "";
							if(mb_strlen($referer,'utf-8') >= $max)  {
								$coupe = 50; 
							}
							if($coupe) {
								$chaine1 = mb_substr($referer, 0, $coupe,'utf-8');
								$chaine2 = mb_substr($referer, $coupe, mb_strlen($referer,'utf-8'),'utf-8');
								$referer_format = htmlentities($chaine1).'<br>'.htmlentities($chaine2);
							} else {
								$referer_format = htmlentities($referer);		  
							}
							
							if(isset($exp_referer[1])) { // if cross scripting
								$max = 50;	 // Nombre de caractères max
								$coupe = "";
								if(mb_strlen($request_url,'utf-8') >= $max)  {
									$coupe = 50;  
								}
								if($coupe) {
									$chaine1 = mb_substr($request_url, 0, $coupe,'utf-8');
									$chaine2 = mb_substr($request_url, $coupe, mb_strlen($request_url,'utf-8'),'utf-8');
									$request_url_format = htmlentities($chaine1).'<br>'.htmlentities($chaine2);
								} else {
									$request_url_format = htmlentities($request_url);		  
								}
							} else {
								$request_url_format = '';
							}
													
							$display_referer_and_request = $referer_format.'<br>'.$request_url_format;
							// -----------
							// ------------------------------------------------------------------

							if ($row['type'] == 'S' || $row['type'] == 'Z') {
								$display_bads_agents .= "
								<tr>
								<td style=\"".$td_data_CSS." white-space: nowrap;\">
									<font color=\"#FF0000\">[".$useragent_format."]</font>
								</td>";
							} else {
								$display_bads_agents .= "
								<tr>
								<td style=\"".$td_data_CSS." white-space: nowrap;\">
									[".$useragent_format."]
								</td>";
							}
								
							$display_bads_agents .= "
							<td style=\"".$td_data_CSS." text-align: center;\"> 
								".$row['type']."
							</td>
							<td style=\"".$td_data_CSS." text-align: center;\">
								".$row['ip']."</td>
							<td style=\"".$td_data_CSS." text-align: center;\">
								".$row['nb_pages_visits']."</td>
							<td style=\"".$td_data_CSS."\">
								[".$reversedns_format."]
							</td>
							<td style=\"".$td_data_CSS." white-space: nowrap;\">
								".$display_referer_and_request."
							</td>
							<td style=\"".$td_data_CSS."\">
								".$Country_flag." ".$row['country']."
							</td>";

							if ($row['type'] == 'D') {
								$display_bads_agents .= "
								<td style=\"".$td_data_CSS."\"> 
									Detected by Bad reverse DNS configuration
								</td>";
							} elseif ($row['type'] == 'E') {
								$display_bads_agents .= "
								<td style=\"".$td_data_CSS."\"> 
									Detected by Bad IP configuration
								</td>";
							} elseif ($row['type'] == 'F') {
								$display_bads_agents .= "
								<td style=\"".$td_data_CSS."\"> 
									Detected by Bad Referer configuration
								</td>";
							} elseif ($row['type'] == 'Z') {
								$display_bads_agents .= "
								<td style=\"".$td_data_CSS."\"> 
									Detected injection cross-site scripting
								</td>";
							} else {
								$result_comments = mysql_query("select info from ".TABLE_BAD_USER_AGENT." where user_agent='".$row['agent']."'");
								$row_comment = mysql_fetch_array($result_comments);  

								$display_bads_agents .= "
								<td style=\"".$td_data_CSS."\">
									".$row_comment['info']."
								</td>";
							}
						}
		
		$display_bads_agents .= '
			</tr></table></td></tr></table></td></tr></table><br />';
	} 
}
?>
	