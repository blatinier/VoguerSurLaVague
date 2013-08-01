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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/details_bots.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

	//for graph (left here)
	$td_data_graph_CSS = 'border-width: 0px 0px 0px 0px; border-collapse: collapse; padding: 0px;';

//------------------------------------------------------------------------------

		if(!function_exists('geoip_open')) { //To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in page)
			require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
		}
		if(!function_exists('geoip_country_name_by_code')) { //Normally not required here but ... - if previously declared in page but not geoip_add.inc (add for AllMySats) 
			require_once(dirname(__FILE__).'/lib/geoip/geoip_add.inc');
		}

if($when==""){
	$when = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
}
	
		echo '		
		<table style="'.$table_border_CSS.'">
		  <tr>
			<td>
			  <table style="'.$table_frame_CSS.'">
				<tr>
				 <th style="'.$table_title_CSS.'">
					<form name="form1" method="post" action="'. $_SERVER['PHP_SELF'].'">
						<input type="hidden" name="type"  value="DetailsRobot">
						<input type="hidden" name="when"  value="'.$when.'">
						<input class="submit" name="refresh" type="submit" value="'.MSG_REFRESH.'" alt="'.MSG_REFRESH.'" title="'.MSG_REFRESH.'">
					</form>
					&nbsp;&nbsp;'.MSG_STATISTICS_OF.$when.'
				 </th>
				</tr>
		</table>
		</td></tr></table></td></tr></table><br />';

		//#########################################################################################
		//------------------------- Include tableau details robots ------------------------------
		$when_date = $when; // date d/m/Y or m/Y
		$display_bots = true;
		include(FILENAME_DISPLAY_BOTS);
		echo $show_page_os_nav_robots;
		//########################################################################################
								//ROBOTS Visites par plage horaire
		//-----------------------------------------------------------------------------------------

		for($i=1;$i<=24;$i++){
			$hour="heure$i";
			$var="v_heure$i";
			$$var=0;
			$$hour=0;
		}
 
	//##############################################################################
	//					GRAPH /HEURE BOTS DOUBLE
	//##############################################################################

		$result = mysql_query("select v.code, v.agent, p.visits, p.hour, p.page from ".TABLE_UNIQUE_BOT." v,".TABLE_PAGE_BOT." p where v.date like '".$when."' and v.code=p.code order by p.hour ASC");

	// Pour comptabilise les visiteurs simultanés par heure ##
	$code_unique_hre1 = array();
	$code_unique_hre2 = array();
	$code_unique_hre3 = array();
	$code_unique_hre4 = array();
	$code_unique_hre5 = array();
	$code_unique_hre6 = array();
	$code_unique_hre7 = array();
	$code_unique_hre8 = array();
	$code_unique_hre9 = array();
	$code_unique_hre10 = array();
	$code_unique_hre11 = array();
	$code_unique_hre12 = array();
	$code_unique_hre13 = array();
	$code_unique_hre14 = array();
	$code_unique_hre15 = array();
	$code_unique_hre16 = array();
	$code_unique_hre17 = array();
	$code_unique_hre18 = array();
	$code_unique_hre19 = array();
	$code_unique_hre20 = array();
	$code_unique_hre21 = array();
	$code_unique_hre22 = array();
	$code_unique_hre23 = array();
	$code_unique_hre24 = array();

	$hauteur = '';
	$MaxHauteur = '';
	$EchyMin_visitors = '';
	$echy_pages_MaxHauteur = '';
	//#############################################################

		$code_unique = array();
		while($row = mysql_fetch_array($result)){

			//---------------------------------------
			//Visistes et pages vues par les robots, hors bad user agent
			//Note: les barres visiteurs sont incrémenté de 1 pour l'heure de la 1ere visite
			//		les barres "pages visitées" sont incrémentées par heure 
			//		(si une même page est visitée par un même visiteur la barre est incrémentée sur l'heure de la 1ere visite) pour eviter de remplir la table si spam

				################################ Pour comptabilise les visiteurs simultanés par heure ################################################
				$heure = $row['hour'];
				
				if(($heure>=0)&($heure<1)){	$heure1=$heure1+$row['visits']; if (!in_array($row['code'], $code_unique_hre1)) {$v_heure1++; $code_unique_hre1[] = $row['code']; }}
				if(($heure>=1)&($heure<2)){	$heure2=$heure2+$row['visits']; if (!in_array($row['code'], $code_unique_hre2)) { $v_heure2++; $code_unique_hre2[] = $row['code']; }}
				if(($heure>=2)&($heure<3)){	$heure3=$heure3+$row['visits']; if (!in_array($row['code'], $code_unique_hre3)) { $v_heure3++; $code_unique_hre3[] = $row['code']; }}
				if(($heure>=3)&($heure<4)){	$heure4=$heure4+$row['visits']; if (!in_array($row['code'], $code_unique_hre4)) { $v_heure4++; $code_unique_hre4[] = $row['code']; }}
				if(($heure>=4)&($heure<5)){	$heure5=$heure5+$row['visits']; if (!in_array($row['code'], $code_unique_hre5)) { $v_heure5++; $code_unique_hre5[] = $row['code']; }}
				if(($heure>=5)&($heure<6)){	$heure6=$heure6+$row['visits']; if (!in_array($row['code'], $code_unique_hre6)) { $v_heure6++; $code_unique_hre6[] = $row['code']; }}
				if(($heure>=6)&($heure<7)){	$heure7=$heure7+$row['visits']; if (!in_array($row['code'], $code_unique_hre7)) { $v_heure7++; $code_unique_hre7[] = $row['code']; }}
				if(($heure>=7)&($heure<8)){	$heure8=$heure8+$row['visits']; if (!in_array($row['code'], $code_unique_hre8)) { $v_heure8++; $code_unique_hre8[] = $row['code']; }}
				if(($heure>=8)&($heure<9)){	$heure9=$heure9+$row['visits']; if (!in_array($row['code'], $code_unique_hre9)) { $v_heure9++; $code_unique_hre9[] = $row['code']; }}
				if(($heure>=9)&($heure<10)){ $heure10=$heure10+$row['visits']; if (!in_array($row['code'], $code_unique_hre10)) { $v_heure10++; $code_unique_hre10[] = $row['code']; }}
				if(($heure>=10)&($heure<11)){ $heure11=$heure11+$row['visits']; if (!in_array($row['code'], $code_unique_hre11)) { $v_heure11++; $code_unique_hre11[] = $row['code']; }}
				if(($heure>=11)&($heure<12)){ $heure12=$heure12+$row['visits']; if (!in_array($row['code'], $code_unique_hre12)) { $v_heure12++; $code_unique_hre12[] = $row['code']; }}
				if(($heure>=12)&($heure<13)){ $heure13=$heure13+$row['visits']; if (!in_array($row['code'], $code_unique_hre13)) { $v_heure13++; $code_unique_hre13[] = $row['code']; }}
				if(($heure>=13)&($heure<14)){ $heure14=$heure14+$row['visits']; if (!in_array($row['code'], $code_unique_hre14)) { $v_heure14++; $code_unique_hre14[] = $row['code']; }}
				if(($heure>=14)&($heure<15)){ $heure15=$heure15+$row['visits']; if (!in_array($row['code'], $code_unique_hre15)) { $v_heure15++; $code_unique_hre15[] = $row['code']; }}
				if(($heure>=15)&($heure<16)){ $heure16=$heure16+$row['visits']; if (!in_array($row['code'], $code_unique_hre16)) { $v_heure16++; $code_unique_hre16[] = $row['code']; }}
				if(($heure>=16)&($heure<17)){ $heure17=$heure17+$row['visits']; if (!in_array($row['code'], $code_unique_hre17)) { $v_heure17++; $code_unique_hre17[] = $row['code']; }}
				if(($heure>=17)&($heure<18)){ $heure18=$heure18+$row['visits']; if (!in_array($row['code'], $code_unique_hre18)) { $v_heure18++; $code_unique_hre18[] = $row['code']; }}
				if(($heure>=18)&($heure<19)){ $heure19=$heure19+$row['visits']; if (!in_array($row['code'], $code_unique_hre19)) { $v_heure19++; $code_unique_hre19[] = $row['code']; }}
				if(($heure>=19)&($heure<20)){ $heure20=$heure20+$row['visits']; if (!in_array($row['code'], $code_unique_hre20)) { $v_heure20++; $code_unique_hre20[] = $row['code']; }}
				if(($heure>=20)&($heure<21)){ $heure21=$heure21+$row['visits']; if (!in_array($row['code'], $code_unique_hre21)) { $v_heure21++; $code_unique_hre21[] = $row['code']; }}
				if(($heure>=21)&($heure<22)){ $heure22=$heure22+$row['visits']; if (!in_array($row['code'], $code_unique_hre22)) { $v_heure22++; $code_unique_hre22[] = $row['code']; }}
				if(($heure>=22)&($heure<23)){ $heure23=$heure23+$row['visits']; if (!in_array($row['code'], $code_unique_hre23)) { $v_heure23++; $code_unique_hre23[] = $row['code']; }}
				if(($heure>=23)&($heure<24)){ $heure24=$heure24+$row['visits']; if (!in_array($row['code'], $code_unique_hre24)) { $v_heure24++; $code_unique_hre24[] = $row['code']; }}

				###########################################################################################################################################
		}// End while

		unset($code_unique);
		$max_visitors = 0;
		$i = 1;
		while($i <= 24){
			$heure = "v_heure$i";
			if($$heure > $max_visitors){
				$max_visitors = $$heure;
			}
			$i++;
		}

		$max_pages = 0;
		$i = 1;
		while($i <= 24){
			$heure = "heure$i";
			if($$heure > $max_pages){
				$max_pages = $$heure;
			}
			$i++;
		}

			//Hauteur graph
			$height_graph = 100.00; //150

			//Font size ech X
			$size_x = '10';
			//Largeur des barres du graph
			$width_bar_graph = $size_x + 6; //'16';
			//Espace entre les barres
			$bar_space = '1';
			$td_width_x = $width_bar_graph + $bar_space;

			//Pour affichage echelle
			$EchyMin = '0';
			if($max_visitors != 0){
			  for($i=1; $i <= 24; $i++){
				$hour = "v_heure$i";
				$indice = bcdiv($$hour, $max_visitors, 2); $hauteur = bcmul($indice, $height_graph, 2);
				if ($MaxHauteur <= $hauteur) { $MaxHauteur = $hauteur; }
			  }
			} else { // pour ne pas afficher 0 si $max_visitors = 0
				$EchyMin  = '';	
		  		$max_visitors = '';
			}

			$Nb_col_span = 27;
			$spaceEnd = 3;

			echo "<br>
				<table style=\"".$table_border_CSS."\">
				  <tr>
					<td>
					  <table style=\"".$table_frame_CSS."\">
						<tr>
						<td style=\"width:5%; white-space:nowrap;\">
							&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/icon_chart.gif\" height=\"32px\" alt=\"".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois.")\" title=\"".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois."\">
						</td>
				 		<td style=\"".$table_title_CSS."\">".MSG_BOTS_GRAPH_HOUR."</td>
						</tr>
						<tr>
						  <td colspan=\"2\">
							<table style=\"".$table_data_CSS."\">
								<tr>
									<!-- vertical space -->
									<td height=\"5\" colspan=\"27\"></td>
								</tr>
								<tr>
									<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center;\"><b><span style=\"".$style_visits."\">&nbsp;".MSG_BOT_VISITS."</span></b></td>
									<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; white-space: nowrap;\">".$max_visitors."</td>";

									//Ech Y
									echo "
									<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">
										<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"".$MaxHauteur."\" width=\"1\" align=\"top\" alt=\"\" title=\"\">
									</td>";


		for($i=1; $i < 25; $i++){ //Bar graph warning nb td variable
          	$hour = "heure$i";
          	$v_hour="v_heure$i";
				echo "<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">";
				if($max_visitors != 0) {
					$indice = bcdiv($$v_hour, $max_visitors, 2); $hauteur = bcmul($indice, $height_graph, 2);
				}
				echo "<img src=\"images/histo-vv.gif\" height=\"".$hauteur."\" width=\"".$width_bar_graph."\" alt=\"".$$v_hour."\" title=\"".$$v_hour."\">
					 </td>";
		}

			echo "
				</tr>
				<tr>
					 <td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: bottom;\">".$EchyMin_visitors."</td>
				</tr>
				<tr>
				<td colspan=\"2\" style=\"".$td_data_graph_CSS." text-align: right;\">".MSG_GRAPH_HOURS."</td>
				<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; height:2px;\">
					<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"1px\" align=\"top\" alt=\"\" title=\"\">
				</td>";

        for($i=0; $i < 25; $i++){ // axe x
			if ($i == 24) {
				echo "<td style=\"width:".$spaceEnd."px;\">&nbsp;</td>"; // space end
			} else {
				echo "
				<td style=\"".$td_data_graph_CSS." text-align: center; vertical-align: top; width:".$td_width_x."px; height:2px;\">
					<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"1px\" align=\"top\" width=\"".$td_width_x."\" alt=\"\" title=\"\"><br>".$i."
				</td>";
			}
        }

			echo '
			</tr>';

		echo "
			<tr>
				<td colspan=\"".$Nb_col_span."\" style=\"".$td_data_graph_CSS." height: 5px;\">
			</td>
		</tr></tr>"; //espace entre les 2 graphs


		//######################### Graph  page visitées) ##############################################

			//Hauteur graph
			$height_graph = 100.00; //150
			
			//Pour affichage echelle
			$EchyMin_pages = '0';
			if($max_pages != 0){
			  for($i=1; $i <= 24; $i++){
				$hour = "heure$i";
				$indice = bcdiv($$hour,$max_pages,2); $hauteur = bcmul($indice, $height_graph, 2);
				if ($echy_pages_MaxHauteur <= $hauteur) { $echy_pages_MaxHauteur = $hauteur; }
			  }
			} else { // pour ne pas afficher 0 si $max_pages = 0
				$EchyMin_pages  = '';	
		  		$max_pages = '';
			}

		echo "
				<tr>
					<td rowspan=\"3\" style=\"".$td_data_graph_CSS." text-align: center;\">
						<b><span style=\"".$page_view."\">&nbsp;".MSG_VISITED_PAGES."</span></b>
					</td>
				</tr>
				<tr>
		  			<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; white-space: nowrap;\">
						".$max_pages."
					</td>";
					
					//Ech Y
					echo "
					<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">
						<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"".$echy_pages_MaxHauteur."\" width=\"1\" align=\"top\" alt=\"\" title=\"\">
					</td>";

          for($i=1; $i < 25; $i++){ //Bar graph warning nb td variable
          	$hour = "heure$i";
          	$v_hour="v_heure$i";
				echo "
				<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">";
				if($max_pages!=0) {
					$indice = bcdiv($$hour, $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);
				}
				//align=\"absbottom\" 
				echo "
				<img src=\"images/histo-v.gif\" height=\"".$hauteur."\" width=\"".$width_bar_graph."\" align=\"bottom\" alt=\"".$$hour."\" title=\"".$$hour."\">
				</td>";
          }
	
		  //----------------------- axe x -----------------------------------------
		   echo "
				</tr>
				<tr>
					 <td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: bottom;\">".$EchyMin_pages."</td>
				</tr>
				<tr>
				<td colspan=\"2\" style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top;\">".MSG_GRAPH_HOURS."</td>
				<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; height:2px;\">
					<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"1px\" align=\"top\" alt=\"\" title=\"\">
				</td>";

        for($i=0; $i < 25; $i++){ 
			if ($i == 24) {
				echo "<td style=\"width:".$spaceEnd."px;\">&nbsp;</td>"; // space end
			} else {
				echo "
				<td style=\"".$td_data_graph_CSS." text-align: center; vertical-align: top;  width:".$td_width_x."px; height:2px;\">
					<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"1px\" align=\"top\" width=\"".$td_width_x."\" alt=\"\" title=\"\"><br>".$i."
					<div style=\"font-size:3px\"><br></div>
				</td>";
			}
		}
		//------------------------- end axe x ----------------------------------------	

		echo "
				</tr>
				</tr></table>
				</td></tr></table>
				</td></tr>
				</table><br />";

	
	//##############################################################################################
	//				 //------------ Affichage page visités par les robots ----------
	//##############################################################################################
	$result = mysql_query("select sum(p.visits) as total_pages, count(v.code) as total_visits, p.page from ".TABLE_UNIQUE_BOT." v, ".TABLE_PAGE_BOT." p where v.date like '".$when."' and v.code=p.code GROUP BY p.page order by p.page");
	$differents_pages = mysql_num_rows($result);  // OK Pages différentes

	unset($page_vue_robots);
	while($row = mysql_fetch_array($result)){
		$page_vue_robots[] = array($row['page'], $row['total_visits'], $row['total_pages']);
		$total_pages = $total_pages + $row['total_pages'];
	}

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
				  	'.MSG_VISITED_PAGES_BY_BOTS.' - '.$when.' <small><div style="font-weight:lighter">'.MSG_TOTAL_DIFFERENT_PAGES.": ".$differents_pages.'</small>
				  </div></td>
			</tr>
			<tr>
				  <td colspan="3">
					<table style="'.$table_data_CSS.'">
					  <tr>
						<th style="'.$td_data_CSS.'">'.MSG_PAGE.'</th>
						<th style="'.$td_data_CSS.'">'.MSG_VISITORS.'</th>
						<th style="'.$td_data_CSS.'">'.MSG_VISITED_PAGES.'</th>
						<th style="'.$td_data_CSS.'">'.MSG_PAGES_PERCENTAGE.'</th></tr>';

	//----------------------------------------

	@usort($page_vue_robots, "CompareValeurs");
	$cpt = 0;

	while ($page_vue_robots[$cpt][0] <> ""){

		$nb_robot = $page_vue_robots[$cpt][2]*100; 
		$pourcent_robot = @bcdiv($nb_robot, $total_pages, 2);

		echo '
		<tr>
		<td style="'.$td_data_CSS.'">'.$page_vue_robots[$cpt][0].'</td>
		<td style="'.$td_data_CSS.' text-align: center;">'.$page_vue_robots[$cpt][1].'</td>
		<td style="'.$td_data_CSS.' text-align: center;">'.$page_vue_robots[$cpt][2].'</td>
		<td style="'.$td_data_CSS.' text-align: center;">'.$pourcent_robot.'%</td>
		</tr>';
		$cpt++;
	}

	echo '</table></td></tr></table></td></tr></table><br>';

	//##############################################################################
	//				ORIGINE GEO DAY
	//#############################################################################

