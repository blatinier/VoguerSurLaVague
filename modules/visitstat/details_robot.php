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

if($when==""){
	$when = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
}

		//-----------------------------------------------------------------------------
		//Mise en en forme ($AllBots) pour preg_match des bot connus (dans la table + bot en général (bot, spider , etc)
		$result1=mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER.""); 
		$AllBots = '/Bot|Slurp|Scooter|Spider|crawl|';
		while($row=mysql_fetch_array($result1)){
			$Form_chaine = str_replace('/','\/',$row['bot_name']);
			$Form_chaine = str_replace('+','\+',$Form_chaine);
			$Form_chaine = str_replace('(','\(',$Form_chaine);
			$Form_chaine = str_replace(')','\)',$Form_chaine);
			$AllBots .= $Form_chaine.'|';
		}
		$AllBots = substr($AllBots,0,strlen($AllBots)-1); //delete last "|"
		$AllBots .= '/i';
		//echo $AllBots;
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
          <TH align="center" valign="middle" class=TABLETITLE>
			<form name="form1" method="post" action="<? $PHP_SELF; ?>">
				<input type="hidden" name="type"  value="DetailsRobot">
				<input type="hidden" name="when"  value="<? echo $when; ?>">
			    <input class="submit" name="refresh" type="submit" value="<? echo $MSG_REFRESH; ?>" alt="<? echo $MSG_REFRESH; ?>" title="<? echo $MSG_REFRESH; ?>">
			</form>
			&nbsp;&nbsp;<? echo $MSG_STATISTIQUE_DATE.$when; ?>
		 </TH>
		</TR>
