<?
/*
 -------------------------------------------------------------------------
 AllMyStats V1.39 - Statistiques de fréquentation visiteurs et robots
 -------------------------------------------------------------------------
 Copyright (C) 2000 - Cédric TATANGELO (Cedstat)
 Copyright (C) 2008 - Herve Seywert
 -------------------------------------------------------------------------
 Web:   http://www.wertronic.com
 -------------------------------------------------------------------------
 Ce programme est libre, vous pouvez le redistribuer et/ou le modifier
 selon les termes de la Licence Publique Génrale GNU publiée par la Free
 Software Foundation .
 -------------------------------------------------------------------------
*/

	//----------------------------------------------------------------------------------------------------
	//Mise en en forme ($AllBots) pour preg_match des bot connus (dans la table + bot en générale (bot, spider , etc)
	$result1=mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER.""); 
	$AllBots = '/Bot|Slurp|Scooter|Spider|crawl|';

	while($row=mysql_fetch_array($result1)){
			$Form_chaine = str_replace('/','\/',$row['bot_name']);
			$Form_chaine = str_replace('+','\+',$Form_chaine);
			$Form_chaine = str_replace('(','\(',$Form_chaine);
			$Form_chaine = str_replace(')','\)',$Form_chaine);
			$AllBots .= $Form_chaine.'|';
	}
	$AllBots = substr($AllBots,0,strlen($AllBots)-1); //supp last |
	$AllBots .= '/i';
	//------------------------------------------------------------------------------------------------------
	//------------------------ Mise en tableau de la table bad user agent ----------------------------------
	unset($Matrice_bad_user_agent);
	$Bad_User_Agent=mysql_query("select * from ".TABLE_BAD_USER_AGENT.""); //
	while($bad_agents=mysql_fetch_array($Bad_User_Agent)){ // Mise en tableau des bad agents
		$Matrice_bad_user_agent[] = array($bad_agents['user_agent'], $bad_agents['info'],$bad_agents['type']);
	}
	//------------------------------------------------------------------------------------------------------
		
	
	$mois_annee = date('/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s")))); //  actuel
	$mois = substr($mois_annee,1);

################################################################################################################################################################
			// Graphique visiteurs et page visitées par Jour (todo en fonction car dans car existe aussi dans cumulpage.php, stats_in.php, histomois.php)
		//---------------------------------------------------------------------------------------------

		//recup des données
		$result=mysql_query("select agent, referer, date, code, ip, nb_visite from ".TABLE_VISITEUR." where date like '%$mois' order by code ASC");
			unset ($val_jour);
			while($row=mysql_fetch_array($result)){
				if(!preg_match($AllBots, $row['agent']) && $trash==false) {		
					$date_comp = $row['date'];
					$cpt_jour = substr($date_comp,0,2)+0; // + 0 pour faire disparaitre les 0 devant 01, 02, 03 etc (aussi simple qu'une regex)
					$val_jour[$cpt_jour][0] = $val_jour[$cpt_jour][0] + 1;
					$val_jour[$cpt_jour][1] = $val_jour[$cpt_jour][1] + $row['nb_visite'];

					$total_nb_visiteurs = $total_nb_visiteurs + 1;
					$total_nb_pages_visitees = $total_nb_pages_visitees + $row['nb_visite'];

					//Nombre max de pages visité, pour height graph
					if($val_jour[$cpt_jour][1]>$max_pages){
						$max_pages=$val_jour[$cpt_jour][1];
					}
					
					//Pour affichage echelle y
					if($max_pages!=0){
						$indice_echelle = bcdiv($val_jour[$cpt_jour][1],$max_pages,2); $hauteur=bcmul($indice_echelle,180.00,2);
						if ($MaxHauteur_echelle <= $hauteur) { $MaxHauteur_echelle = $hauteur; $EchyMin = '0'; }
					} else { // pour ne pas afficher 0 si $max_pages = 0
						$EchyMin  = '';	
						$max_pages = '';
					}
				}
			}
			$Nb_jours =  substr($date_comp,0,2);


$graph_byday = "";

		//---------- Affichage --------------------------------------------------------------------	
$graph_byday .= "
<TABLE align=center CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE>"
		  	.$MSG_STAT_GRAF_JOUR_TITRE." (".$MSG_ROBOTS_EXCLUS.") - ".$mois."
		  </TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
		  	<small>Total visiteurs = ".$total_nb_visiteurs."<br>
			Total pages visitées = ".$total_nb_pages_visitees."</small>
            <TABLE border=0 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
          		<td rowspan=\"2\">
					<B><SPAN class=PAGESVUES>".$MSG_PAGESVISITES."</SPAN><BR>
					& 
		  			<SPAN class=VISITES>".$MSG_VISITE."</SPAN></B>
		  		</TD>";

$graph_byday .= "
		  	<td nowrap=nowrap valign=\"top\">".$max_pages."</td>
		  	<td rowspan=\"2\" valign=\"bottom\"><img src=\"images/histo-v_black.gif\" height=\"".$MaxHauteur_echelle."\" width=\"1\" alt=\"\" title=\"\"></td>";
			
          for($i=1;$i<=$Nb_jours;$i++){
				$graph_byday .= "<td rowspan=\"2\" valign=\"bottom\">";
				if($max_pages!=0) {
					$indice=bcdiv($val_jour[$i][1],$max_pages,2); $hauteur=bcmul($indice,180.00,2);
				}
				$graph_byday .= "<img src=\"images/histo-v.gif\" height=\"".$hauteur."\" width=\"7\" alt=\"".$val_jour[$i][1]."\" title=\"".$val_jour[$i][1]."\">";

				if($max_pages!=0){
					$indice=bcdiv($val_jour[$i][0],$max_pages,2); $hauteur=bcmul($indice,180.00,2);  
				}
				$graph_byday .=  "<img src=\"images/histo-vv.gif\" height=\"".$hauteur."\" width=\"7\" alt=\"".$val_jour[$i][0]."\" title=\"".$val_jour[$i][0]."\"></td>";
          }

$graph_byday .= "
		  </TR>
		  <tr>
			 <td align=\"right\" valign=\"bottom\">".$EchyMin."</td>
		  </tr>
		  
              <TR>
                <TD><B>". $MSG_GRAF_JOUR."</B></TD>

 	    <td align=center>&nbsp;</td><td align=center>&nbsp;</td>"; // Pour echelle x

		//----------------- calcul jour du mois et week end pour echelle x ------------------------
		$jm = explode("/",$mois);
		$nbjourdumois = maxDaysInMonth($jm[0], $jm[1]);
		
		// Premier samedi du mois (6eme jour de la semaine) fonction get_first_day
		$premiersamedi = strftime("%d", get_first_day(6, $jm[0], $jm[1])); 
		$weekend = "/";
		if ($premiersamedi == 7) { $weekend .= sprintf("%02d",1).'|'; } //Le 1er jour du mois est un dimanche
		for($i=$premiersamedi;$i<=$nbjourdumois;$i=$i+7){
			$week = $i;
			$week = $week+0;
			$weekend .= sprintf("%02d",$week).'|'; 
			$weekend .= sprintf("%02d",$week+1) .'|';
		}
		$weekend = substr($weekend,0,strlen($weekend)-1); //supp last |
		$weekend .= "/";
		//------------------ Affichage echelle x -----------------
		for($i=1;$i<=$nbjourdumois;$i++){
			$num=$i;
			if(!preg_match($weekend, sprintf("%02d",$num))) {	//$num+0 pour supprimer les 0 devant 01, 02 ,03 etc
		  		$graph_byday .=  "<td align=center>". sprintf("%02d", $num)."</td>";
		  	} else {
		  		$graph_byday .=  "<td align=center><b><font color=#990000>". sprintf("%02d", $num)."</font></b></td>";
			}
		}
		//---------------------------------------------------------------------------------------

$graph_byday .= "
</TR></TBODY></TABLE><!-- Rows END --></TD></TR><!-- footer -->
        <TR>
		  </TR></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><br>";
 
		 echo $graph_byday; //Affichage graph
		 //$show_cumul_page .= $graph_byday;
		 $graph_byday ="";

################################################################################################################################################################

############################################## Calcul données tableau lien vers details jour ###################################################################
$show_tableau_jours = "";
$show_graph_jour_donnes = "";
			//determination du nombre de visiteur																											//desc ou asc
			$result_date=mysql_query("select count(nb_visite) as somme,date from ".TABLE_VISITEUR." where date like '%".$mois_annee."' group by date order by date asc") or die ("erreur compte visite");

			while($row_date=mysql_fetch_array($result_date)){

				//Calcul Nb visiteurs et pages hors bot Robots
				$result=mysql_query("select * from ".TABLE_VISITEUR." where date like '$row_date[date]'");
				$Nb_pages_vues_HorsBots=0;
				$NbVisiteurs_HorsBots=0;
				$NbpageVues_robots=0;
				$NbVisites_robots=0;

				while($row=mysql_fetch_array($result)){
					//-- Exclusion des Bad user agent reconnus (non compté comme visiteur) ----
					$trash=false;
					//NE PAS tester strlen(trim($User_Agent))<=1 car le user agent peut être vide mais IP et host non vide
					//Compte les user agent inconnus mais mais pas les spammers
					for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
						if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
							$trash=true;
						}
					}
					//Visistes et pages vues hors robots et hors bad ou user agent inconnus
					if(!preg_match($AllBots, $row[agent]) && $trash==false) {		
						$Nb_pages_vues_HorsBots = $Nb_pages_vues_HorsBots + $row[nb_visite];
						$NbVisiteurs_HorsBots = $NbVisiteurs_HorsBots + 1;
						$Total_NbVisiteurs_HorsBots = $Total_NbVisiteurs_HorsBots + 1;
						$Total_NbPageVues_HorsBots = $Total_NbPageVues_HorsBots + $row[nb_visite];
					}			
		
					if(preg_match($AllBots, $row[agent]) && $trash==false) {		
						$NbpageVues_robots = $NbpageVues_robots+$row[nb_visite];
						$NbVisites_robots = $NbVisites_robots+1;
					}			
		
				}
		//----------------------------------------------------------------------------------------------
				$sql="select sum(p.nb_visite) as somme from ".TABLE_VISITEUR." v, ".TABLE_PAGE." p where v.date like '".$row_date[date]."' and v.code=p.code ";
				$result1=mysql_query($sql) or die ("erreur compte pages vues");
				$row_date2=mysql_fetch_array($result1);

