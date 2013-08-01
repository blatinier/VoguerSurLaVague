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
Note : $data_year_graph est donné par monthly_archives_list.php

$graph_visitors_pages == 1 IS OBSOLETE --> TODO delete code
*/
	
	// ---------------- Should not be called directly -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/graph_year_months.php' ){ 
		header('Location: index.php'); //Si appelle direct de la page redirect
	}
	// ------------------------------------------------------------------------

	//for graph left here (for admin and stats in)
	$td_data_graph_CSS = 'border-width: 0px 0px 0px 0px; border-collapse: collapse; padding: 0px;';

$graph_visitors_pages = 2; // $graph_visitors_pages = 1 is OBSOLETE

if ($graph_visitors_pages == 1) { // IS OBSOLETE ($graph_visitors_pages = 1)

$graph_bymonth = "";

	//Hauteur graph
	$height_graph = 180.00; 

	$total_unique_visitors = "";
	$total_pages = "";
	$max_pages = "";

		//Mois ou Max pages année 
		for($i = 0 ; $i < count($data_year_graph); $i++) {
        	if($data_year_graph[$i][2] > $max_pages){
				$max_pages = $data_year_graph[$i][2]; // Pour chaque mois
			}
			$total_unique_visitors = $total_unique_visitors + $data_year_graph[$i][1];
			$total_pages = $total_pages + $data_year_graph[$i][2];
        } 

	$graph_bymonth .= "
		<table style=\"".$table_border_CSS."\">
		  <tr>
			<td>
			  <table style=\"".$table_frame_CSS."\">
			<tr>
          	  <td style=\"width:5%; white-space:nowrap;\">
			  		&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/icon_chart.gif\" height=\"32px\" alt=\"".MSG_GRAPH_MONTH_VISITORS_PAGES." ".$year_todo." (".MSG_EXCLUDED_BOTS.")\" title=\"".MSG_GRAPH_MONTH_VISITORS_PAGES." ".$year_todo." (".MSG_EXCLUDED_BOTS.")\">
			  </td>
			  <th style=\"".$table_title_CSS."\">"
				.MSG_GRAPH_MONTH_VISITORS_PAGES." ".$year_todo." (".MSG_EXCLUDED_BOTS.")
			  </th>
			  </tr>
			<tr>
			  <td colspan=\"2\">
				<small>".MSG_TOTAL_VISITORS." = ".$total_unique_visitors."<br>
				".MSG_TOTAL_PAGES_VISITED." = ".$total_pages."</small>
				<table style=\"".$table_data_CSS."\">
				  <tr>
					<td rowspan=\"2\" style=\"".$td_txt_CSS."\">
						<b><span class=\"PagesVisited\">".MSG_VISITED_PAGES."</span><br />
						& 
						<span class=\"Visits\">".MSG_VISITORS."</span></b>
					</td>";
	
					//Pour affichage echelle y
					if($max_pages != 0){
						echo $row['visited_pages']."<br>";
						$indice_echelle = 1; $hauteur = bcmul($indice_echelle, $height_graph, 2);
						if ($MaxHauteur_echelle <= $hauteur) { $MaxHauteur_echelle = $hauteur; $EchyMin = '0'; }
					} else { // not display 0 if $max_pages = 0
						$EchyMin  = '';	
						$max_pages = '';
					}

$graph_bymonth .= "
		  	<td style=\"".$td_data_graph_CSS." vertical-align: top; white-space: nowrap;\">".$max_pages."</td>
		  	<td rowspan=\"2\" style=\"".$td_data_graph_CSS." vertical-align: bottom;\">
				<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"".$MaxHauteur_echelle."\" width=\"1\" alt=\"\" title=\"\">
			</td>";

			unset($val_month);

		if ($max_pages > 0 ) {
			//Add month if
			if(sizeof($data_year_graph) < $data_year_graph[0][0]) { //Si nombre de mois de l'année < $data_year_graph[0][0] = dernier mois
				unset($add_month);
				$count = ($data_year_graph[0][0] - sizeof($data_year_graph));
				for($i = 0 ; $i < $count; $i++) {
					$month_add = $i - (11 - sizeof($data_year_graph));
					$add_month[] = array($month_add, 0, 0);
				}
				$format_nb_mois = array_merge($data_year_graph, $add_month);
			} else {
				$format_nb_mois = $data_year_graph;
			}
			
			
			//------------------------------------------------------ 			
			for($i = count($format_nb_mois)-1 ; $i >= 0; $i--) { // le dernier mois est en 1er
				$date_comp = $row['date'];
				$cpt_month = $data_year_graph[$i][2] + 0; // + 0 pour faire disparaitre les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)

				$val_month[$cpt_month][0] = $data_year_graph[$i][1];
				$val_month[$cpt_month][1] = $data_year_graph[$i][2];

				$graph_bymonth .= "<td rowspan=\"2\" valign=\"bottom\">";
				if($max_pages != 0) {
					$indice = @bcdiv($val_month[$cpt_month][1], $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);
				}
				$graph_bymonth .= "<img src=\"images/histo-v.gif\" height=\"".$hauteur."\" width=\"10\" alt=\"".$val_month[$cpt_month][1]."\" title=\"".$val_month[$cpt_month][1]."\">";

				if($max_pages!=0){
					$indice = @bcdiv($val_month[$cpt_month][0], $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);  
				}
				$graph_bymonth .=  "<img src=\"images/histo-vv.gif\" height=\"".$hauteur."\" width=\"10\" alt=\"".$val_month[$cpt_month][0]."\" title=\"".$val_month[$cpt_month][0]."\"></td>";

			}
		}

$graph_bymonth .= "
				  </tr>
				  <tr>
					 <td style=\"text-align: right; vertical-align: bottom;\">".$EchyMin."</td>
				  </tr>
		  
              <tr>
                <td><b>".MSG_MONTH."</b></td>
				<td style=\"text-align: center;\">&nbsp;</td><td style=\"text-align: center;\">&nbsp;</td>"; //echelle x

		//------------------ Display ech X -----------------
		for($i = 1; $i <= 12; $i++){
			$num = $i;
		  	$graph_bymonth .=  "<td>". sprintf("%02d", $num)."</td>";
		}
		//---------------------------------------------------------------------------------------

$graph_bymonth .= "
</tr></table></td></tr>
        <tr>
		  </tr></table></td></tr></table><br>";
 
		 echo $graph_bymonth; //Affichage graph
		 
		 $graph_bymonth ="";


} else {
		###############################################################################################################
		//											DOUBLE GRAPH
		//#############################################################################################################
		//									VISITEURS (DOUBLE GRAPH)
		//#############################################################################################################	
		$max_pages = 0;
		$max_visitors = 0;
		$echy_visitors_MaxHauteur = 0;
		$echy_pages_MaxHauteur = 0;
		$total_pages = 0;
		$total_unique_visitors = 0;
		$hauteur = 0;			
		//--------------------------------- Proportions graph -------------------------------------
		//Hauteur graph
		$height_graph = 100.00; //150
	
		//Font size ech X
		$size_x = '10';
		//Largeur des barres du graph
		$width_bar_graph = $size_x + 18; //'16';
		//Espace entre les barres
		$bar_space = '5';
		$td_width_x = $width_bar_graph + $bar_space;

		$double_graph_bymonth = '';
		//-----------------------------------------------------------
		$echy_visitors_MaxHauteur = '';
		$echy_pages_MaxHauteur = '';

		if (isset($data_year_graph) && sizeof($data_year_graph) > 0 ) { //precedent était testé sur $max_pages, mais $max_pages est calculé aprés
			//Add month if
			if(sizeof($data_year_graph) < $data_year_graph[0][0]) { //Si nombre de mois de l'année < $data_year_graph[0][0] = dernier mois
				unset($add_month);
				$count = ($data_year_graph[0][0] - sizeof($data_year_graph));
				for($i = 0 ; $i < $count; $i++) {
					$month_add = $i - (11 - sizeof($data_year_graph));
					$add_month[] = array($month_add, 0, 0);
				}
				$format_nb_mois = array_merge($data_year_graph, $add_month);
			} else {
				$format_nb_mois = $data_year_graph;
			}
			
			//------------------------------------------------------ 			
			//Max visitors and Max pages
			for($i = count($format_nb_mois)-1 ; $i >= 0; $i--) { // le dernier mois est en 1er
		
				if(!isset($data_year_graph[$i][1])) { $data_year_graph[$i][1] = 0; }
				if(!isset($data_year_graph[$i][2])) { $data_year_graph[$i][2] = 0; }
				
				if(!isset($row['date'])) { $row['date'] = ''; }
				
				$date_comp = $row['date'];
				$cpt_month = $data_year_graph[$i][2] + 0; // + 0 pour supprimer les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)
				
				$val_month[$cpt_month][0] = $data_year_graph[$i][1];
				$val_month[$cpt_month][1] = $data_year_graph[$i][2];

				if($val_month[$cpt_month][1] > $max_pages){
					//TODO $row_unknown_agent['total_visited_page'] for double graph
					//$max_pages = $data_graph[$cpt_jour][1] + $row_unknown_agent['total_visited_page']; // Pour chaque jour On ajoute pages visitées des user agent inconnus
					$max_pages = $val_month[$cpt_month][1]; // Pour chaque jour On ajoute pages visitées des user agent inconnus
				}

				if($val_month[$cpt_month][0] > $max_visitors){
					$max_visitors = $val_month[$cpt_month][0]; 
				}

				$total_unique_visitors = $total_unique_visitors + $val_month[$cpt_month][0];
				$total_pages = $total_pages + $val_month[$cpt_month][1];
			}
		}

		//for display ech y visitors
		$EchyMin_visitors = '0';
		if($max_visitors != 0){
			for($i = count($format_nb_mois)-1 ; $i >= 0; $i--) { // le dernier mois est en 1er
				$date_comp = $row['date'];
				$cpt_month = $data_year_graph[$i][2] + 0; // + 0 pour supprimer les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)
	
				$indice = @bcdiv($val_month[$cpt_month][0], $max_visitors, 2); $hauteur = bcmul($indice, $height_graph, 2);  
				if ($echy_visitors_MaxHauteur <= $hauteur) { $echy_visitors_MaxHauteur = $hauteur; }
			}
		} else { // pour ne pas afficher 0 si $max_visitors = 0
			$EchyMin_visitors  = '';	
			$max_visitors = '';
		}

		//for display ech y pages
		$EchyMin_pages = '0';
		if($max_pages != 0){
			for($i = count($format_nb_mois)-1 ; $i >= 0; $i--) { // le dernier mois est en 1er
				$date_comp = $row['date'];
				$cpt_month = $data_year_graph[$i][2] + 0; // + 0 pour supprimer les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)

				$indice = @bcdiv($val_month[$cpt_month][1], $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);  
				if ($echy_pages_MaxHauteur <= $hauteur) { $echy_pages_MaxHauteur = $hauteur; }
			}
		} else { // pour ne pas afficher 0 si $max_pages = 0
			$EchyMin_page  = '';	
			$max_pages = '';
		}

	//---------------------------------------------------------------------------------------
$Nb_col_span = 12+3;
$spaceEnd = 3;

$double_graph_bymonth .= "
				<table style=\"".$table_border_CSS."\">
				  <tr>
					<td>
					  <table style=\"".$table_frame_CSS."\">
						<tr>
						<td style=\"width:5%; white-space:nowrap;\">
							&nbsp;&nbsp;<img src=\"".$path_allmystats_abs."images/icons/icon_chart.gif\" height=\"32px\" alt=\"".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois.")\" title=\"".MSG_GRAPH_DAY_VISITORS_PAGES." (".MSG_EXCLUDED_BOTS.") - ".$mois."\">
						</td>
				 		<td style=\"".$table_title_CSS."\">".MSG_GRAPH_MONTH_VISITORS_PAGES." ".$year_todo." (".MSG_EXCLUDED_BOTS.")</td>
						</tr>
						<tr>
						  <td colspan=\"2\" style=\"".$td_txt_CSS."\">
							<small>".MSG_TOTAL_VISITORS." = ".$total_unique_visitors."<br>".MSG_TOTAL_PAGES_VISITED." = ".$total_pages."</small>
							<table style=\"".$table_data_CSS."\">
								<tr>
									<!-- vertical space -->
									<td style=\"height:5px;\" colspan=\"".$Nb_col_span."\"></td>
								</tr>
								<tr>
									<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center;\"><b><span style=\"".$style_visits."\">&nbsp;".MSG_VISITORS."</span></b></td>
									<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; white-space: nowrap;\">".$max_visitors."</td>";
									
									//Ech Y
									$double_graph_bymonth .= "
									<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">
										<img src=\"".$path_allmystats_abs."images/histo-v_black.gif\" height=\"".$echy_visitors_MaxHauteur."\" width=\"1\" align=\"top\" alt=\"\" title=\"\">
									</td>";

			for($i = count($format_nb_mois)-1 ; $i >= 0; $i--) { // le dernier mois est en 1er
				$date_comp = $row['date'];
				
				if(isset($data_year_graph[$i][2])) {
					$cpt_month = $data_year_graph[$i][2] + 0; // + 0 pour supprimer les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)
				}

				$double_graph_bymonth .= "
				<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">";
				if($max_visitors != 0) {
					$indice = @bcdiv($val_month[$cpt_month][0], $max_visitors, 2); $hauteur = bcmul($indice, $height_graph, 2);
				}

					$double_graph_bymonth .= "
					<img src=\"images/histo-vv.gif\" height=\"".$hauteur."\" width=\"".$width_bar_graph."\" alt=\"".$val_month[$cpt_month][0]."\" title=\"".$val_month[$cpt_month][0]."\">
				</td>";
			}

	
			$double_graph_bymonth .= "
			</tr>
			<tr>
				<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: bottom;\">".$EchyMin_visitors."</td>
			</tr>
			<tr>
				<td colspan=\"2\" style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top;\">".MSG_MONTH."</td>
				<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; height: 2px;\">
					<img src=\"images/histo-v_black.gif\" height=\"1px\" alt=\"\" align=\"top\" title=\"\">
				</td>";

				for($month = 1 ; $month <= 12; $month++) {  //Axe x
					$double_graph_bymonth .= "
					<td style=\"".$td_data_graph_CSS." text-align: center; vertical-align: top; height: 2px; width:".$td_width_x."px;\">
						<img src=\"images/histo-v_black.gif\" height=\"1px\" width=\"".$td_width_x."\" alt=\"\" title=\"\"><br>
						<span style=\"font-size:".$size_x."px\">". sprintf("%02d", $month)."</span>
					</td>";
				}
				$double_graph_bymonth .= "<td style=\"width:".$spaceEnd."px;\"></td>"; // space end	

			$double_graph_bymonth .= "
			<tr>";
		
		//espace entre les 2 graphs
		$double_graph_bymonth .= "
				</tr>
				<tr>
					<td colspan=\"".$Nb_col_span."\" style=\"".$td_data_graph_CSS." height: 5px;\"></td>
				</tr>
				</tr>"; 

	$double_graph_bymonth .= "
				</tr>";

		//###############################################################################
		//					 GRAPH VISITED PAGES (DOUBLE GRAPH) 

       		$double_graph_bymonth .= "
				<tr>
					<td rowspan=\"3\" style=\"".$td_data_graph_CSS." text-align: center;\">
						<b><span style=\"".$page_view."\">&nbsp;".MSG_VISITED_PAGES."</span></b>
					</td>
				</tr>
				<tr>
					<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; white-space: nowrap;\">".$max_pages."</td>";
					
					//Ech Y
					$double_graph_bymonth .= "
					<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">
						<img src=\"images/histo-v_black.gif\" height=\"".$echy_pages_MaxHauteur."\" width=\"1\" align=\"top\" alt=\"\" title=\"\">
					</td>";
				
			for($i = count($format_nb_mois)-1 ; $i >= 0; $i--) { // le dernier mois est en 1er
				$date_comp = $row['date'];
				
				if(isset($data_year_graph[$i][2])) {
				$cpt_month = $data_year_graph[$i][2] + 0; // + 0 pour supprimer les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)
				}

				$double_graph_bymonth .= "
				<td rowspan=\"2\" style=\"".$td_data_graph_CSS." text-align: center; vertical-align: bottom;\">";
				if($max_visitors != 0) {
					$indice = @bcdiv($val_month[$cpt_month][1], $max_pages, 2); $hauteur = bcmul($indice, $height_graph, 2);
				}
	
					$double_graph_bymonth .= "
					<img src=\"images/histo-v.gif\" height=\"".$hauteur."\" width=\"".$width_bar_graph."\" alt=\"".$val_month[$cpt_month][1]."\" title=\"".$val_month[$cpt_month][1]."\">";
					$double_graph_bymonth .= "
				</td>";
			}
			
			$double_graph_bymonth .= "
			</tr>
			<tr>
				<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: bottom;\">".$EchyMin_pages."</td>
			</tr>
			<tr>
				<td colspan=\"2\" style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top;\">".MSG_MONTH."</td>
				<td style=\"".$td_data_graph_CSS." text-align: right; vertical-align: top; height: 2px;\">
					<img src=\"images/histo-v_black.gif\" height=\"1px\" alt=\"\" align=\"top\" title=\"\">
				</td>";
	
				for($month = 1 ; $month <= 12; $month++) {  //Axe x
					$double_graph_bymonth .= "
					<td style=\"".$td_data_graph_CSS." text-align: center; vertical-align: top; height: 2px; width:".$td_width_x."px;\">
						<img src=\"images/histo-v_black.gif\" height=\"1px\" width=\"".$td_width_x."\" alt=\"\" title=\"\"><br>
						<span style=\"font-size:".$size_x."px\">". sprintf("%02d", $month)."</span>
						<div style=\"font-size:3px\"><br></div>
					</td>";
				}
				$double_graph_bymonth .= "<td style=\"width:".$spaceEnd."px;\"></td>"; // space end	
				
	$double_graph_bymonth .= "
				</tr>
				</tr></table>
				</td></tr></table>
				</td></tr>
				</table><br />";
	
	echo $double_graph_bymonth;
	$double_graph_bymonth = '';
}

##########################################################################################
?>