</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
<?

		//#########################################################################################
		//------------------------- Include tableau details robots ------------------------------
		$result=mysql_query("select * from ".TABLE_VISITEUR." where date='$when'");
		$nbr_result=mysql_num_rows($result);

		$AfficheOS=false;
		$AfficheNav=false;
		$AfficheRobots=true;
		include('tab_os_nav_robots.php');

		//########################################################################################
								//ROBOTS Visites par plage horaire
		//-----------------------------------------------------------------------------------------

		for($i=1;$i<=24;$i++){
			$hour="heure$i";
			$var="v_heure$i";
			$$var=0;
			$$hour=0;
		}

		//recup des données
		$result=mysql_query("select v.code, v.agent, p.nb_visite, p.heure, p.page from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where v.date like '$when' and v.code=p.code order by p.heure ASC");

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
#############################################################

		$code_unique = array();
		while($row=mysql_fetch_array($result)){
			//---------------------------------------
			//-- Exclusion des Bad user agent reconnus (non compté comme visiteur) ----
			$User_Agent=$row[agent];
			$trash=false;
			for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
				if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
					$trash=true;
				}
			}
			//---------------------------------------
			//Visistes et pages vues par les robots, hors bad user agent
			//Note: les barres visiteurs sont incrémenté de 1 pour l'heure de la 1ere visite
			//		les barres "pages visitées" sont incrémentées par heure 
			//		(si une même page est visitée par un même visiteur la barre est incrémentée sur l'heure de la 1ere visite) pour eviter de remplir la table si spam

			if(preg_match($AllBots, $row[agent]) && $trash==false) {		
				################################ Pour comptabilise les visiteurs simultanés par heure ################################################
				$heure=$row[heure];
				if(($heure>=0)&($heure<1)){	$heure1=$heure1+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre1)) {$v_heure1++; $code_unique_hre1[] = $row[code]; }}
				if(($heure>=1)&($heure<2)){	$heure2=$heure2+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre2)) { $v_heure2++; $code_unique_hre2[] = $row[code]; }}
				if(($heure>=2)&($heure<3)){	$heure3=$heure3+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre3)) { $v_heure3++; $code_unique_hre3[] = $row[code]; }}
				if(($heure>=3)&($heure<4)){	$heure4=$heure4+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre4)) { $v_heure4++; $code_unique_hre4[] = $row[code]; }}
				if(($heure>=4)&($heure<5)){	$heure5=$heure5+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre5)) { $v_heure5++; $code_unique_hre5[] = $row[code]; }}
				if(($heure>=5)&($heure<6)){	$heure6=$heure6+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre6)) { $v_heure6++; $code_unique_hre6[] = $row[code]; }}
				if(($heure>=6)&($heure<7)){	$heure7=$heure7+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre7)) { $v_heure7++; $code_unique_hre7[] = $row[code]; }}
				if(($heure>=7)&($heure<8)){	$heure8=$heure8+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre8)) { $v_heure8++; $code_unique_hre8[] = $row[code]; }}
				if(($heure>=8)&($heure<9)){	$heure9=$heure9+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre9)) { $v_heure9++; $code_unique_hre9[] = $row[code]; }}
				if(($heure>=9)&($heure<10)){ $heure10=$heure10+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre10)) { $v_heure10++; $code_unique_hre10[] = $row[code]; }}
				if(($heure>=10)&($heure<11)){ $heure11=$heure11+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre11)) { $v_heure11++; $code_unique_hre11[] = $row[code]; }}
				if(($heure>=11)&($heure<12)){ $heure12=$heure12+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre12)) { $v_heure12++; $code_unique_hre12[] = $row[code]; }}
				if(($heure>=12)&($heure<13)){ $heure13=$heure13+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre13)) { $v_heure13++; $code_unique_hre13[] = $row[code]; }}
				if(($heure>=13)&($heure<14)){ $heure14=$heure14+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre14)) { $v_heure14++; $code_unique_hre14[] = $row[code]; }}
				if(($heure>=14)&($heure<15)){ $heure15=$heure15+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre15)) { $v_heure15++; $code_unique_hre15[] = $row[code]; }}
				if(($heure>=15)&($heure<16)){ $heure16=$heure16+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre16)) { $v_heure16++; $code_unique_hre16[] = $row[code]; }}
				if(($heure>=16)&($heure<17)){ $heure17=$heure17+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre17)) { $v_heure17++; $code_unique_hre17[] = $row[code]; }}
				if(($heure>=17)&($heure<18)){ $heure18=$heure18+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre18)) { $v_heure18++; $code_unique_hre18[] = $row[code]; }}
				if(($heure>=18)&($heure<19)){ $heure19=$heure19+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre19)) { $v_heure19++; $code_unique_hre19[] = $row[code]; }}
				if(($heure>=19)&($heure<20)){ $heure20=$heure20+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre20)) { $v_heure20++; $code_unique_hre20[] = $row[code]; }}
				if(($heure>=20)&($heure<21)){ $heure21=$heure21+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre21)) { $v_heure21++; $code_unique_hre21[] = $row[code]; }}
				if(($heure>=21)&($heure<22)){ $heure22=$heure22+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre22)) { $v_heure22++; $code_unique_hre22[] = $row[code]; }}
				if(($heure>=22)&($heure<23)){ $heure23=$heure23+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre23)) { $v_heure23++; $code_unique_hre23[] = $row[code]; }}
				if(($heure>=23)&($heure<24)){ $heure24=$heure24+$row[nb_visite]; if (!in_array($row[code], $code_unique_hre24)) { $v_heure24++; $code_unique_hre24[] = $row[code]; }}

				###########################################################################################################################################
			}
		}// End while
		unset($code_unique);

		$max=0;
		$i=1;
		while($i<=24){
			$heure="heure$i";
			if($$heure>$max){
				$max=$$heure;
			}
			$i++;
		}