$show_tableau_jours .= '		
				 <tr>
					<td align="center">
					<form name="forme" method="post" action="'.$PHP_SELF.'">
						<input type="hidden" name="when" value="'.$row_date['date'].'">
						<input  class="submitDate" type="submit" name="datemois" size="1" value="'.$row_date['date'].'" alt="'.$row_date['date'].'" title="'.$row_date['date'].'">
					</form>
					</td>	
					<td width="25%">'.$MSG_VISITEURS_AND_ROBOTS.'<br>'.$MSG_VISITEURS.'<br>'.$MSG_BOT.'</td>
					<td align="center">'.$row_date[somme].'<br>'.$NbVisiteurs_HorsBots.'<br>'.$NbVisites_robots .'</td>
					<td align="center">'.$row_date2[somme].'<br>'.$Nb_pages_vues_HorsBots.'<br>'.$NbpageVues_robots .'</td>
				</tr>';	

		} // End while principal

$show_graph_jour = "";

################################################################################################################
	//---------------------------- Afichage tableau lien vers details jour ------------------------------------
echo '<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE>'. $MSG_CUMUL_TITRE. '</TH>
          </TR>
        <TR>
          <TD><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
                <TH>'. $MSG_DATES. '</TH>
                <TH>&nbsp;</TH>
                <TH>'.$MSG_VISITE.'</TH>
                <TH>'.$MSG_PAGESVISITES.'</TH>
                </TR>';

				echo $show_tableau_jours; //Affichages 

echo '</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><br>';

	//--------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------------------
############################################# Functions ######################################################
//TODO rep functions car existe aussi dans cumulpage.php, stats_in.php, histomaois.php
/**
* Fonction retournant le nombre de jours dans un mois.
* @param integer $month Mois de 1 à 12
* @param integer $year Année
* @return integer Nombre de jours
*/
function maxDaysInMonth($month, $year)
{
  $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  return $days;
}

  /**
   *
   *  Gets the first weekday of that month and year
   *
   *  @param  int   The day of the week (0 = sunday, 1 = monday ... , 6 = saturday)
   *  @param  int   The month (if false use the current month)
   *  @param  int   The year (if false use the current year)
   *
   *  @return int   The timestamp of the first day of that month
   *
   **/ 
  function get_first_day($day_number=1, $month=false, $year=false)
  {
    $month  = ($month === false) ? strftime("%m"): $month;
    $year   = ($year === false) ? strftime("%Y"): $year;
   
    $first_day = 1 + ((7+$day_number - strftime("%w", mktime(0,0,0,$month, 1, $year)))%7);
 
    return mktime(0,0,0,$month, $first_day, $year);
  }

##############################################################################################################
?>
