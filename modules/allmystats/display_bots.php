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
 $when sert pour by day - $mois pour by month

*/
	// ---------------- Should not be called directly -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/'.FILENAME_DISPLAY_BOTS ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------
	
require_once(dirname(__FILE__).'/config_add.php'); // for other bots !preg_match($detect_bad_by_reverseDNS, $row['agent'])  
	
	if(!isset($when)) { // include from stats in
		$when = ''; 
	}

	//######################################################################################
	//							LISTE ROBOTS
	//######################################################################################

	if(isset($time_test) && $time_test == true) {
		$start = (float) array_sum(explode(' ',microtime()));  		
	}

	if(!isset($when_date)) { $when_date = ''; } // if admin bots
	if(!isset($Total_pages_bots)) { $Total_pages_bots = 0; }
	if(!isset($Total_distinct_ip_bots)) { $Total_distinct_ip_bots = 0; }

if (!isset($_SESSION['other_bot']) || $affiche_only_other_bots <> true) { //Pour accelerer lors de l'ajout des robots

		// If is in if $Tab_user_agent --> not display in Others bot
		$result_agent = mysql_query("select user_agent from ".TABLE_BAD_USER_AGENT.""); 
		while($row = mysql_fetch_array($result_agent)){
			$Tab_user_agent[] = $row['user_agent'];
		}

		if(isset($_SESSION['other_bot'])) {
			unset($_SESSION['other_bot']);
		}

		$result_1 = mysql_query("select count(ip) as nb_distinct_IP, agent, sum(visits) as nb_visits_agent from ".TABLE_UNIQUE_BOT." where date like '%".$when_date."' GROUP BY agent");

		$Total_distinct_robots = 0;
		$unique_bot_name = '';
		while($row = mysql_fetch_array($result_1)){
			$crawler_result = is_crawler($row['agent']);

			$result_crawler = mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER." where bot_name='".$crawler_result."'"); 
			$crawler = mysql_fetch_array($result_crawler);
			$field = $crawler['bot_name'];
			
			$Total_pages_bots = $Total_pages_bots + $row['nb_visits_agent'];
			$Total_distinct_ip_bots = $Total_distinct_ip_bots + $row['nb_distinct_IP'];

			$crawler_info = '<br />'.stripslashes(($crawler['crawler_info']));
			if ($crawler['crawler_url']) {
				$Lien_url = '<a href="'.$crawler['crawler_url'].'" target="_blank">'.$crawler['org_name'].'</a>';
			} else {
				$Lien_url = $crawler['org_name'];
			}

			if($crawler['bot_name']) {
				if(@strstr($unique_bot_name, $crawler['bot_name'])) { // agents différents mais même bot
					$bot_visits = $Tab_bot[$field][2] + $row['nb_visits_agent'];
					$bot_ip = $Tab_bot[$field][3] + $row['nb_distinct_IP'];
					$Tab_bot[$field] = array($Lien_url.$crawler_info, $crawler['bot_name'], $bot_visits, $bot_ip);
				} else {
					$Total_distinct_robots++;
					$Tab_bot[$field] = array($Lien_url.$crawler_info, $crawler['bot_name'], $row['nb_visits_agent'], $row['nb_distinct_IP']);
				}

			} else {
				if(!in_array($row['agent'], $Tab_user_agent) && !preg_match($detect_bad_by_reverseDNS, $row['agent'])) { // if configure $detect_bot_on_reverseDNS (config_add.php) before and put after in $detect_bad_by_reverseDNS
					$Other_bot[] = $row['agent'];

					if(!isset($Tab_bot['other_bot'][2])) { $Tab_bot['other_bot'][2] = 0; }
					if(!isset($Tab_bot['other_bot'][3])) { $Tab_bot['other_bot'][3] = 0; }

					$bot_visits = $Tab_bot['other_bot'][2] + $row['nb_visits_agent'];
					$bot_ip = $Tab_bot['other_bot'][3] + $row['nb_distinct_IP'];

					$Tab_bot['other_bot'] = array(MSG_OTHER_BOTS, '-', $bot_visits, $bot_ip, '');
				}
			}
	
			$unique_bot_name .= $crawler['bot_name']."+-+";
		}

		$show_page_os_nav_robots = '';
	
	###############################################################################
	############################## Liste Robots ###################################
	if ($display_bots == true) {
		
		if(!isset($StatsIn_in_prot_dir)) { $StatsIn_in_prot_dir = ''; } // Si n'est pas include de stats_in
		
		$display_bots = false;
		$affiche_only_other_bots = false;
		
		$show_page_os_nav_robots .= '
	<table style="'.$table_border_CSS.'">
	  <tr>
		<td>
		  <table style="'.$table_frame_CSS.'">
				<tr>
					<td style="width:5%; white-space:nowrap;">'; 
					if (isset($StatsIn_in_prot_dir) && isset($StatsIn_in_prot_dir) <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
						$show_page_os_nav_robots .= '
							&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_bots.gif" height="32px" alt="'.MSG_BOT_VISITS.'" title="'.MSG_BOT_VISITS.'">';
					}
					
					$show_page_os_nav_robots .= '
					</td>
					<td style="'.$table_title_CSS.'">'.MSG_BOT_VISITS.'</td>
				</tr>
				
				<tr>
					<td colspan="2">
						<table style="'.$table_data_CSS.'">
						  <tr>
							<th style="'.$td_data_CSS.' width:40%">'.MSG_BOT_PARENT_NAME.'</th>
							<th style="'.$td_data_CSS.'">'.MSG_BOT_NAME.'</th>
							<th style="'.$td_data_CSS.'">'.MSG_BOTS_NB_PAGES_SCANNED.'</th>
							<th style="'.$td_data_CSS.'">'.MSG_NB_DISTINCT_IP.'</th>
							<th style="'.$td_data_CSS.'">'.MSG_PERCENTAGE_VISITORS_BOTS.'</th>
						  </tr>';
		
			@usort($Tab_bot,"CompareValeurs");
					
			// Obtient une liste de colonnes
			unset($link);
			unset($botname);
			unset($nbVisits);
			unset($nbIP);
			if (isset($Tab_bot)) {
				foreach ($Tab_bot as $key => $row) {
					$link[$key]  = $row[0];
					$botname[$key] = $row[1];
					$nbVisits[$key]  = $row[2];
					$nbIP[$key] = $row[3];
				}
			}
			@array_multisort ($nbVisits, SORT_DESC, $Tab_bot); 
		
			for($i = 0; $i < count($Tab_bot); $i++){
				$Tab_bot[$i][4] = (@bcdiv($Tab_bot[$i][2], $Total_pages_bots,4)*100); //percents
		
				if ($Tab_bot[$i][2] != 0)  {
					$show_page_os_nav_robots .= "
					<tr>
						<td style=\"".$td_data_CSS."\">".$Tab_bot[$i][0]."</td>
						<td style=\"".$td_data_CSS." text-align: center;\"> ".stripslashes($Tab_bot[$i][1])."</td>
						<td style=\"".$td_data_CSS." text-align: center;\"> ".$Tab_bot[$i][2]."</td>
						<td style=\"".$td_data_CSS." text-align: center;\"> ".$Tab_bot[$i][3]."</td>
						<td style=\"".$td_data_CSS." text-align: center;\"> ".$Tab_bot[$i][4]."%</td>
					</tr>";
				}
			}
		
			$show_page_os_nav_robots .= "
			<tr>
				<td style=\"".$td_data_CSS." text-align: right;\"><br /><strong>Total: &nbsp;</strong><br /><center>".MSG_BOTS_KNOW_IN_DATABASE.$Total_distinct_robots."</center></td>
				<td style=\"".$td_data_CSS." text-align: center;\">-</td>
				<td style=\"".$td_data_CSS." text-align: center;\">".$Total_pages_bots."</td>
				<td style=\"".$td_data_CSS." text-align: center;\">".$Total_distinct_ip_bots."</td>
				<td style=\"".$td_data_CSS." text-align: center;\">
					&nbsp;
				</td>
			</tr>";
		
			//############################## Autres robots ########################################
		
				if (isset($Other_bot)) { 
					$Other_bot = array_unique($Other_bot);
					@usort($Other_bot,"CompareValeurs");
					$show_page_os_nav_robots .= "
					<tr>
						<td colspan=\"5\" style=\"".$td_data_CSS." white-space: nowrap;\"><br /><center><strong>New: ".MSG_DETAILS_UNKNOWN_BOTS." ".$when_date."</strong></center><br />";
					for($i = 0; $i < count($Other_bot); $i++){
						$show_page_os_nav_robots .= '['.$Other_bot[$i].']<br />';
					}
					$show_page_os_nav_robots .= '</td></tr>';
				}
		
		$show_page_os_nav_robots .= "
				</table>
					</td>
					</tr>
					<tr>
					<td colspan=\"2\" style=\"text-align: center;\">
					<form name=\"GestionCrawler\" method=\"post\" action=\"".FILENAME_INDEX_FRAME."\">
						<input name=\"type\" type=\"hidden\" value=\"add_crawler\">
						<input name=\"when\" type=\"hidden\" value=\"".$when."\">
						<input name=\"mois\" type=\"hidden\" value=\"".$mois."\">";
					if(isset($dislpay_button_tool_bots) && $dislpay_button_tool_bots <> "false") {
						$show_page_os_nav_robots .= "
						<input class=\"submitDate\" name=\"SubmitGestionCrawler\" type=\"submit\" value=\"".MSG_ADMIN_TOOLS_BOTS."\" alt=\"".MSG_ADMIN_TOOLS_BOTS."\" title=\"".MSG_ADMIN_TOOLS_BOTS."\">";
					}
		$show_page_os_nav_robots .= "	
					</form>
					</td>
					</tr>

	</table>
	</td></tr></table>
	</td></tr>
	</table><br />";

		//############################################################################################################################
		//############################################################################################################################
		
	} // End if ($display_bots==true) {

	//############################## Affiche seulement les robots non référencés dans la base ########################################
} //End if (!$_SESSION['other_bot'] || $affiche_only_other_bots<>true) {

	if ($affiche_only_other_bots == true) {
		if (isset($Other_bot)) {
			$_SESSION['other_bot'] = $Other_bot;
		}
		
if(!isset($show_page_os_nav_robots)) { $show_page_os_nav_robots = ''; } // TODO see if .= is necessary and change this variable name if possible

$show_page_os_nav_robots .= '
		<a name="focusforminsert"></a>
		<table style="border: 0px; margin-left: auto; margin-right: auto;">';

					//------------------------ Insert crawler ----------------------------------
					if (isset($InsertCrawler)){
						$show_page_os_nav_robots .= '
						<tr><td>
							<table style="'.$table_border_CSS.'">
							  <tr>
								<td style="'.$table_title_CSS.'">
								  <table style="'.$table_frame_CSS.'">
								<tr>
								 <th style="'.$table_title_CSS.'">
										<form name="form1" method="post" action="'.FILENAME_INDEX_FRAME.'">
											<table style="'.$table_data_CSS.'">
											  <tr>
												<th style="'.$td_data_CSS.'">'.MSG_BOT_NAME.'</th>
												<th style="'.$td_data_CSS.'">'.MSG_ADMIN_BOT_PARENT_NAME.'</th>
												<th style="'.$td_data_CSS.'">'.MSG_TOOLS_BOT_URL.'</th>
												<th style="'.$td_data_CSS.'">'.MSG_COMMENTS.'</th>
											  </tr>
											  <tr>
												<td align="center"><input name="BotName" type="text" size="20"></td>
												<td align="center"><input name="BotParentName" type="text"></td>
												<td align="center"><input name="BotUrl" type="text"></td>
												<td align="center"><input name="BotComment" type="text"></td>
											  </tr>
										  </table>
											<input name="type" type="hidden" value="add_crawler">
											<input name="when" type="hidden" value="'.$when.'">
											<input name="mois" type="hidden" value="'.$mois.'">
											<input class="submitDate" name="DoInsertCrawler" type="submit" value="'.MSG_ADD.'" alt="'.MSG_ADD.'" >&nbsp;&nbsp;
											<input class="submitDate" name="AnnulerInsertCrawler" type="submit" value="'.MSG_CANCEL.'" alt="'.MSG_CANCEL.'" >
										</form>
								 </th>
						</tr>
		  				</table></td></tr></table><br>
						';
				 }

		//------ Pour formulaire insert robot permanent -------
		if ($_SESSION['other_bot'] ) { // Ligne déplacée
		//-----------------------------------------------------
			$_SESSION['other_bot'] = array_unique($_SESSION['other_bot']);
			@usort($_SESSION['other_bot'], "CompareValeurs"); //supprime champ vide
			$robots = $_SESSION['other_bot'] ;
			array_multisort ($robots, SORT_ASC); // Ne fonctionne pas sur tableau session
			$show_page_os_nav_robots .= "
			<tr>
			<td colspan=\"5\" style=\"".$table_data_CSS." white-space: nowrap;\">
				<strong><center><br>New: ".MSG_DETAILS_UNKNOWN_BOTS." ".$when_date."</center></strong><br>";
			for($i=0; $i < count($robots); $i++){
				$show_page_os_nav_robots .= '['.$robots[$i].']<br>';
			}
			$show_page_os_nav_robots .= '
			</td></tr>';
		} else {
			$show_page_os_nav_robots .= "<div align=\"center\"><strong><big><font color=#FF0000>".MSG_ADMIN_TOOLS_NO_UNKNOWN_BOT."</font></big></strong></div>";
		}
		//-------------------------------------------------------------------------

		$show_page_os_nav_robots .= '
			</table>
			<br>';

	}
		
	if(isset($time_test) && $time_test == true) {
		$end = (float) array_sum(explode(' ',microtime()));  
		echo '<pre>										Liste BOTS Traitement : '.sprintf("%.4f", $end-$start) . ' sec</pre>';
	}

?>	