?>
<br>
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_ROBOTS_STAT_GRAF_TITRE; ?></TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=0 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
          <td rowspan="2"><B><SPAN class=PAGESVUES><? echo $MSG_PAGESVISITES; ?></SPAN><BR>& 
		  	<SPAN class=VISITES><? echo $MSG_VISITE; ?></SPAN></B>
		  </TD>
          <?

			//Pour affichage echelle
			$EchyMin = '0';
			if($max!=0) {
			  for($i=1;$i<=24;$i++){
				$hour="heure$i";
				$indice=@bcdiv($$hour,$max,2); $hauteur=bcmul($indice,120.00,2);
				if ($MaxHauteur <= $hauteur) { $MaxHauteur = $hauteur; }
			  }
			} else {// pour ne pas afficher les 0 si $max = 0
				$EchyMin  = '';	
		  		$max = ''; 
			}

		  echo "<td nowrap=nowrap valign=\"top\">".$max."</td>";
		  echo "<td rowspan=\"2\" valign=\"bottom\">
			<img src=\"images/histo-v.gif\" height=\"".$MaxHauteur."\" width=\"1\" alt=\"\" title=\"\">
		  </td>";

          for($i=1;$i<=24;$i++){
          	$hour="heure$i";
          	$v_hour="v_heure$i";
          	echo "<td rowspan=\"2\" valign=\"bottom\">";
          	if($max!=0) {
         		$indice=@bcdiv($$hour,$max,2); $hauteur=bcmul($indice,120.00,2);
        	}
          	echo "<img src=\"images/histo-v.gif\" height=\"".$hauteur."\" width=\"8\" alt=\"".$$hour."\" title=\"".$$hour."\"><img src=\"images/histo-vv.gif\" height=\"";
          	if($max!=0){
          		$indice=@bcdiv($$v_hour,$max,2); $hauteur=bcmul($indice,120.00,2);  
          	}
          	echo $hauteur;
          	echo "\" width=\"8\" alt=\"".$$v_hour."\" title=\"".$$v_hour."\"></td>";
          }

		?>
  		  </TR>
		  <tr>
			 <td align="right" valign="bottom"><?php echo $EchyMin; ?></td>
		  </tr>

              <TR>
                <TD><B><? echo $MSG_GRAF_HEURE; ?></B></TD>
         <?
 	     echo "<td align=center>&nbsp;</td><td align=center>&nbsp;</td>"; // Pour echelle
         for($i=0;$i<24;$i++){
	         if ($horloge==12 && $i>12){ 
	         	$num=$i-12; 
	         } else {
	         	$num=$i;
	         }
	         echo "<td align=center>".$num."</td>";
         }
         if ($horloge==12){
         	echo "<tr><td></td><td align=center colspan=12>AM</td><td align=center colspan=12>PM</td></tr>";
         }
         ?>
</TR></TBODY></TABLE><!-- Rows END --></TD></TR><!-- footer -->
        <TR>
		  </TR></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>

<?
//##############################################################################################
	//--------------------------- Pages vues par les robots --------------------------

	$result=mysql_query("select * from ".TABLE_VISITEUR." v, ".TABLE_PAGE." p where v.date like '$when' and v.code=p.code order by page");
	$row=mysql_fetch_array($result);

	$url_robots=$row[page];
	$nb_url_robots=0;
	$nb_vis_robots=0;
	
	while($row=mysql_fetch_array($result)){
			//---------------------------------------
		$User_Agent=$row[agent];
		$trash=false;
		for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
			if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
				$trash=true;
			}
		}
			//---------------------------------------

		if(preg_match($AllBots, $row[agent]) && $trash==false) {
	
			$page_robot=$row[page];
			if($url_robots==$page_robot){
				$nb_url_robots=$nb_url_robots+$row[nb_visite];
				$nb_vis_robots++;	
			} else {
				$nb_robot=$nb_url_robots*100; 
				if($max_visite!=0){
					$pourcent_robot=@bcdiv($nb_robot,$max_visite,2);
				}
				$page_vue_robots[]= array($url_robots,$nb_vis_robots, $nb_url_robots, $pourcent_robot);
				$url_robots=$page_robot;
				$nb_url_robots=$row[nb_visite];
				$nb_vis_robots=1;
			}
		}

	} // Fin while
	//---------------------------------------------------------------------------------------
	//------------ Affichage page visités par les robots ------------------------------------
?>
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->

      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_ROBOTS_PAGES_VISITES; ?></TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
                <TH><? echo $MSG_PAGE; ?></TH>
                <TH><? echo $MSG_VISITE; ?></TH>
                <TH><? echo $MSG_PAGESVISITES; ?></TH>
                <TH><? echo $MSG_PAGES_POURCENTAGE; ?></TH></TR>
