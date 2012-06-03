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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/display_bads_agents.php' ){ 
		header('Location: index.php'); //Si appelle direct de la page redirect
	}
	// -----------------------------------------------------------------------

if($display_bad_user_agent) {

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

	//NOTE: peut avoir plusieurs enregistrements avec même ip date mais avec code différent (se suivent) par contre TABLE_UNIQUE_BAD_AGENT est OK ?? pourquoi --> rare
	$result = mysql_query("select agent, type, ip, reverse_dns, sum(visits) as nb_pages_visits, country from ".TABLE_UNIQUE_BAD_AGENT." where date like '%".$when_date."' GROUP BY ip"); //$when_date = d/m/Y or m/Y suivant appelle
	$nb_distinct_ip_bad_agent = mysql_num_rows($result);

	if ($nb_distinct_ip_bad_agent > 0) {
		$show_page_os_nav_robots .= "
	<table style=\"".$table_border_CSS."\">
	  <tr>
		<td>
		  <table style=\"".$table_frame_CSS."\">
					<tr>";
		 			
					if ($StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed 
				  		$show_page_os_nav_robots .= "
						<td style=\"width:5%; white-space:nowrap;\">
							&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/icon_matrix_agent.gif\" style=\"vertical-align:middle\" alt=\"".MSG_USER_AGENT."\" title=\"".MSG_USER_AGENT."\">
						</td>";
					}
					
					$show_page_os_nav_robots .= "
					<th style=\"".$table_title_CSS."\">
						".MSG_USER_AGENT."&nbsp;&nbsp;<small><font style=\"font-weight:lighter\">Total: ".$nb_distinct_ip_bad_agent."<br />".MSG_USER_AGENT_SPAM." - ".MSG_USER_AGENT_UNKNOWN." - ".MSG_USER_AGENT_OTHER."</font></small>
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
							<th style=\"".$td_data_CSS." text-align: center;\">".MSG_COUNTRY."</th>
							<th style=\"".$td_data_CSS." text-align: center;\">".MSG_COMMENTS."</th>
						  </tr>";
		
						while($row = mysql_fetch_array($result)){
							//Country flag
							$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
							$record_code = geoip_country_code_by_addr($handle, $row['ip']); 
							@geoip_close($handle); //GEOIP n'est plus appelé --> on ferme $handle
						
							//if (file_exists("images/flags/".strtolower($record_code).".png")) { //Pb avec stats_in et file_exist en path absolu - "images/flags/" relative path else Warning: file_exists() [function.file-exists]: open_basedir restriction in effect.
								$Country_flag = "<img src=\"".$path_allmystats_abs."images/flags/".strtolower($record_code).".png\" height=\"10\" width=\"14\" alt=\"".$row['country']."\" title=\"".$row['country']."\">";
							//} else {
								//$Country_flag = $row['country'];
							//}
						
							$result_comments = mysql_query("select info from ".TABLE_BAD_USER_AGENT." where user_agent='".$row['agent']."'");
							$row_comment = mysql_fetch_array($result_comments);  
							
							if ($row['type']== 'S') {
								$show_page_os_nav_robots .= "
								<tr>
								<td style=\"".$td_data_CSS." white-space: nowrap;\">
									<font color=\"#FF0000\">".$row['agent']."</font>
								</td>
								<td style=\"".$td_data_CSS." text-align: center;\"> 
									".$row['type']."
								</td>
								<td style=\"".$td_data_CSS." text-align: center;\">
									".$row['ip']."</td>
								<td style=\"".$td_data_CSS." text-align: center;\">
									".$row['nb_pages_visits']."</td>
								<td style=\"".$td_data_CSS."\">
									".$row['reverse_dns']."
								</td>
								<td style=\"".$td_data_CSS."\">
									".$Country_flag." ".$row['country']."
								</td>
								<td style=\"".$td_data_CSS."\">
									".$row_comment['info']."
								</td>
								</tr>";
							} else {
								$show_page_os_nav_robots .= "
								<tr>
								<td style=\"".$td_data_CSS." white-space: nowrap;\">
									".$row['agent']."
								</td>
								<td style=\"".$td_data_CSS." text-align: center;\"> 
									".$row['type']."
								</td>
								<td style=\"".$td_data_CSS." text-align: center;\"> 
									".$row['ip']."
								</td>
								<td style=\"".$td_data_CSS." text-align: center;\"> 
									".$row['nb_pages_visits']."
								</td>
								<td style=\"".$td_data_CSS."\"> 
									".$row['reverse_dns']."
								</td>
								<td style=\"".$td_data_CSS."\"> 
									".$Country_flag." ".$row['country']."
								</td>
								<td style=\"".$td_data_CSS."\"> 
									".$row_comment['info']."
								</td>
								</tr>";
							}
						}
		
		$show_page_os_nav_robots .= '
			</table></td></tr></table></td></tr></table><br />';
	} 
}
?>
	