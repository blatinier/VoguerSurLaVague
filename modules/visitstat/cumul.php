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

		//-----------------------------------------------------------------------------
		//Mise en en forme ($AllBots) pour preg_match des bot connus (dans la table + bot en générale (bot, spider , etc)
		$result1=mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER." "); 
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
		//-----------------------------------------------------------------------------
		//------------------------ Mise en tableau de la table bad user agent ---------
		unset($Matrice_bad_user_agent);
		$Bad_User_Agent=mysql_query("select * from ".TABLE_BAD_USER_AGENT.""); //
		while($bad_agents=mysql_fetch_array($Bad_User_Agent)){ // Mise en tableau des bad agents
			$Matrice_bad_user_agent[] = array($bad_agents['user_agent'], $bad_agents['info'],$bad_agents['type']);
		}
		//-----------------------------------------------------------------------------

?>

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_ARCHIVE; ?></TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
                <TH><? echo $MSG_DATES; ?></TH>
                <TH><? echo '&nbsp;'; ?></TH>
                <TH><? echo $MSG_VISITE; ?></TH>
                <TH><? echo $MSG_PAGESVISITES; ?></TH>
                <TH><? echo $MSG_ARCHIVAGE; ?></TH>
                </TR>
		<?
		$booleen=1; // sert a finir la boucle 
		$mois_encours = date('m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$mois = $mois_encours;
		$annee = date('Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		
		
		$result_min_code = mysql_query("select min(code) as cd from ".TABLE_VISITEUR."");
		$min_code = mysql_fetch_array($result_min_code);

$Min_annee = substr($min_code['cd'], 0, 4);
$Min_mois = substr($min_code['cd'], 4, 2);
$Min_date = $Min_annee."/".$Min_mois;

		while($booleen==1){
			$date="%/".$mois_encours."/".$annee;
		//----------------------------------------------
		//Calcul Nb visiteurs et pages hors bot Robots
		$result = mysql_query("select * from ".TABLE_VISITEUR." where date like '$date'");
		$NbpageVues_HorsBots=0;
		$NbVisites_HorsBots=0;
		$NbpageVues_robots=0;
		$NbVisites_robots=0;

		while($row=mysql_fetch_array($result)){

			if(!preg_match($AllBots, $row[agent])) {
			//---------------------------------------
				//Si type = S (SPAM) est affiché en rouge dans Liste Bad user agent et n'est pas compté comme visiteur ni comme robot
				//type = I n'est pas implénenté actuellement 21-08-2009
				$User_Agent=$row[agent];
				$trash=false;
				for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
					if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
						$trash=true;
						//echo $User_Agent.'<br>';
					}
				}
			//---------------------------------------
			}

			if(!preg_match($AllBots, $row[agent]) && $trash==false) {		
				$NbpageVues_HorsBots = $NbpageVues_HorsBots+$row[nb_visite];
				$NbVisites_HorsBots = $NbVisites_HorsBots+1;
			}			

			if(preg_match($AllBots, $row[agent]) && $trash==false) {		
				$NbpageVues_robots = $NbpageVues_robots+$row[nb_visite];
				$NbVisites_robots = $NbVisites_robots+1;
			}			

		}
		//echo 'Total Page vues hors robots = '.$NbpageVues_HorsBots.'<br>'; // Nb de page vue
		//echo 'Total Visites hors robots = '.$NbVisites_HorsBots.'<br>';
		//----------------------------------------------

			$result=mysql_query("select count(*) from ".TABLE_VISITEUR." where date like '$date'");
			$row=mysql_fetch_row($result);
			$nb_visiteur=$row[0];
			
			$result=mysql_query("select sum(p.nb_visite) as somme from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where date like '$date' and p.code=v.code");
			$row=mysql_fetch_row($result);
			$nb_visite=$row[0];

			//Pour mois en cours
?>
			<td align="center">
				<form name="forme" method="post" action="<? echo $PHP_SELF; ?>">
					<input type="hidden" name="type" value="cumulpage">
					<input type="hidden" name="mois" value="<? echo $mois_encours."/".$annee; ?>">
					<input  class="submitDate" type="submit" name="datemois" size="1" value="<? echo $mois_encours.'/'.$annee; ?>" alt="<? echo $mois_encours.'/'.$annee; ?>" title="<? echo $mois_encours.'/'.$annee; ?>">
				</form>
			</td>
<?
			echo '
			<td width="25%">'.$MSG_VISITEURS_AND_ROBOTS.'<br>'.$MSG_VISITEURS.'<br>'.$MSG_BOT.'</td>
			<td valign="top" align="center">'.$nb_visiteur.'<br>'.$NbVisites_HorsBots.'<br>'.$NbVisites_robots.'</td>
			<td valign="top" align="center">'.$nb_visite.'<br>'.$NbpageVues_HorsBots.'<br>'.$NbpageVues_robots.'</td>
			<td align="center">';
			
			$Annee_actuelle = date('Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			if($mois_encours."/".$annee<>$mois."/".$Annee_actuelle){
				echo 'Pas en cache';
			} else{
				echo $MSG_MOIS_ENCOURS;
			}
			echo '</td></tr>';
			
			//---------------------------------------
			//recherche du mois precedent non en cache
			//---------------------------------------

			if($mois_encours == "01")	{ 
				$mois_encours = "12";
				$annee--;
				$date = "%/".$mois_encours."/".$annee;
				$result = mysql_query("select count(*) from ".TABLE_VISITEUR." where date like '$date'");
				$row = mysql_fetch_row($result);
				if($row[0]=='0'){ $mois_en_cache = -1; } else { $mois_en_cache = 1; }
			} else {
				$mois_encours--;
				if($mois_encours<10) {
					$mois_encours="0".$mois_encours;
				}
				$date = "%/".$mois_encours."/".$annee;
				$result = mysql_query("select count(*) from ".TABLE_VISITEUR." where date like '$date'");
				$row = mysql_fetch_row($result);
				if($row[0]=='0'){ $mois_en_cache = -1; } else { $mois_en_cache = 1; }
			}
			if ($Min_date > $annee."/".$mois_encours) { $booleen=0; }

			//$booleen  //pour arreter la grande boucle
			//$mois_en_cache  //pour arreter la sous boucle
			while ($mois_en_cache < '0' && $booleen <> 0) { 
			
					if ($Min_date > $annee."/".$mois_encours) { $booleen=0; }
			
					if($mois_encours == "01")	{ 
						$mois_encours = "12";
						$annee--;
						$date = "%/".$mois_encours."/".$annee;
						$result = mysql_query("select count(*) from ".TABLE_VISITEUR." where date like '$date'");
						$row = mysql_fetch_row($result);
						if($row[0]=='0'){ $mois_en_cache = -1; } else { $mois_en_cache = 1; }
					} else {
						$mois_encours--;
						if($mois_encours<10) {
							$mois_encours="0".$mois_encours;
						}
						$date = "%/".$mois_encours."/".$annee;
						$result = mysql_query("select count(*) from ".TABLE_VISITEUR." where date like '$date'");
						$row = mysql_fetch_row($result);
						if($row[0]=='0'){ $mois_en_cache = -1; } else { $mois_en_cache = 1; }
					}
			}
		}

		///////////////////////////////////////////////////
		//Lecture Archives
		$result=mysql_query("select * from ".TABLE_ARCHIVE." order by annee desc, mois desc");
		while($row=mysql_fetch_array($result)){
			echo '<tr>
			<td align="center"><b>';
			if ($row[mois]<10){ $row[mois] = "0".$row[mois]; }
			
			$format_date_file_name = $row[annee].'-'.$row[mois];
			
			if (file_exists('cache/stats_'.$site.'_'.$format_date_file_name.'.php')) {
				echo '				
				<form name="forme" method="get" action="cache/stats_'.$site.'_'.$format_date_file_name.'.php" "target="_blank">
					<input class="submitDate" type="submit" name="datemois" size="1" value="'.$row[mois]."/".$row[annee].'" alt="'.$row[mois]."/".$row[annee].'" title="'.$row[mois]."/".$row[annee].'">
				</form>
				';
			} else {
				echo "$row[mois]"."/".$row[annee]."</b></td>"; // Ancienne archive -> pas de cache	
			}		
	
			echo '
			<td width="25%">'.$MSG_VISITEURS_AND_ROBOTS.'<br>'.$MSG_VISITEURS.'<br>'.$MSG_BOT.'</td>
			<td valign="top" align="center">'.$row['visiteur'].'<br>'.$row['visites_hors_bot'].'<br>'.$row['visites_robot'].'</td>
			<td valign="top" align="center">'.$row['visite'].'<br>'.$row['pages_hors_bot'].'<br>'.$row['pages_robots'].'</td>';
	
			if (file_exists('cache/stats_'.$site.'_'.$format_date_file_name.'.php')) {
				echo '<td align="center">En cache</td>';	
			} else {
				echo '<td align="center">'.$MSG_MOIS_ARCHIVE.'</td>';
			}
			
			echo '</tr>';
		}
		?>      
		</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