<?

	//----------------------------------------
	$nb_robot=$nb_url_robots*100; 
    if($max_visite!=0){
    	$pourcent_robot=@bcdiv($nb_robot,$max_visite,2);
    }
	$page_vue_robots[]= array($url_robots,$nb_vis_robots, $nb_url_robots, $pourcent_robot);

	@usort($page_vue_robots, "CompareValeurs");
	$cpt=0;

	while ($page_vue_robots[$cpt][0]<>""){
		echo "<tr><td>".utf8_decode($page_vue_robots[$cpt][0])."</td><td align=center>".$page_vue_robots[$cpt][1]."</td><td align=center>".$page_vue_robots[$cpt][2]."</td><td align=center>".$page_vue_robots[$cpt][3]."%</td></tr>";
		$cpt++;
	}
?>      
 </TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
<?
	//------------ Affichage Origine géographique des robots ----------------------
?>
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_ROBOTS_ORG_GEO; ?></TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
                <TH><? echo $MSG_DOMAIN; ?></TH>
                <TH><? echo $MSG_NB_VISITEURS; ?></TH>
                <TH><? echo $MSG_PAGESVISITES; ?></TH>
			  </TR>
      <?

		$result=mysql_query("select agent, nb_visite ,domaine from ".TABLE_VISITEUR." where date='$when'");
		while($row=mysql_fetch_array($result)){
			$Country[] .= $row['domaine'];
		}
		$Country=@array_unique($Country);
		@array_multisort ($Country, SORT_ASC); 

		//----------------------------------
		//Important mise à 0 des variables
		unset($Tab_country_pages_visiteurs);
		for($i=0;$i<count($Country);$i++){ //Comment faire autrement
			$Nb_visites = 'Nb_'.$Country[$i];
			$$Nb_visites = 0;
	
			$Nb_pages_visites = $Country[$i];
			$$Nb_pages_visites = 0;
		}
		//-----------------------------------

		$result=mysql_query("select * from ".TABLE_VISITEUR." where date='$when'");
		while($row=mysql_fetch_array($result)){
			//---------------------------------------
			$User_Agent=$row[agent];
			$trash=false;
			for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
				if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
					$trash=true;
				}
			}
				//---------------------------------------
			if(preg_match($AllBots, $row[agent]) && $trash==false) {
				for($i=0;$i<count($Country);$i++){
					if($row[domaine]==$Country[$i]) {
						//echo $row[domaine].'<br>';
						$Nb_visites = 'Nb_'.$Country[$i];
						$$Nb_visites = $$Nb_visites + 1;
	
						$Nb_pages_visites = $Country[$i];
						$$Nb_pages_visites = $$Nb_pages_visites + $row['nb_visite'];
	
						$Tab_country_pages_visiteurs[$i] = array($Country[$i],$$Nb_pages_visites,$$Nb_visites);
					}
				}
			}
		}
		//--------- Affichage des résultats --------------------------
		@usort($Tab_country_pages_visiteurs,"CompareValeurs");
		//array_multisort($Tab_country_pages_visiteurs, SORT_ASC);
		$indice = @bcdiv(1,($Tab_country_pages_visiteurs[0][1]/300),2); //proportion en rapport au plus grand nb de pages visités

		for($i=0;$i<count($Tab_country_pages_visiteurs);$i++){
			if ($Tab_country_pages_visiteurs[$i][0]=='') { $Tab_country_pages_visiteurs[$i][0] = $MSG_ORIGIN_UNKNOWN;}
			echo "<tr>
			<td> 
			<b>".$Tab_country_pages_visiteurs[$i][0]."</b>
			</td>
			<td align=\"left\">
			<img src=\"images/histo-h.gif\" width=\""; 
			$hauteur=bcmul($Tab_country_pages_visiteurs[$i][2],$indice,2);  
			echo $hauteur; 
			echo"\" height=\"8\">".$Tab_country_pages_visiteurs[$i][2].
			"</td>
			<td align=\"left\">
			<img src=\"images/histo-h.gif\" width=\""; 
			$hauteur=bcmul($Tab_country_pages_visiteurs[$i][1],$indice,2);  
			echo $hauteur; 
			echo"\" height=\"8\">".$Tab_country_pages_visiteurs[$i][1].
			"</td>";	
		}

?>    
</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><br>
