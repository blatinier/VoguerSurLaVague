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
 $when sert pour by day - $mois pour by month

11-01-2011
Bug light
Certains user agent se mettent dans la table allmystats_unique_bot sur les sites à fort visites (> 4000 pages/jour)
ex : 
[Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 ( .NET CLR 3.5.30729)]
[Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12]
[Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13]
[Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SIMBAR={FE7B57FB-3B79-46D0-90CB-44DB0194E81D}; GTB6.6; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; CPNTDF; .NET4.0C)]
[Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; FunWebProducts; GTB6.6; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.21022; .NET CLR 3.5.30729; InfoPath.2; .NET CLR 3.0.30729; .NET4.0C)]
[Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6.6; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)]
[Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; InfoPath.1; .NET CLR 3.5.30729; .NET CLR 3.0.30729; OfficeLiveConnector.1.3; OfficeLivePatch.0.0; Orange 8.0)]

A VERIFIER SI RECOMMENCE
*/
	// ---------------- Ne doit pas être appelé directement -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/display_bots.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

		//-----------------------------------------------------------------------------
		//Mise en en forme ($AllBots) pour preg_match des bot connus (dans la table + bot en général (bot, spider , etc)
		$result1 = mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER.""); 
		$AllBots = '/Bot|Slurp|Scooter|Spider|crawl|'; //del Agent because error on user agent
		while($row=mysql_fetch_array($result1)){
			$Form_chaine = str_replace('/','\/',$row['bot_name']);
			$Form_chaine = str_replace('+','\+',$Form_chaine);
			$Form_chaine = str_replace('(','\(',$Form_chaine);
			$Form_chaine = str_replace(')','\)',$Form_chaine);
			$AllBots .= $Form_chaine.'|';
		}
		$AllBots = substr($AllBots,0,strlen($AllBots)-1); //delete last "|"
		$AllBots .= '/i';
	//-------------------------------------------------------------------------

	//######################################################################################
	//							LISTE ROBOTS
	//######################################################################################

		if($time_test == true) {
			$start = (float) array_sum(explode(' ',microtime()));  		
		}
	
if (!$_SESSION['other_bot'] || $affiche_only_other_bots <> true) { //Pour accelerer lors de l'ajout des robots

		unset($_SESSION['other_bot']);

		$result_1 = mysql_query("select count(ip) as nb_distinct_IP, agent, sum(visits) as nb_visits_agent from ".TABLE_UNIQUE_BOT." where date like '%".$when_date."' GROUP BY agent");

		$Total_distinct_robots = 0;
		$i = 0;
		while($row = mysql_fetch_array($result_1)){
			preg_match($AllBots, $row['agent'], $matches);
			
			$result_crawler = mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER." where bot_name='".$matches[0]."'"); 
			$crawler = mysql_fetch_array($result_crawler);
			$i = $crawler['bot_name'];
			
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
					$bot_visits = $Tab_bot[$i][2] + $row['nb_visits_agent'];
					$bot_ip = $Tab_bot[$i][3] + $row['nb_distinct_IP'];
					$Tab_bot[$i] = array($Lien_url.$crawler_info, $crawler['bot_name'], $bot_visits, $bot_ip);
				} else {
					$Total_distinct_robots++;
					$Tab_bot[$i] = array($Lien_url.$crawler_info, $crawler['bot_name'], $row['nb_visits_agent'], $row['nb_distinct_IP']);
				}

			} else {
				$Other_bot[] = $row['agent'];
				
				$bot_visits = $Tab_bot['other_bot'][2] + $row['nb_visits_agent'];
				$bot_ip = $Tab_bot['other_bot'][3] + $row['nb_distinct_IP'];
				
				$Tab_bot['other_bot'] = array(MSG_OTHER_BOTS, '-', $bot_visits, $bot_ip, '');
			}
	
			$unique_bot_name .= $crawler['bot_name']."+-+";
		}


	###############################################################################
	############################## Liste Robots ###################################
	if ($display_bots == true) {

		$display_bots = false;
		$affiche_only_other_bots == false;
		
		$show_page_os_nav_robots .= '
	<table style="'.$table_border_CSS.'">
	  <tr>
		<td>
		  <table style="'.$table_frame_CSS.'">
				<tr>
					<td style="width:5%; white-space:nowrap;">'; //width: %; in px ne fonctionne pas
					if ($StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
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
			if ($Tab_bot) {
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
		
				if ($Other_bot) { 
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
					if($dislpay_button_tool_bots<>"false") {
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
		
	} //Fin de if ($display_bots==true) {

	//############################## Affiche seulement les robots non référencés dans la base ########################################
} //End if (!$_SESSION['other_bot'] || $affiche_only_other_bots<>true) {

	if ($affiche_only_other_bots == true) {
		if ($Other_bot) {
			$_SESSION['other_bot'] = $Other_bot;
		}
		
$show_page_os_nav_robots .= '
		<a name="focusforminsert"></a>
		<table style="border: 0px; margin-left: auto; margin-right: auto;">';

//		if ($_SESSION['other_bot']) { //Commenter cette ligne pour insert robot permanent
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
		
	if($time_test == true) {
		$end = (float) array_sum(explode(' ',microtime()));  
		echo '<pre>										Liste BOTS Traitement : '.sprintf("%.4f", $end-$start) . ' sec</pre>';
	}

?>	