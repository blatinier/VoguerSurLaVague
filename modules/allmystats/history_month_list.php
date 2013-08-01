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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/history_month_list.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

	$mois_annee = date('/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s")))); //  actuel
	$mois = substr($mois_annee, 1);

	// ------------  //Affichage graph -------------
	include(FILENAME_GRAPH_MONTH_DAYS);
	echo $graph_byday;
	$graph_byday ="";
	//----------------------------------------------

	//####################################################################################
	//				 LISTE LIENS VERS JOUR DU MOIS EN COURS 
	//####################################################################################

	$display_tableau_jours = "";
	$show_graph_jour_donnes = "";
			
			//$row['date'] pour chaque jour du mois																											//desc ou asc
			$result_date = mysql_query("select count(visits) as somme, date from ".TABLE_UNIQUE_VISITOR." where date like '%".$mois_annee."' group by date order by date DESC") or die ("erreur compte visite");

			//while Pour chaque jour
			while($row_date = mysql_fetch_array($result_date)){

				$result = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_VISITOR." where date like '".$row_date['date']."'");
				$row = mysql_fetch_array($result);
				$PagesView_HorsBots = $row['total_pages_view'];
				$Visits_HorsBots = $row['nb_visitors'];
/*		
				//On ajoute Visitors et pages visitées des user agent inconnus --> NO NO
				// 2013-04-16 - Standardization of the date
				$exd_month_date = explode('/', $row_date['date']);
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

				$result = mysql_query("select count(*) as nb_visitors, sum(visits) as total_pages_view from ".TABLE_UNIQUE_BOT." where date like '".$row_date['date']."'");
				$row = mysql_fetch_array($result);
				$PagesView_robots = $row['total_pages_view'];
				$Visits_robots = $row['nb_visitors'];

				$Total_Visits_HorsBots = $Visits_HorsBots + $Visits_robots;
				$Total_PagesView_HorsBots = $PagesView_HorsBots + $PagesView_robots;


$display_tableau_jours .= '		
				 <tr>
					<td style="'.$td_data_CSS.' text-align: center;">
					<form name="forme" method="post" action="'.$_SERVER['PHP_SELF'].'">
						<input type="hidden" name="when" value="'.$row_date['date'].'">
						<input  class="submitDate" type="submit" name="datemois" size="1" value="'.$row_date['date'].'" alt="'.$row_date['date'].'" title="'.$row_date['date'].'">
					</form>
					</td>	
					<td style="'.$td_data_CSS.' width: 25%;">'.MSG_VISITORS_AND_ROBOTS.'<br>'.MSG_VISITORS.'<br>'.MSG_BOTS.'</td>
					<td style="'.$td_data_CSS.' text-align: center; vertical-align: top;">'.$Total_Visits_HorsBots.'<br>'.$Visits_HorsBots.'<br>'.$Visits_robots .'</td>
					<td style="'.$td_data_CSS.' text-align: center; vertical-align: top;">'.$Total_PagesView_HorsBots.'<br>'.$PagesView_HorsBots.'<br>'.$PagesView_robots .'</td>
				</tr>';	

			} // End while

	
	//###########################################################################################
	//						 DISPLAY TABLEAU JOURS
	//###########################################################################################
	
$show_graph_jour = "";

echo '
<table style="'.$table_border_CSS.'">
	<tr>
	<td>
		<table style="'.$table_frame_CSS.'">
        <tr>
          <td style="width:5%; white-space:nowrap;">
				&nbsp;&nbsp;<img src="'.$path_allmystats_abs.'images/icons/icon_archives.gif" height="32px" alt="'.MSG_CURRENTLY_MONTHLY.'" title="'.MSG_CURRENTLY_MONTHLY.'">
		  </td>
          <th style="'.$table_title_CSS.'">'. MSG_CURRENTLY_MONTHLY. '</th>
          </tr>
        <tr>
          <td colspan="2">
            <table style="'.$table_data_CSS.'">
              <tr>
                <th style="'.$td_data_CSS.'">'.MSG_DATES.'</th>
                <th style="'.$td_data_CSS.'">&nbsp;</th>
                <th style="'.$td_data_CSS.'">'.MSG_VISITORS.'</th>
                <th style="'.$td_data_CSS.'">'.MSG_VISITED_PAGES.'</th>
                </tr>';

				echo $display_tableau_jours; //Affichages 

echo '
</table></td></tr></table></td></tr></table><br>';
?>