$Country_visitors_pie = array();
$Country_name_pie = array();

	$result = mysql_query("select count(country) as visitors_by_country, sum(visits) as pages_by_country, country from ".TABLE_UNIQUE_BOT." where date='".$when."' GROUP BY country ORDER BY visitors_by_country DESC");
	$total_differents_countries = mysql_num_rows($result);
		
	$result_max_pages = mysql_query("select sum(visits) as pages_by_country from ".TABLE_UNIQUE_BOT." where date='".$when."' GROUP BY country ORDER BY pages_by_country DESC");
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
			  <td style="'.$table_title_CSS.'">'.MSG_TITLE_ORG_GEO.'<br><small><font style="font-weight:lighter">'.MSG_TOTAL_DIFFERENT_COUNTRIES.': '.$total_differents_countries.'</font><small></td>
			</tr>
			<tr>
			  <td colspan="2">
				<table style="'.$table_data_CSS.'">
			<tr>
               <th style="'.$td_data_CSS.'">'.MSG_COUNTRY.'</th>
               <th style="'.$td_data_CSS.'">'.MSG_NB_VISITORS.'</th>
               <th style="'.$td_data_CSS.'">'.MSG_VISITED_PAGES.'</th>
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
				$hauteur = @bcmul($row['visitors_by_country'] , $indice, 2);  
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
			//---------------------------------------------------------------  
		 
			echo '</table></td></tr></table></td></tr></table><br />';
?>
