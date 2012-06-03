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
// on doit envoyé $mois avant
//TODO no count bad user agent

	// ---------------- Ne doit pas être appelé directement -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/graph_month_days.php' ){ 
		header('Location: index.php'); //Si appelle direct de la page redirect
	}
	// ------------------------------------------------------------------------

	
	//for graph left here (for admin and stats in)
	$td_data_graph_CSS = 'border-width: 0px 0px 0px 0px; border-collapse: collapse; padding: 0px;';
	//$td_data_graph_CSS = 'border: 1px solid #000000; border-collapse: collapse; padding: 0px;'; //test

			//#########################################################################################
			//						 		Simple GRAPH month by day
			//#########################################################################################
		if ($graph_visitors_pages == 1) {

			if($time_test == true) {
				$start = (float) array_sum(explode(' ',microtime()));  
			}

			$graph_byday = '';
		
			//Hauteur graph
			$height_graph = 180.00; 
		
			//------------ Proportons graph -------------------------------------
			$total_nb_pages_visitees = "";
			$max_pages = "";
			$result = mysql_query("select date, visited_pages, sum(visited_pages) as total_visited_page from ".TABLE_DAYS_PAGES." where date like '%".$mois."' GROUP BY date");
			while($row = mysql_fetch_array($result)){ //while sur nb jour
				$result_unknown_agent = mysql_query("select sum(visits) as total_visited_page from ".TABLE_UNIQUE_BAD_AGENT." where date='".$row['date']."' and type='I'");
				$row_unknown_agent = mysql_fetch_array($result_unknown_agent);
				
				if($row['total_visited_page'] + $row_unknown_agent['total_visited_page'] > $max_pages){
					$max_pages = $row['total_visited_page'] + $row_unknown_agent['total_visited_page']; // Pour chaque jour On ajoute pages visitées des user agent inconnus
				}
				$total_nb_pages_visitees = $total_nb_pages_visitees + $row['total_visited_page'] + $row_unknown_agent['total_visited_page'];
			}
		
			$result = mysql_query("select * from ".TABLE_UNIQUE_VISITOR." where date like '%".$mois."'");
			$total_nb_visiteurs = mysql_num_rows($result);
			//On ajoute Visitors des user agent inconnus
			$result = mysql_query("select *  from ".TABLE_UNIQUE_BAD_AGENT." where date like '%".$mois."' and type='I'"); 
			$total_nb_visiteurs =  $total_nb_visiteurs + mysql_num_rows($result);
			//--------------------------------------------------------------------
		
			$graph_byday .= "
		<table style=\"".$table_border_CSS."\">
		  <tr>
			<td>
			  <table style=\"".$table_frame_CSS."\">
				<tr>
				<td style=\"width:5%; white-space:nowrap;\">"; //width: 1%; in px ne fonctionne pas
				 if ($StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
				 	$graph_byday .= "
				 		&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/icon_chart.gif\" height=\"32px\" alt=\"".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois.")\" title=\"".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois."\">";
				 }
				 
				 $graph_byday .= "
				 </td>
				 <th style=\"".$table_title_CSS."\">"
					.MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois."
				  </th>
				  </tr>
				<tr>
				  <td colspan=\"3\">
					<small>".MSG_TOTAL_VISITORS." = ".$total_nb_visiteurs."<br>
					".MSG_TOTAL_PAGES_VISITED." = ".$total_nb_pages_visitees."</small>
					<table style=\"".$table_data_CSS."\">
					  <tr>
						<td rowspan=\"2\" style=\"".$td_txt_CSS."\">
							<b><span style=\"".$page_view."\">".MSG_VISITED_PAGES."</span><br />
							& 
							<span style=\"".$style_visits."\">".MSG_VISITORS."</span></b>
						</td>";
		
						//Pour affichage echelle y
						if($max_pages != 0){
							$indice_echelle = 1; $hauteur = bcmul($indice_echelle, $height_graph, 2);
							if ($MaxHauteur_echelle <= $hauteur) { $MaxHauteur_echelle = $hauteur; $EchyMin = '0'; }
						} else { // pour ne pas afficher 0 si $max_pages = 0
							$EchyMin  = '';	
							$max_pages = '';
						}
		
		$graph_byday .= "
					<td style=\"".$td_data_graph_CSS." vertical-align: top; white-space: nowrap;\">".$max_pages."</td>
					<td rowspan=\"2\" style=\"".$td_data_graph_CSS." vertical-align: bottom;\">
						<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"".$MaxHauteur_echelle."\" width=\"1\" alt=\"\" title=\"\">
					</td>";
		
					unset($data_graph);
					$result = mysql_query("select date, sum(visitors) as total_visitors_day, sum(visited_pages) as total_visited_page_day from ".TABLE_DAYS_PAGES." where date like '%".$mois."' GROUP BY date");
					while($row = mysql_fetch_array($result)){ //while sur nb jour
		
						$date_comp = $row['date'];
						$n_day = substr($date_comp, 0, 2) + 0; // + 0 pour faire disparaitre les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)
		
						$result_uniq_visits = mysql_query("select date from ".TABLE_UNIQUE_VISITOR." where date='".$row['date']."'");
						$data_graph[$n_day][1] = $row['total_visited_page_day'];
						$data_graph[$n_day][0] = mysql_num_rows($result_uniq_visits);
		
						//On ajoute Visitors et pages visitées des user agent inconnus
						$result_bad_agent = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_BAD_AGENT." where date='".$row['date']."' and type='I'"); 
						$row_bad_agent = mysql_fetch_array($result_bad_agent);
						$data_graph[$n_day][0] =  $data_graph[$n_day][0] + $row_bad_agent['nb_visitors'];
						$data_graph[$n_day][1] = $data_graph[$n_day][1] + $row_bad_agent['total_pages_view'];
					}
		
					// for chaque jour du mois
					for($cpt_jour = 1 ; $cpt_jour <= $n_day; $cpt_jour++) { 
						$graph_byday .= "<td rowspan=\"2\" style=\"".$td_data_graph_CSS." vertical-align: bottom;\">";
						if($max_pages != 0) {
							$indice = bcdiv($data_graph[$cpt_jour][1], $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);
						}
						$graph_byday .= "
						<img src=\"".$path_allmystats_abs."images/histo-v.gif\" height=\"".$hauteur."\" width=\"7\" alt=\"".$data_graph[$cpt_jour][1]."\" title=\"".$data_graph[$cpt_jour][1]."\">";
		
						if($max_pages!=0){
							$indice = bcdiv($data_graph[$cpt_jour][0], $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);  
						}
						$graph_byday .=  "
						<img src=\"".$path_allmystats_abs."images/histo-vv.gif\" height=\"".$hauteur."\" width=\"7\" alt=\"".$data_graph[$cpt_jour][0]."\" title=\"".$data_graph[$cpt_jour][0]."\"></td>";
					}
		
		$graph_byday .= "
				  </tr>
				  <tr>
					 <td style=\"text-align: right; vertical-align: bottom;\">".$EchyMin."</td>
				  </tr>
				  
					  <tr>
						<td><b>". MSG_GRAPH_DAY."</b></td>
						<td style=\"text-align: center;\">&nbsp;</td><td style=\"text-align: center;\">&nbsp;</td>"; // Pour echelle x
		
				//----------------- calcul jour du mois et week end pour echelle x ------------------------
				$jm = explode("/",$mois);
				$nbjourdumois = maxDaysInMonth($jm[0], $jm[1]);
				
				// Premier samedi du mois (6eme jour de la semaine) fonction get_first_day
				$premiersamedi =  strftime("%d", get_first_day(6, $jm[0], $jm[1])); 
				$weekend = "/";
				if ($premiersamedi == 7) { $weekend .= sprintf("%02d",1).'|'; } //Le 1er jour du mois est un dimanche
				for($i = $premiersamedi; $i <= $nbjourdumois; $i=$i+7){
					$week = $i;
					$week = $week+0;
					$weekend .= sprintf("%02d",$week).'|'; 
					$weekend .= sprintf("%02d",$week+1) .'|';
				}
				$weekend = substr($weekend, 0, strlen($weekend)-1); //supp last |
				$weekend .= "/";
				//------------------ Affichage echelle x -----------------
				for($i = 1; $i <= $nbjourdumois; $i++){
					$num = $i;
					if(!preg_match($weekend, sprintf("%02d", $num))) {	//$num+0 pour supprimer les 0 devant 01, 02 ,03 etc
						$graph_byday .=  "<td style=\"text-align: center;\">". sprintf("%02d", $num)."</td>";
					} else {
						$graph_byday .=  "<td style=\"text-align: center;\"><b><font color=#990000>". sprintf("%02d", $num)."</font></b></td>";
					}
				}
				//---------------------------------------------------------------------------------------
		
		$graph_byday .= "
		</tr></table></td></tr>
				<tr>
				  </tr></table></td></tr></table><br>";
		 
			if($time_test == true) {
				$end = (float) array_sum(explode(' ',microtime()));  
				echo '<pre>										Simple Graph month days Traitement : '.sprintf("%.4f", $end-$start) . ' sec</pre>';  
			}

		} else {
				###############################################################################################################
				//#############################################################################################################
				//									DOUBLE GRAPH /HEURE VISITEURS DOUBLE
				//#############################################################################################################	
				if($time_test == true) {
					$start = (float) array_sum(explode(' ',microtime()));  
				}
					
				//----------------- calcul jour du mois et week end pour echelle x ------------------------
				$jm = explode("/",$mois);
				$nbjourdumois = maxDaysInMonth($jm[0], $jm[1]);
				
				// Premier samedi du mois (6eme jour de la semaine) fonction get_first_day
				$premiersamedi =  strftime("%d", get_first_day(6, $jm[0], $jm[1])); 
				$weekend = "/";
				if ($premiersamedi == 7) { $weekend .= sprintf("%02d",1).'|'; } //Le 1er jour du mois est un dimanche
				for($i = $premiersamedi; $i <= $nbjourdumois; $i=$i+7){
					$week = $i;
					$week = $week+0;
					$weekend .= sprintf("%02d",$week).'|'; 
					$weekend .= sprintf("%02d",$week+1) .'|';
				}
				$weekend = substr($weekend, 0, strlen($weekend)-1); //supp last |
				$weekend .= "/";
				//----------------------------------------------------------------------------------------
				//--------------------------------- Proportons graph -------------------------------------
				//Hauteur graph
				$height_graph = 100.00; //150
			
				//Font size ech X
				$size_x = '10';
				//Largeur des barres du graph
				$width_bar_graph = $size_x + 5; 
				//Espace entre les barres
				$bar_space = '1';
				$td_width_x = $width_bar_graph + $bar_space;
		
				$graph_byday = '';
				//-----------------------------------------------------------
		
				//Max visitors and Max pages
				//unset($data_graph);
				$result = mysql_query("select date, sum(visitors) as total_visitors_day, sum(visited_pages) as total_visited_page_day from ".TABLE_DAYS_PAGES." where date like '%".$mois."' GROUP BY date");
				while($row = mysql_fetch_array($result)){ //while sur nb jour
		
					$date_comp = $row['date'];
					$n_day = substr($date_comp, 0, 2) + 0; // + 0 pour faire disparaitre les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)
		
					$result_uniq_visits = mysql_query("select date from ".TABLE_UNIQUE_VISITOR." where date='".$row['date']."'");
					$data_graph[$n_day][1] = $row['total_visited_page_day'];
					$data_graph[$n_day][0] = mysql_num_rows($result_uniq_visits);
		
					//On ajoute Visitors et pages visitées des user agent inconnus
					$result_bad_agent = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_BAD_AGENT." where date='".$row['date']."' and type='I'"); 
					$row_bad_agent = mysql_fetch_array($result_bad_agent);
					$data_graph[$n_day][0] =  $data_graph[$n_day][0] + $row_bad_agent['nb_visitors'];
					$data_graph[$n_day][1] = $data_graph[$n_day][1] + $row_bad_agent['total_pages_view'];
				}
		
				// for chaque jour du mois
				for($cpt_jour = 1 ; $cpt_jour <= $n_day; $cpt_jour++) { 
		
					if($data_graph[$cpt_jour][1] + $row_unknown_agent['total_visited_page'] > $max_pages){
						//TODO $row_unknown_agent['total_visited_page'] for double graph
						//$max_pages = $data_graph[$cpt_jour][1] + $row_unknown_agent['total_visited_page']; // Pour chaque jour On ajoute pages visitées des user agent inconnus
						$max_pages = $data_graph[$cpt_jour][1]; // Pour chaque jour On ajoute pages visitées des user agent inconnus
					}
		
					if($data_graph[$cpt_jour][0] > $max_visitors){
						$max_visitors = $data_graph[$cpt_jour][0]; 
					}
					
					$total_nb_visiteurs = $total_nb_visiteurs + $data_graph[$cpt_jour][0];
					$total_nb_pages_visitees = $total_nb_pages_visitees + $data_graph[$cpt_jour][1];
				}
		
				//for display ech y visitors
				$EchyMin_visitors = '0';
				if($max_visitors != 0){
					for($cpt_jour = 1 ; $cpt_jour <= $n_day; $cpt_jour++) { 
						$indice = bcdiv($data_graph[$cpt_jour][0], $max_visitors, 2); $hauteur = bcmul($indice, $height_graph, 2);  
						if ($echy_visitors_MaxHauteur <= $hauteur) { $echy_visitors_MaxHauteur = $hauteur; }
					}
				} else { // not display 0 if $max_visitors = 0
					$EchyMin_visitors  = '';	
					$max_visitors = '';
				}
		
				//for display ech y pages
				$EchyMin_pages = '0';
				if($max_pages != 0){
					for($cpt_jour = 1 ; $cpt_jour <= $n_day; $cpt_jour++) { 
						$indice = bcdiv($data_graph[$cpt_jour][1], $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);  
						if ($echy_pages_MaxHauteur <= $hauteur) { $echy_pages_MaxHauteur = $hauteur; }
					}
				} else { // not display 0 if 0 si $max_pages = 0
					$EchyMin_page  = '';	
					$max_pages = '';
				}
		
			//---------------------------------------------------------------------------------------
			$Nb_col_span = $nbjourdumois+3+1;
			$spaceEnd = 3;
			
			$graph_byday .= "
				<table style=\"".$table_border_CSS."\">
				  <tr>
					<td>
					  <table style=\"".$table_frame_CSS."\">
						<tr>
						  <td style=\"width:5%; white-space:nowrap;\">"; //width: 8%; in px ne fonctionne pas
							 if ($StatsIn_in_prot_dir <> 'Y') { // if use stats_in and is in protected directory --> the images can be displayed
								$graph_byday .= "
									&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/icon_chart.gif\" height=\"32px\" alt=\"".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois.")\" title=\"".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois."\">";
							 }
				 
						  $graph_byday .= "
						  </td>
				 		<td style=\"".$table_title_CSS."\">".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois ."</td>
						</tr>
						<tr>
						  <td colspan=\"2\" style=\"".$td_txt_CSS."\">
							<small>".MSG_TOTAL_VISITORS." = ".$total_nb_visiteurs."<br>".MSG_TOTAL_PAGES_VISITED." = ".$total_nb_pages_visitees."</small>
							<table style=\"".$table_data_CSS."\">
								<tr>
									<!-- vertical space -->
									<td style=\"height:5px;\" colspan=\"".$Nb_col_span."\"></td>
								</tr>
								<tr>
									<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center;\"><b><span style=\"".$style_visits."\">&nbsp;".MSG_VISITORS."</span></b></td>
									<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; white-space: nowrap;\">".$max_visitors."</td>";

									//Ech Y
									$graph_byday .= "
									<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">
										<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"".$echy_visitors_MaxHauteur."\" width=\"1\" align=\"top\" alt=\"\" title=\"\">
									</td>";
			
						for($cpt_jour = 1 ; $cpt_jour <= $nbjourdumois; $cpt_jour++) {  //Axe x
							$graph_byday .= "
							<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">";
							if($max_visitors != 0) {
								$indice = bcdiv($data_graph[$cpt_jour][0], $max_visitors, 2); $hauteur = bcmul($indice, $height_graph, 2);
							}
							$graph_byday .= "
								<img src=\"".$path_allmystats_abs."images/histo-vv.gif\" height=\"".$hauteur."\" width=\"".$width_bar_graph."\" alt=\"".$data_graph[$cpt_jour][0]."\" title=\"".$data_graph[$cpt_jour][0]."\">
							</td>";
						}
								

					$graph_byday .= "
					  </tr>
					  <tr>
						 <td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: bottom;\">".$EchyMin_visitors."</td>
					  </tr>
					  <tr>
						<td colspan=\"2\" style=\"".$td_data_graph_CSS." text-align: right;\">".MSG_GRAPH_DAY."</td>
						<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; height:2px;\">
							<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"1px\" align=\"top\" alt=\"\" title=\"\">
						</td>";
						//--------------- Display echelle x visitors -------------
						for($cpt_jour = 1 ; $cpt_jour <= $nbjourdumois; $cpt_jour++) {  //Axe x
							$graph_byday .= "
							<td style=\"".$td_data_graph_CSS." text-align: center; vertical-align: top; width:".$td_width_x."px; height:2px;\">
								<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"1px\" width=\"".$td_width_x."\" alt=\"\" title=\"\"><br>";
								if(!preg_match($weekend, sprintf("%02d", $cpt_jour))) {	//$num+0 pour supprimer les 0 devant 01, 02 ,03 etc
									$graph_byday .= "
									<span style=\"font-size:".$size_x."px\">". sprintf("%02d", $cpt_jour)."</span>";
								} else {
									$graph_byday .= "
									<b><span style=\"color:#990000; font-size:".$size_x."px\">". sprintf("%02d", $cpt_jour)."</span></b>";
								}
		    				$graph_byday .= 
							'</td>';
						}
						$graph_byday .= "<td style=\"width:".$spaceEnd."px;\"></td>"; // space end
						
					$graph_byday .= "</tr>";
						//-------------------------------------------------------
			
					$graph_byday .= "
						<tr>
							<td style=\"height:5px; ".$td_data_graph_CSS."\" colspan=\"".$Nb_col_span."\">
						</td>
					</tr></tr>"; //espace entre les 2 graphs
			
					//######################### GRAPH VISITED PAGES ##############################################
			
						$graph_byday .= "
						<tr>
							<td rowspan=\"3\" style=\"".$td_data_graph_CSS." text-align: center;\">
								<b><span style=\"".$page_view."\">&nbsp;".MSG_VISITED_PAGES."</span></b>
							</td>
						</tr>
						<tr>
							<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; white-space: nowrap;\">".$max_pages."</td>";
							
							//Ech y
							$graph_byday .= "
							<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">
								<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"".$echy_pages_MaxHauteur."\" width=\"1\" align=\"top\" alt=\"\" title=\"\">
							</td>";

					for($cpt_jour = 1 ; $cpt_jour <= $nbjourdumois; $cpt_jour++) { 
						$graph_byday .= "
						<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">";
						if($max_visitors != 0) {
							$indice = bcdiv($data_graph[$cpt_jour][1], $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);
						}
			
						$graph_byday .= "
							<img src=\"".$path_allmystats_abs."images/histo-v.gif\" height=\"".$hauteur."\" width=\"".$width_bar_graph."\" alt=\"".$data_graph[$cpt_jour][1]."\" title=\"".$data_graph[$cpt_jour][1]."\">
						</td>";
					}
					
				  //----------------------- axe x -----------------------------------------

					$graph_byday .= "
					  </tr>
					  <tr>
						 <td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: bottom;\">".$EchyMin_pages."</td>
					  </tr>
					  <tr>
						 <td colspan=\"2\" style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top;\">".MSG_GRAPH_DAY."</td>
						 <td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; height:2px;\">
							 <img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"1px\" align=\"top\" alt=\"\" title=\"\">
						 </td>";
			
						for($cpt_jour = 1 ; $cpt_jour <= $nbjourdumois; $cpt_jour++) {  //Axe x
							$graph_byday .= "
							<td style=\"".$td_data_graph_CSS." text-align: center; vertical-align: top; width:".$td_width_x."px; height:2px;\">
								<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"1px\" width=\"".$td_width_x."\" alt=\"\" title=\"\"><br>";
								if(!preg_match($weekend, sprintf("%02d", $cpt_jour))) {	//$num+0 pour supprimer les 0 devant 01, 02 ,03 etc
									$graph_byday .= "
									<span style=\"font-size:".$size_x."px\">". sprintf("%02d", $cpt_jour)."</span>
									<span style=\"font-size:3px\"><br></span>";
								} else {
									$graph_byday .= "
									<b><span style=\"color:#990000; font-size:".$size_x."px\">". sprintf("%02d", $cpt_jour)."</span></b>
									<div style=\"font-size:3px\"><br></div>";
								}
							$graph_byday .= 
							'</td>';
						}			
						$graph_byday .= "<td style=\"width:".$spaceEnd."px;\"></td>"; // space end
				//------------------------- end axe x ----------------------------------------	

				$graph_byday .= "
				</tr>
				</tr></table>
				</td></tr></table>
				</td></tr>
				</table><br />";
							
				if($time_test == true) {
					$end = (float) array_sum(explode(' ',microtime()));  
					echo "<pre>										Double Graph month days Traitement : ".sprintf("%.4f", $end-$start) . ' sec</pre>';  
				}

		}
		##########################################################################################
?>
