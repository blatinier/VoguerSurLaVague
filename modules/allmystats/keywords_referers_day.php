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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/keywords_referers_day.php' ){ 
		header('Location: index.php'); //Si appelle direct de la page redirect
	}
	// ------------------------------------------------------------------------

if ($included_in_page_charset == ''){  //peut venir de monthly archives_edit.php or stats_in.php
	$included_in_page_charset = 'utf-8';
}
if ($display_adword_links == ''){ //peut venir de main.php or stats_in.php
	$display_adword_links = 'true';
}

	########################################################################################
	//						KEYWORDS DAYS
	########################################################################################

// start time  
//$start = (float) array_sum(explode(' ',microtime()));  

		//------------------ Affichage tableau keywords -----------------------------
		//Total different keywords
		//$total_keyword = mysql_query("select COUNT(*) from ".TABLE_DAYS_KEYWORDS." where date='".$when_day."' and keyword<>'TOTAL_VISITS'");
		//$total_differents_keyword = mysql_fetch_row($total_keyword); // result = $total_differents_keyword[0]

		//24-10-2011 OK
		$total_keyword = mysql_query("SELECT DISTINCT SUBSTRING_INDEX(keyword, '|;|', 1) from ".TABLE_DAYS_KEYWORDS." where date='".$when_day."' and keyword<>'TOTAL_VISITS'");
		$total_differents_keyword = mysql_num_rows($total_keyword); 

		// ---------------------------
	
		$show_cumul_page .= "
