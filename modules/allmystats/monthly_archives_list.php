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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/monthly_archives_list.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------


		################################ ARCHIVES LIST ########################################

		$booleen = 1; // sert à terminer la boucle 
		$mois_encours = date('m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$mois = $mois_encours;
		$annee = date('Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$year_todo = $annee;

		$result_min_code = mysql_query("select min(code) as cd from ".TABLE_UNIQUE_VISITOR."");
		$min_code = mysql_fetch_array($result_min_code);

		$Min_annee = substr($min_code['cd'], 0, 4);
		$Min_mois = substr($min_code['cd'], 4, 2);
		$Min_date = $Min_annee."/".$Min_mois;

		echo '
		<table style="'.$table_border_CSS.'">
		  <tr>
			<td>
			  <table style="'.$table_frame_CSS.'">
				<tr>
          	 	  <td style="width:5%; white-space:nowrap;">
						&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_archives.gif" height="32px" alt="'.MSG_ARCHIVE.' '.$annee.'" title="'.MSG_ARCHIVE.' '.$annee.'">
				  </td>
				  <th style="'.$table_title_CSS.'">'.MSG_ARCHIVE. " ".$annee.'</th>
			    </tr>
				<tr>
				  <td colspan="2">
					<table style="'.$table_data_CSS.'">
					  <tr>
						<th style="'.$td_data_CSS.'">'.MSG_DATES.'</th>
						<th style="'.$td_data_CSS.'">&nbsp;</th>
						<th style="'.$td_data_CSS.'">'.MSG_VISITORS.'</th>
						<th style="'.$td_data_CSS.'">'.MSG_VISITED_PAGES.'</th>
						<th style="'.$td_data_CSS.'">'.MSG_ARCHIVE.'</th>
					  </tr>';
		
		unset($data_year_graph);
		while($booleen == 1){
			$date = "%/".$mois_encours."/".$annee;

			$result = mysql_query("select * from ".TABLE_UNIQUE_VISITOR." where date like '".$date."'");
			$Visits_HorsBots = mysql_num_rows($result);
			
			if($Visits_HorsBots == 0){
				$booleen = 0;
			}
			
			$result = mysql_query("select sum(p.visits) as somme from ".TABLE_UNIQUE_VISITOR." v,".TABLE_PAGE_VISITOR." p where date like '".$date."' and p.code=v.code");
			$row = mysql_fetch_array($result);
			$PagesView_HorsBots = $row['somme'];
/*
			//On ajoute Visitors et pages visitées des user agent inconnus --> NO NO
			// 2013-04-16 - Standardization of the date
			$exd_month_date = explode('/', $date);
			if (isset($exd_month_date[2]) && $exd_month_date[2]) { // by day
				$MySQL_month_date = $exd_month_date[2].'-'.$exd_month_date[1].'-'.$exd_month_date[0];
			} else { // by month
				$MySQL_month_date = $exd_month_date[1].'-'.$exd_month_date[0];
			}
			$result = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_BAD_AGENT." where date like '".$MySQL_month_date."%' and type='I'"); 
			$row_bad_agent = mysql_fetch_array($result);
			$PagesView_HorsBots = $PagesView_HorsBots + $row_bad_agent['total_pages_view'];
			$Visits_HorsBots =  $Visits_HorsBots + $row_bad_agent['nb_visitors'];
*/
			$result = mysql_query("select * from ".TABLE_UNIQUE_BOT." where date like '".$date."'");
			$Visits_robots = mysql_num_rows($result);
			
			$result=mysql_query("select sum(p.visits) as somme from ".TABLE_UNIQUE_BOT." v,".TABLE_PAGE_BOT." p where date like '".$date."' and p.code=v.code");
			$row = mysql_fetch_array($result);
			$PagesView_robots = $row['somme'];

			$Total_visitors = $Visits_HorsBots + $Visits_robots;
			$Total_visits = $PagesView_HorsBots + $PagesView_robots;

			//Pour graph ---------------------
			$data_year_graph[] = array($mois_encours, $Visits_HorsBots, $PagesView_HorsBots);
			//--------------------------------

			//Pour mois en cours
			echo '
			<td style="'.$td_data_CSS.' text-align: center;">
				<form name="forme" method="post" action="'.$_SERVER['PHP_SELF'].'">
					<input type="hidden" name="type" value="cumulpage">
					<input type="hidden" name="mois" value="'.$mois_encours."/".$annee.'">
					<input  class="submitDate" type="submit" name="datemois" size="1" value="'.$mois_encours.'/'.$annee.'" alt="'.$mois_encours.'/'.$annee.'" title="'.$mois_encours.'/'.$annee.'">
				</form>
			</td>
			<td style="'.$td_data_CSS.' width: 25%;">'.MSG_VISITEURS_AND_BOTS.'<br>'.MSG_VISITEURS.'<br>'.MSG_BOTS.'</td>
			<td style="'.$td_data_CSS.' text-align: center; vertical-align: top;">'.$Total_visitors.'<br>'.$Visits_HorsBots.'<br>'.$Visits_robots.'</td>
			<td style="'.$td_data_CSS.' text-align: center; vertical-align: top;">'.$Total_visits.'<br>'.$PagesView_HorsBots.'<br>'.$PagesView_robots.'</td>
			<td style="'.$td_data_CSS.' text-align: center;">';
			
			$Annee_actuelle = date('Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			if($mois_encours."/".$annee <> $mois."/".$Annee_actuelle){
				echo MSG_MONTH_NOT_CACHED;
			} else{
				echo MSG_IN_PROGRESS;
			}
			echo '</td></tr>';
			
			//---------------------------------------
			//recherche du mois precedent non en cache
			//---------------------------------------

			if($mois_encours == "01")	{ 
				$break_table = true;
				$mois_encours = "12";
				$annee--;
				$date = "%/".$mois_encours."/".$annee;
				$result = mysql_query("select count(*) from ".TABLE_UNIQUE_VISITOR." where date like '".$date."'");
				$row = mysql_fetch_row($result);
				if($row[0] == '0'){ $mois_en_cache = -1; } else { $mois_en_cache = 1; }
			} else {
				$break_table = false;
				$mois_encours--;
				if($mois_encours < 10) {
					$mois_encours = "0".$mois_encours;
				}
				$date = "%/".$mois_encours."/".$annee;
				$result = mysql_query("select count(*) from ".TABLE_UNIQUE_VISITOR." where date like '".$date."'");
				$row = mysql_fetch_row($result);
				if($row[0] == '0'){ $mois_en_cache = -1; } else { $mois_en_cache = 1; }
			}
			if ($Min_date > $annee."/".$mois_encours) { $booleen = 0; }

			//---------------------------------------------------------------
			//Ajout break pour mois en cours
			if($break_table) {
				echo '</table></td></tr></table></td></tr></table><br />';

				//Affichage graph / month
				include(FILENAME_GRAPH_YEAR_MONTHS);
				unset($data_year_graph);

				echo '<hr width="500" noshade><br />';
				
				//$year_todo = $row['annee'] - 1;
				$year_todo = $annee; // diff
				echo '
				<table style="'.$table_border_CSS.'">
				  <tr>
					<td>
					  <table style="'.$table_frame_CSS.'">
						<tr>
          	 			  <td style="width:5%; white-space:nowrap;">
							&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_archives.gif" height="32px" alt="'.MSG_ARCHIVE.' '.$year_todo.'" title="'.MSG_ARCHIVE.' '.$year_todo.'">
						  </td>
						  <td style="'.$table_title_CSS.'">'.MSG_ARCHIVE.' '.$year_todo.'</td>
						  </tr>
						<tr>
						  <td colspan="2">
							<table style="'.$table_data_CSS.'">
							  <tr>
								 <th style="'.$td_data_CSS.'">'.MSG_DATES.'</th>
								 <th style="'.$td_data_CSS.'">&nbsp;</th>
								 <th style="'.$td_data_CSS.'">'.MSG_VISITORS.'</th>
								 <th style="'.$td_data_CSS.'">'.MSG_VISITED_PAGES.'</th>
								 <th style="'.$td_data_CSS.'">'.MSG_ARCHIVE.'</th>
							  </tr>';
			}
			//---------------------------------------------------------------

			//$booleen  //pour arreter la grande boucle
			//$mois_en_cache  //pour arreter la sous boucle
			while ($mois_en_cache < '0' && $booleen <> 0) { 
			
					if ($Min_date > $annee."/".$mois_encours) { 
						$booleen = 0;
					}
			
					if($mois_encours == "01")	{ 
						$break_table = true;
						$mois_encours = "12";
						$annee--;
						$date = "%/".$mois_encours."/".$annee;
						$result = mysql_query("select count(*) from ".TABLE_UNIQUE_VISITOR." where date like '".$date."'");
						$row = mysql_fetch_row($result);
						if($row[0] == '0'){ $mois_en_cache = -1; } else { $mois_en_cache = 1; }
					} else {
						$break_table = false;
						$mois_encours--;
						if($mois_encours < 10) {
							$mois_encours = "0".$mois_encours;
						}
						$date = "%/".$mois_encours."/".$annee;
						$result = mysql_query("select count(*) from ".TABLE_UNIQUE_VISITOR." where date like '".$date."'");
						$row = mysql_fetch_row($result);
						if($row[0] == '0'){ $mois_en_cache = -1; } else { $mois_en_cache = 1; }
					}
			}
		}

		///////////////////////////////////////////////////
		//Lecture Archives
		$result = mysql_query("select * from ".TABLE_ARCHIVE." order by annee desc, mois desc");
		while($row = mysql_fetch_array($result)){

			if ($row['mois'] < 10){ $row['mois'] = "0".$row['mois']; }
			
			// ---------------- Security clean MySQL tables for archived dates (month/Year) ------------
			// Date displayed
			$month_year_displayed = $row['mois']."/".$row['annee']; // mm/Y
			$year_displayed = $row['annee'];
			$month_displayed = $row['mois'];

			$query_delete = mysql_query("delete from ".TABLE_MONTHLY_KEYWORDS." where date='".$month_year_displayed."'") or die('Erreur SQL! TABLE_MONTHLY_KEYWORDS: '.$query_delete.'<br>'.mysql_error());
			
			$query_delete = mysql_query("delete from ".TABLE_DAYS_KEYWORDS." where date like '%".$month_year_displayed."'") or die('Erreur SQL! TABLE_DAYS_KEYWORDS: TABLE_DAYS_PAGES: '.$query_delete.'<br>'.mysql_error()); ;
			$query_delete = mysql_query("delete from ".TABLE_DAYS_PAGES." where date like '%".$month_year_displayed."'") or die('Erreur SQL! '.$query_delete.'<br>'.mysql_error()); 
						
			$query_delete = mysql_query("delete from ".TABLE_UNIQUE_VISITOR." where date like '%".$month_year_displayed."'") or die('Erreur SQL! TABLE_UNIQUE_VISITOR: '.$query_delete.'<br>'.mysql_error());
			$query_delete = mysql_query("delete from ".TABLE_PAGE_VISITOR." where code like '".$year_displayed.$month_displayed."%'") or die('Erreur SQL! TABLE_PAGE_VISITOR: '.$query_delete.'<br>'.mysql_error());
	
			$query_delete = mysql_query("delete from ".TABLE_UNIQUE_BOT." where date like '%".$month_year_displayed."'") or die('Erreur SQL! TABLE_UNIQUE_BOT: '.$query_delete.'<br>'.mysql_error());
			$query_delete = mysql_query("delete from ".TABLE_PAGE_BOT." where code like '".$year_displayed.$month_displayed."%'") or die('Erreur SQL! TABLE_PAGE_BOT: '.$query_delete.'<br>'.mysql_error());
	
			//$query_delete = mysql_query("delete from ".TABLE_UNIQUE_BAD_AGENT." where date like '%".$month_year_displayed."'") or die('Erreur SQL! TABLE_UNIQUE_BAD_AGENT: '.$query_delete.'<br>'.mysql_error());
			//$query_delete = mysql_query("delete from ".TABLE_PAGE_BAD_AGENT." where code like '".$year_displayed.$month_displayed."%'") or die('Erreur SQL! TABLE_PAGE_BAD_AGENT: '.$query_delete.'<br>'.mysql_error());

			// 2013-04-16 - Standardization of the date
			$exd_month_date = explode('/', $month_year_displayed);
			if (isset($exd_month_date[2]) && $exd_month_date[2]) { // by day
				$MySQL_month_date = $exd_month_date[2].'-'.$exd_month_date[1].'-'.$exd_month_date[0];
			} else { // by month
				$MySQL_month_date = $exd_month_date[1].'-'.$exd_month_date[0];
			}
			$query_delete = mysql_query("delete from ".TABLE_UNIQUE_BAD_AGENT." where date like '".$MySQL_month_date."%'") or die('Erreur SQL! TABLE_UNIQUE_BAD_AGENT: '.$query_delete.'<br>'.mysql_error());
			
			$query_delete = mysql_query("delete from ".TABLE_PAGE_BAD_AGENT." where code like '".$year_displayed.$month_displayed."%'") or die('Erreur SQL! TABLE_PAGE_BAD_AGENT: '.$query_delete.'<br>'.mysql_error());

			//------------------------------------------------------------------------------------------

			if ($row['mois'] == "01") {
				$break_table = true;
			} else {
				$break_table = false;
			}
			
			//Pour graph ---------------------
			$data_year_graph[] = array($row['mois'], $row['visites_hors_bot'], $row['pages_hors_bot']);
			//--------------------------------

			echo '<tr>
			<td style="'.$td_data_CSS.' text-align: center;"><b>';
			
			$format_date_file_name = $row['annee'].'-'.$row['mois'];
			
			if (file_exists('cache/stats_'.$site.'_'.$format_date_file_name.'.php')) {
				echo '				
				<form name="form_histoMonth" method="get" action="cache/stats_'.$site.'_'.$format_date_file_name.'.php" target="_blank">
					<input class="submitDate" type="submit" name="datemois" size="1" value="'.$row['mois']."/".$row['annee'].'" alt="'.$row['mois']."/".$row['annee'].'" title="'.$row['mois']."/".$row['annee'].'">
				</form>';
			} else {
				echo $row['mois']."/".$row['annee']."</b></td>"; // Ancienne archive -> pas de cache	
			}		
	
			echo '
			<td style="'.$td_data_CSS.' width: 25%;">'.MSG_VISITEURS_AND_BOTS.'<br>'.MSG_VISITEURS.'<br>'.MSG_BOTS.'</td>
			<td style="'.$td_data_CSS.' text-align: center; vertical-align: top;">'.$row['visiteur'].'<br>'.$row['visites_hors_bot'].'<br>'.$row['visites_robot'].'</td>
			<td style="'.$td_data_CSS.' text-align: center; vertical-align: top;">'.$row['visite'].'<br>'.$row['pages_hors_bot'].'<br>'.$row['pages_robots'].'</td>';
	
			if (file_exists('cache/stats_'.$site.'_'.$format_date_file_name.'.php')) {
				echo '<td style="'.$td_data_CSS.' text-align: center;">'.MSG_MONTH_CACHED.'</td>';	
			} else {
				echo '<td style="'.$td_data_CSS.' text-align: center;">'.MSG_MONTH_ARCHIVED.'</td>';
			}
			
			echo '</tr>';

			if($break_table) {
				echo '</table></td></tr></table></td></tr></table><br />';

				//Affichage graph / month
				include(FILENAME_GRAPH_YEAR_MONTHS);
				unset($data_year_graph);

				echo '<hr width="500" noshade><br />';
				
				$year_todo = $row['annee'] - 1;
				echo '
				<table style="'.$table_border_CSS.'">
				  <tr>
					<td>
					  <table style="'.$table_frame_CSS.'">
				  		<tr>
          	 			  <td style="width:5%; white-space:nowrap;">
								&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_archives.gif" height="32px" alt="'.MSG_ARCHIVE.' '.$year_todo.'" title="'.MSG_ARCHIVE.' '.$year_todo.'">
						  </td>
						  <td style="'.$table_title_CSS.'">'.MSG_ARCHIVE.' '.$year_todo.'</td>
						</tr>
						<tr>
						  <td colspan="2">
							 <table style="'.$table_data_CSS.'">
							  <tr>
								<th>'.MSG_DATES.'</th>
								<th>&nbsp;</th>
								<th>'.MSG_VISITORS.'</th>
								<th>'.MSG_VISITED_PAGES.'</th>
								<th>'.MSG_ARCHIVE.'</th>
								</tr>';
			}

		}
		?>      
</table></td></tr></table></td></tr></table><br />
<?php
		//Mois non terminé Affichage graph / month
		include(FILENAME_GRAPH_YEAR_MONTHS);
	
		unset($data_year_graph);

?>