<table style=\"".$table_border_CSS."\">
  <tr>
    <td>
      <table style=\"".$table_frame_CSS."\">
        <tr>
		<td style=\"width:5%; white-space:nowrap;\">"; //width: %; in px ne fonctionne pas
		 if ($StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
         	$show_cumul_page .= "
				&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."/images/icons/icon_keywords.gif\" height=\"32px\" alt=\"".MSG_REFERERS_AND_KEYWORDS."\" title=\"".MSG_REFERERS_AND_KEYWORDS."\">";
		 }

		 $show_cumul_page .= "
		  </td>
		  <td style=\"".$table_title_CSS."\">". MSG_REFERERS_AND_KEYWORDS. ' '. $when_day."<br>
		  	<small><font style=\"font-weight:lighter\">".MSG_TOTAL_DIFFERENT_KEYWORDS.": ".$total_differents_keyword."</font></small>
		  </td>
			<td>";		  
				//Display button short page
				if(!$switch_list_button && !$switch_short_complete_list && !$do_archives && !$public_StatsIn) {
				 $show_cumul_page .= "
					<form name=\"listekeywords\" method=\"post\" action=\"".FILENAME_INDEX_FRAME."\">
						<input type=\"hidden\" name=\"type\" value=\"\">
						<input type=\"hidden\" name=\"when\"  value=\"".$when_day."\">
						<input type=\"hidden\" name=\"limit_keywords\" value=\"".$limit_keywords."\">
						<input type=\"hidden\" name=\"limit_pages\" value=\"".$limit_pages."\">
						<input class=\"submit\" name=\"submit_limit_keywords\" type=\"submit\" value=\"".$value_button_keywords."\" alt=\"".$value_button_keywords."\" title=\"".$value_button_keywords."\">
					</form>";
				}	  

		$show_cumul_page .= "
        	</td>
			</tr>
        	<tr>
				<td colspan=\"3\">
            		<table style=\"".$table_data_CSS."\">
					<tr>
						<th style=\"".$td_data_CSS." text-align: center;\">".MSG_REFERERS."</th>
						<th style=\"".$td_data_CSS." text-align: center;\">".MSG_KEYWORDS."</th>
						<th style=\"".$td_data_CSS." text-align: center;\">".MSG_VISITORS."</th>
					</tr>";		

		// referer (unique) tri par keyword='TOTAL_VISITS'
		$referer = mysql_query("select host_referer, keyword, nb_item from ".TABLE_DAYS_KEYWORDS." where date='".$when_day."' and keyword='TOTAL_VISITS'");
		unset($tri_Tab_aff_ref);
		while($row = mysql_fetch_array($referer)){
			$tri_Tab_aff_ref[] = array($row['nb_item'], $row['host_referer'], $row['keyword']); //dans cet ordre car on tri sur nb visites
		}
		@array_multisort($tri_Tab_aff_ref,SORT_DESC);

		$count = count($tri_Tab_aff_ref); 
		for($i = 0; $i < $count; $i++){
			//Note : Environ 0.0049 sec / result
			$result = mysql_query("select keyword, nb_item from ".TABLE_DAYS_KEYWORDS." where host_referer='".trim($tri_Tab_aff_ref[$i][1])."' and date='".$when_day."' order by nb_item DESC, keyword ASC ".$limit_keywords."");
			//$distinct_keyword_count = mysql_num_rows($result)-1; // -1 car TOTAL_VISITS est compté //Nb different keywords for this referer
			//--------------------------------								
			//25-10-2011 OK - keyword pos page et donc même keyword with different position possible
			$total_keyword = mysql_query("SELECT DISTINCT SUBSTRING_INDEX(keyword, '|;|', 1) from ".TABLE_DAYS_KEYWORDS."  where host_referer='".trim($tri_Tab_aff_ref[$i][1])."' and date='".$when_day."'");
			$distinct_keyword_count = mysql_num_rows($total_keyword)-1; // -1 car TOTAL_VISITS est compté //Nb different keywords for this referer
			//-----------------------------
			//Longueur Affichage Referer
			$lenmax = 35;
			if (strlen($tri_Tab_aff_ref[$i][1]) > $lenmax) {
				$chaine1 = substr($tri_Tab_aff_ref[$i][1], 0, $lenmax);
				$chaine2 = substr($tri_Tab_aff_ref[$i][1], $lenmax);
				$tri_Tab_aff_ref[$i][1] = $chaine1."<br>".$chaine2;
			} elseif ($tri_Tab_aff_ref[$i][1] == 'MSG_UNKNOWN_OR_DIRECT' || $tri_Tab_aff_ref[$i][1] == 'Unknown or direct') {
				$tri_Tab_aff_ref[$i][1] = MSG_UNKNOWN_OR_DIRECT;
			}	
			//-----------------------------

			$show_cumul_page .= "
				<tr>
				<td style=\"".$td_data_CSS." text-align: left; vertical-align: top;\">
					".$tri_Tab_aff_ref[$i][1]." 
				</td>
				<td style=\"".$td_data_CSS." text-align: left; vertical-align: top; white-space: nowrap;\">";
				
/*				
				//25-10-2011
			    if ($distinct_keyword_count <> 0) {
					if ($distinct_keyword_count > $first_limit_keywords && $switch_short_complete_list) {
						$show_cumul_page .= MSG_DIFFERENTS.": ".$distinct_keyword_count." (limited to : ".$limit_keywords.")<br>";
						$switch_list_button = true;
					} elseif ($distinct_keyword_count > $complete_list_limit_keywords && !$switch_short_complete_list) {
						$show_cumul_page .= MSG_DIFFERENTS.": ".$distinct_keyword_count." (limited to : ".$limit_keywords.")<br>";
						$switch_list_button = true;
					} else {
						$show_cumul_page .= MSG_DIFFERENTS.": ".$distinct_keyword_count."<br>";
					}
				} else {
					$show_cumul_page .= "&nbsp;";
				}
*/
				// -----------------------------
				//25-10-2011
				//'LIMIT '.$val
				$val_limit_keywords = substr($limit_keywords,6);

			    if ($distinct_keyword_count <> 0) {
					if ($distinct_keyword_count > $val_limit_keywords) { // && $switch_short_complete_list
						$show_cumul_page .= MSG_DIFFERENTS.": ".$distinct_keyword_count." (limited to : ".$val_limit_keywords.")<br>";
						$switch_list_button = true;
					} else {
						$show_cumul_page .= MSG_DIFFERENTS.": ".$distinct_keyword_count."<br>";
					}
				} else {
					$show_cumul_page .= "&nbsp;";
				}
				// -----------------------------

				$nb_characters = 60; //org 60 - Number of characters for keywords and referrers
				mysql_data_seek($result,0);
				while($row = mysql_fetch_array($result)){
					//TODO function or ... same in keyword_referers_day.php, stats_in.php (sauf $show_cumul_page .= )
					if($row['keyword'] <> 'TOTAL_VISITS') {

						//24-10-2011
						$delimiter = '|;|';
						$exp_keyword = explode($delimiter, $row['keyword']);
						$keyword = $exp_keyword[0];
						$keyword_position = '';
						if ($exp_keyword[1] && $exp_keyword[2]) {
							$keyword_position = '(pos '.$exp_keyword[1].' page '.$exp_keyword[2].')';
						} elseif ($exp_keyword[1]) {
							$keyword_position = '(pos '.$exp_keyword[1].')';
						} elseif ($exp_keyword[2]) {
							$keyword_position = '(page '.$exp_keyword[2].')';
						}

						if ($included_in_page_charset == 'iso-8859-1') {						

							if (strstr($row['keyword'],'(AdWords Keyword)')) {	//Mot clé avec tag (AdWords Keyword) - Tag inséré dans visiteur.php --> $row['keyword'] pour compatibility whith delimiter
								//$Keyword = str_replace("(AdWords Keyword)", '', $row['keyword']); // $row['keyword'] pour compatibility whith delimiter
								if (strlen($keyword.strip_tags(MSG_ADWORDS_KEYWORD)) > $nb_characters) { 
									$keyword = substr($keyword, 0, $nb_characters-(strlen(strip_tags(MSG_ADWORDS_KEYWORD))+3)).'...'; 
								}
								$show_cumul_page .=  "
								<font color='#990000'>".stripslashes(utf8_decode($keyword))."</font>&nbsp;&nbsp;(".MSG_ADWORDS_KEYWORD.")<br>"; 
							
							} elseif (strstr($keyword,'googlesyndication=1') || strstr($keyword,'googlesyndicReseauGCLID=1')) { // (Adwords Réseau de Contenu)
								$aff_referer = utf8_decode($keyword);
								//Ne fonctionne pas avec le $row['keyword'] directement
								$aff_referer = str_replace("?googlesyndication=1", '', $aff_referer); 
								$aff_referer = str_replace('&googlesyndication=1', '', $aff_referer);
								$aff_referer = str_replace("?googlesyndicReseauGCLID=1", '', $aff_referer);
								$aff_referer = str_replace('&googlesyndicReseauGCLID=1', '', $aff_referer);

								$link = $aff_referer;
								if (strlen($aff_referer.strip_tags(MSG_ADWORDS_CONTENT_NETWORK)) > $nb_characters) { $aff_referer = substr($aff_referer, 0, $nb_characters-strlen(strip_tags(MSG_ADWORDS_CONTENT_NETWORK))).'...'; } 

								if($display_adword_links == 'true') {
									$show_cumul_page .= "
									<a href=\"$link\" target=\"_new\">".urldecode($aff_referer)."</a>&nbsp;&nbsp;(".MSG_ADWORDS_CONTENT_NETWORK.")<br>";
								} else {
									$show_cumul_page .= urldecode($aff_referer)."
									&nbsp;&nbsp;(".MSG_ADWORDS_CONTENT_NETWORK.")<br>";								
								}

							} elseif (strstr($tri_Tab_aff_ref[$i][1],'webcache.googleusercontent.com')) { // Cache Google ou tester on strstr($row['keyword'],'cache:')
								//eg : [cache:g42q5dvv7vYJ:www.site.tld/path keyword1 keyword2 3 4&cd=10&hl=fr&ct=clnk&gl=fr]
								$Keyword_cache = explode('&',$keyword);
								$Keyword_cache = explode(' ',$Keyword_cache[0],2);
								if (strlen($Keyword_cache[1].strip_tags('Google Cache')) > $nb_characters) { 
									$Keyword_cache[1] = substr($Keyword_cache[0], 0, $nb_characters-strlen(strip_tags('Google Cache'))).'...'; 
								} 
								$show_cumul_page .= "
								<font color='#990000'>[".stripslashes($Keyword_cache[1])."]</font>&nbsp;&nbsp;(Google Cache)<br>"; 

							} else { // Normal
								if (strlen(strip_tags($keyword)) > $nb_characters) { 
									$keyword = substr($keyword, 0, $nb_characters).'...'; 
								} 
								$show_cumul_page .= "
								<font color='#990000'>".stripslashes(utf8_decode($keyword))."</font> <font color='#000000'>".$keyword_position."</font><br>";
							}

						} else { //utf-8
							//TODO function or ... same in keyword_referers_day.php, monthly_archives_edit.php (sauf $show_cumul_page .= )
							if (strstr($row['keyword'],'(AdWords Keyword)')) {	//Mot clé avec tag (AdWords Keyword) - Tag effectué dans visiteur.php --> $row['keyword'] pour compatibility whith delimiter
								//$Keyword = str_replace("(AdWords Keyword)", '', $row['keyword']); // $row['keyword'] pour compatibility whith delimiter
								//if (mb_strlen($Keyword.strip_tags(MSG_ADWORDS_KEYWORD),'utf-8') > $nb_characters) { 
								if (mb_strlen(strip_tags($keyword.MSG_ADWORDS_KEYWORD),'utf-8') > $nb_characters) { 
									$keyword = mb_substr($keyword, 0, $nb_characters-(mb_strlen(strip_tags(MSG_ADWORDS_KEYWORD),'utf-8')+3),'utf-8').'...'; 
								} 

								$show_cumul_page .=  "
								<font color='#990000'>".stripslashes($keyword)."</font>&nbsp;&nbsp;(".MSG_ADWORDS_KEYWORD.")<br>"; 

							} elseif (strstr($keyword,'googlesyndication=1') || strstr($keyword, 'googlesyndicReseauGCLID=1')) { // (Adwords Réseau de Contenu)
								$aff_referer = utf8_decode($keyword);
								//Ne fonctionne pas avec le $row['keyword'] directement
								$aff_referer = str_replace("?googlesyndication=1", '', $aff_referer); 
								$aff_referer = str_replace('&googlesyndication=1', '', $aff_referer);
								$aff_referer = str_replace("?googlesyndicReseauGCLID=1", '', $aff_referer);
								$aff_referer = str_replace('&googlesyndicReseauGCLID=1', '', $aff_referer);

								$link = $aff_referer;
								//if (mb_strlen($aff_referer.strip_tags(MSG_ADWORDS_CONTENT_NETWORK),'utf-8') > $nb_characters) {
								if (mb_strlen(strip_tags($aff_referer.MSG_ADWORDS_CONTENT_NETWORK),'utf-8') > $nb_characters) { 
									$aff_referer = mb_substr($aff_referer, 0, $nb_characters-mb_strlen(strip_tags(MSG_ADWORDS_CONTENT_NETWORK),'utf-8'),'utf-8').'...'; 
								} 

								if($display_adword_links == 'true') {
									$show_cumul_page .= "
									<a href=\"".htmlspecialchars($link)."\" target=\"_new\">".urldecode($aff_referer)."</a>&nbsp;&nbsp;(".MSG_ADWORDS_CONTENT_NETWORK.")<br>";
								} else {
									$show_cumul_page .= 
									urldecode($aff_referer)."&nbsp;&nbsp;(".MSG_ADWORDS_CONTENT_NETWORK.")<br>";								
								}

							} elseif (strstr($tri_Tab_aff_ref[$i][1],'webcache.googleusercontent.com')) { // Cache Google ou tester on strstr($row['keyword'],'cache:')
								//eg : [cache:g42q5dvv7vYJ:www.site.tld/path keyword1 keyword2 3 4&cd=10&hl=fr&ct=clnk&gl=fr]
								$Keyword_cache = explode('&',$keyword);
								$Keyword_cache = explode(' ',$Keyword_cache[0],2);
								if (mb_strlen($Keyword_cache[1].strip_tags('Google Cache'),'utf-8') > $nb_characters) {
									$Keyword_cache[1] = mb_substr($Keyword_cache[0], 0, $nb_characters-mb_strlen(strip_tags('Google Cache'),'utf-8'),'utf-8').'...'; 
								} //mb_ pour utf8
								$show_cumul_page .= "
								<font color='#990000'>[".stripslashes($Keyword_cache[1])."]</font>&nbsp;&nbsp;(Google Cache)<br>"; 

							} else { // Normal
								if (mb_strlen($keyword,'utf-8') > $nb_characters) { 
									$keyword = mb_substr($keyword, 0, $nb_characters,'utf-8').'...';
								} //mb_ pour utf8
								$show_cumul_page .= "
								<font color='#990000'>".stripslashes($keyword)."</font> <font color='#000000'>".$keyword_position."</font><br>";
							}
						} 
					}
				}

			$show_cumul_page .= "
					</td>
					<td style=\"".$td_data_CSS." text-align: left; vertical-align: top; white-space: nowrap;\">
						Total: ".$tri_Tab_aff_ref[$i][0]."<br>";
				
				@mysql_data_seek($result, 0); // reset($result);
				while($row = mysql_fetch_array($result)){
					if($row['keyword'] <> 'TOTAL_VISITS') {
						$show_cumul_page .= "<font color='#990000'>".$row['nb_item']."</font><br>";
					}
				}

			$show_cumul_page .= "
					</td>
				</tr>";
		}

//$end = (float) array_sum(explode(' ',microtime()));  
//echo 'Keywords diff '.$total_differents_keyword.' : boucle entiere :'.sprintf("%.4f", $end-$start) . " secondes<br><br>";  


		$show_cumul_page .= "
		</table>";				
			if($switch_list_button && !$do_archives && !$public_StatsIn) {
				$show_cumul_page .= "
				<div align=\"center\">
					<form name=\"listekeywords\" method=\"post\" action=\"".FILENAME_INDEX_FRAME."\">
						<input type=\"hidden\" name=\"type\" value=\"\">
						<input type=\"hidden\" name=\"when\"  value=\"".$when_day."\">
						<input type=\"hidden\" name=\"limit_keywords\" value=\"".$limit_keywords."\">
						<input type=\"hidden\" name=\"limit_pages\" value=\"".$limit_pages."\">
						<input class=\"submit\" name=\"submit_limit_keywords\" type=\"submit\" value=\"".$value_button_keywords."\" alt=\"".$value_button_keywords."\" title=\"".$value_button_keywords."\">
					</form>
				</div>";
			}
				
		$show_cumul_page .= "
		</td></tr>
		</table></td></tr></table><br />" ;

		unset($tri_Tab_aff_ref);
?>
