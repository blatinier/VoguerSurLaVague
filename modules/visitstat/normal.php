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
VOIR: http://analytics.blogspot.com/2009/04/upcoming-change-to-googlecom-search.html (url google "source=web")
Entre autre aussi: Nouvelle variable "as_q" 		unset($Matrice_bad_user_agent);
*/

		//-----------------------------------------------------------------------------
		//Mise en en forme ($AllBots) pour preg_match des bots connus (dans la table + bot en générale (bot, spider , etc)
		$result1=mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER.""); 
		if (!$result1) { //ex: si la table n'existe pas
			echo 'Impossible d\'exécuter la requête : ' . mysql_error();
			exit;
		}

		$AllBots = '/Bot|Slurp|Scooter|Spider|crawl|';
		while($row=mysql_fetch_array($result1)){
			$Form_chaine = str_replace('/','\/',$row['bot_name']);
			$Form_chaine = str_replace('+','\+',$Form_chaine);
			$Form_chaine = str_replace('(','\(',$Form_chaine);
			$Form_chaine = str_replace(')','\)',$Form_chaine);
			$AllBots .= $Form_chaine.'|';
		}
		$AllBots = substr($AllBots,0,strlen($AllBots)-1); //del last |
		$AllBots .= '/i';
		//------------------------ Mise en tableau de la table bad user agent ---------
		unset($Matrice_bad_user_agent);
		$Bad_User_Agent=mysql_query("select * from ".TABLE_BAD_USER_AGENT.""); //
		while($bad_agents=mysql_fetch_array($Bad_User_Agent)){ // Mise en tableau des bad agents
			$Matrice_bad_user_agent[] = array($bad_agents['user_agent'], $bad_agents['info'],$bad_agents['type']);
		}
		//-----------------------------------------------------------------------------

		if($when==""){ // seulement lorsque 1er appel page
			$when = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		}
		
?>
<style type="text/css">
<!--
.Style2 {
	font-size: 12px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-weight: lighter;
}
.Style11pxbold {
	font-size: 11px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH align="center" valign="middle" class=TABLETITLE>
			<form name="form1" method="post" action="<? $PHP_SELF; ?>">
				<input type="hidden" name="when"  value="<? echo $when; ?>">
			    <input class="submit" name="refresh" type="submit" value="<? echo $MSG_REFRESH; ?>" alt="<? echo $MSG_REFRESH; ?>" title="<? echo $MSG_REFRESH; ?>">
			</form>
			&nbsp;&nbsp;<? echo $MSG_STATISTIQUE_DATE.$when; ?>
		 </TH>
        </TR>
</TBODY></TABLE>
      <!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_RESUME; ?></TH>
        </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
		  <tr>
			<TH> 
				<? echo $MSG_DESCRIPTION; ?>
			</TH>
			<TH>
				<? echo $MSG_VALUE; ?>
			</TH>
			<TH>
				<? echo $MSG_TOTAL; ?>
			</TH>

		</tr>

<?php
		//#############################################################################################
		//Nb visiteurs et pages hors bot,crawler,robots et hors bad user agent
		//Robots Nbvisites, Nb pages vues ...
		//---------------------------------------------------------------------------------------------
		$result=mysql_query("select * from ".TABLE_VISITEUR." where date like '$when'");
		$NbpageVues_HorsBots=0;
		$NbVisites_HorsBots=0;
		//---------------------------------------------------------------------------------------------
		while($row = mysql_fetch_array($result)){
			$User_Agent = $row['agent'];

			//-- Exclusion des Bad user agent reconnus (non compté comme visiteur) ----
			$trash=false;
			//NE PAS tester strlen(trim($User_Agent))<=1 car le user agent peut être vide mais IP et host non vide
			//Compter les user agent inconnus mais mais pas les spammers
			for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
				if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
					$trash=true;
				}
			}
			//Visistes et pages vues hors robots et hors bad ou user agent inconnus
			if(!preg_match($AllBots, $row[agent]) && $trash==false) {		
//Pour test
//echo "Test normal.php = ".$row['referer']. " - ".$row['host']. "<br>";
				$NbpageVues_HorsBots = $NbpageVues_HorsBots + $row['nb_visite'];
				$NbVisites_HorsBots = $NbVisites_HorsBots + 1;
			} else {
				//echo "N'est pas compté comme visiteur: ".$User_Agent.'<br>';			
				$NbpageVues_Bots = $NbpageVues_Bots+$row['nb_visite'];
				$NbVisites_Bots = $NbVisites_Bots + 1;
			}			
		}
		$visite_par_visiteurs_HorsBots = @bcdiv($NbpageVues_HorsBots,$NbVisites_HorsBots,2); 
		$visite_par_visiteurs_Bots = @bcdiv($NbpageVues_Bots,$NbVisites_Bots,2); 


		//#############################################################################################
							// Nombre visiteur total (avec robots)
		//---------------------------------------------------------------------------------------------
		$nb_visiteur=0;
		//---------------------------
		//Nb de visites (avec robots)
		$result=mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where date like '$when'");
		$row_nb_visites=mysql_fetch_row($result);
		//--------------------------
	
		//Heure premier, heure dernier et Nb de page vue ($nb_visite à supprimer)
		$result=mysql_query("select * from ".TABLE_VISITEUR." v, ".TABLE_PAGE." p where date like '$when' and v.code=p.code order by heure");
		$nb_visite=0;
		$heure_premier="";
		while($row=mysql_fetch_array($result)){
			if($heure_premier==""){
				$heure_premier=$row[heure];
			}
			$nb_visite=$nb_visite+$row[nb_visite];
			$heure_dernier=$row[heure];
		}

		//Nb visites veille (avec robots)
		$diffhier = $UTC-24;
		$date_utc_hier = date('d/m/Y',strtotime($diffhier." hours", strtotime(date("Y-m-d H:i:s"))));
		$heure_utc = date('H:i',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$result=mysql_query("select * from ".TABLE_VISITEUR." v, ".TABLE_PAGE." p where p.code=v.code and heure<'".$heure_utc."' and heure>'00:00' and date = '".$date_utc_hier."' group by ip");

		$nb_visite_hier=mysql_num_rows($result);

		echo "<tr><td> 
					<div>".$MSG_STAT_PREMIER." :</div>
				  </td>
				  <td>"; echo $heure_premier; echo "&nbsp;</td>
				  <td align=center>-</td>
				</tr>
				<tr> 
				  <td> 
					<div>".$MSG_STAT_DERNIER." :</div>
				  </td>
				  <td>"; echo $heure_dernier; echo "&nbsp;</td>
				  <td align=center>-</td>
				</tr>
				<tr> 
				  <td> 
					<div>".$MSG_STAT_VISTEURS." (".$MSG_ROBOTS_EXCLUS.") :</div>
					<div>".$MSG_ROBOTS_STAT_VISTEURS." :</div>
				  </td>
				  <td valign=top><span class=\"Style11pxbold\">".$NbVisites_HorsBots."</span><br>".$NbVisites_Bots."</td>
				  <td align=center>".$row_nb_visites[0]. " | ".$MSG_HIER." : ".$nb_visite_hier.
				  "</td>
				</tr>
				<tr> 
				  <td> 
					<div>".$MSG_STAT_PAGESVUES." (".$MSG_ROBOTS_EXCLUS.") :</div>
					<div>".$MSG_ROBOTS_STAT_PAGESVUES."  :</div>
				  </td>
				  <td valign=top>";$max_visite_HorsBots = $NbpageVues_HorsBots;
				   echo '<span class="Style11pxbold">'.$NbpageVues_HorsBots."</span><br>".$NbpageVues_Bots.
				   "</td>
				  <td align=center>"; $max_visite = $nb_visite; echo $nb_visite; 
				  echo "
				  </td>
				</tr>
				<tr> 
				  <td> 
					<div>".$MSG_STAT_POURCENTAGE." (".$MSG_ROBOTS_EXCLUS.") :</div>
					<div>".$MSG_ROBOTS_STAT_POURCENTAGE." :</div>
				  </td>
				  <td valign=top>"; 
				  if($row_nb_visites[0]!=0){
				  	echo '<span class="Style11pxbold">'.$visite_par_visiteurs_HorsBots.'</span><br>';
					echo $visite_par_visiteurs_Bots; 
				  }
				  echo "&nbsp;</td>
				  <td align=center>&nbsp;"; 
				  if($row_nb_visites[0]!=0){
					$visite_par_visiteurs=bcdiv($nb_visite,$row_nb_visites[0],2); echo $visite_par_visiteurs; 
				  }
				  echo "&nbsp;</td>";

		?>
</TABLE></TD></TR></TABLE>
      <!-- Data END --></TD></TR></TBODY></TABLE><br>
		
		<?
		//---------------------------- Fin tableau de bord --------------------------------------------
		//#############################################################################################
								// Visites par plage horaire
		//---------------------------------------------------------------------------------------------

		for($i=1;$i<=24;$i++){
			$hour="heure$i";
			$var="v_heure$i";
			$$var=0;
			$$hour=0;
		}

		//recup des données
		$result=mysql_query("select v.code, v.agent, p.nb_visite, p.heure, p.page from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where v.date like '$when' and v.code=p.code order by p.heure ASC");

//## TEST Pour comptabilise les visiteurs simultanés par heure ##
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
			//Visistes et pages vues hors robots et hors bad user agent
			//Note: les barres visiteurs sont incrémenté de 1 pour l'heure de la 1ere visite
			//		les barres "pages visitées" sont incrémentées par heure 
			//		(si une même page est visitée par un même visiteur la barre est incrémentée sur l'heure de la 1ere visite) pour eviter de remplir la table si spam
			if(!preg_match($AllBots, $row[agent]) && $trash==false) {		
				//################################ Pour comptabilise les visiteurs simultanés par heure ################################################
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

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_STAT_GRAF_TITRE." (".$MSG_ROBOTS_EXCLUS.")"; ?></TH>
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
			if($max!=0){
			  for($i=1;$i<=24;$i++){
				$hour="heure$i";
				$indice=bcdiv($$hour,$max,2); $hauteur=bcmul($indice,120.00,2);
				if ($MaxHauteur <= $hauteur) { $MaxHauteur = $hauteur; }
			  }
			} else { // pour ne pas afficher 0 si $max = 0
				$EchyMin  = '';	
		  		$max = '';
			}

		  echo "<td nowrap=nowrap valign=\"top\">".$max."</td>";
		  echo "<td rowspan=\"2\" valign=\"bottom\">
			<img src=\"images/histo-v_black.gif\" height=\"".$MaxHauteur."\" width=\"1\" alt=\"\" title=\"\">
		  </td>";
			
          for($i=1;$i<=24;$i++){
          	$hour="heure$i";
          	$v_hour="v_heure$i";
				echo "<td rowspan=\"2\" valign=\"bottom\">";
				if($max!=0) {
					$indice=bcdiv($$hour,$max,2); $hauteur=bcmul($indice,120.00,2);
				}
				echo "<img src=\"images/histo-v.gif\" height=\"".$hauteur."\" width=\"8\" alt=\"".$$hour."\" title=\"".$$hour."\"><img src=\"images/histo-vv.gif\" height=\"";

				if($max!=0){
					$indice=bcdiv($$v_hour,$max,2); $hauteur=bcmul($indice,120.00,2);  
				}
				echo $hauteur;
				echo "\" width=\"8\" alt=\"".$$v_hour."\" title=\"".$$v_hour."\">
			</td>";
 
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
		//#########################################################################################
		//------------------------- Include tableau details robots ------------------------------
		include_once('referant.php');
		//########################################################################################
		
		//##############################################################################################
		//------------------------------------ Pages visités -------------------------------------------
		//TODO voir mettre en tableau avant
		$result=mysql_query("select * from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where v.date like '$when' and v.code=p.code order by page");
		$nb_url=0;
		$nb_vis=0;

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
			//A voir est utilisé aussi dans référant.php, cumulpage.php et ailleurs ? A voir car algo obscur
			if(!preg_match($AllBots, $row[agent]) && $trash==false) {
				$page=$row[page];
				if($all_pages==$page){
					$nb_url=$nb_url+$row[nb_visite];
					$nb_vis++;	
				} else {
					$nb=$nb_url*100; 
					if($max_visite!=0){
						$pourcent=bcdiv($nb,$max_visite,2);
					}
					$page_vue[]= array($all_pages,$nb_vis, $nb_url, $pourcent);
					$all_pages=$page;
					$nb_url=$row[nb_visite];
					$nb_vis=1;
				}
			}
		} // Fin while
	
		//---------------------------------------------------------------------------------------

	//############################## Affichage du Top avec referer #######################################################
?>
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_TOP_TITRE.' ('.$MSG_ROBOTS_EXCLUS.')'; ?></TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
                <TH><? echo $MSG_TOP_ADDRESS; ?></TH>
                <TH><? echo "IP"; ?></TH>
				<TH><? echo $MSG_REFERANT; ?></TH>
                <TH><? echo $MSG_PAGESVISITES; ?></TH>
                <TH><? echo $MSG_PAGES_POURCENTAGE; ?></TH></TR>
<?
			$result=mysql_query("select * from ".TABLE_VISITEUR." where date='$when' order by nb_visite desc"); //limit 0,20
			if (!$result) { //ex: si la table n'existe pas
				echo 'Impossible d\'exécuter la requête : ' . mysql_error();
				exit;
			}
	
		while($Nb_lignes_affichees<20 && $row=mysql_fetch_array($result)){
	
			//---------------------------------------
			$User_Agent=$row[agent];
			$trash=false;
			for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
				if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
					$trash=true;
				}
			}
			//---------------------------------------
		
			if(!preg_match($AllBots, $row[agent]) && $trash==false) {
		
				$Nb_lignes_affichees = $Nb_lignes_affichees+1;
				$max=20;	 // Nombre de caractères max
				$coupe ="";
				if(strlen($row["host"])>=$max)  {
				//$coupe = strpos($row["host"],"."); 
				$coupe = 20; 
				}
	
				//col host
				if($coupe) {
					$chaine1 = substr($row["host"], 0, $coupe);
					$chaine2 = substr($row["host"], $coupe, strlen($row["host"]));
					echo "<tr><td nowrap>".$chaine1."<br>".$chaine2."</td>";
				} else {
					echo "<tr><td nowrap>".$row["host"]."</td>";		  
				}
				
				//col IP
				echo "<td>".$row["ip"]."</td>";

				//col referer
				echo "<td nowrap=nowrap>  ";
				$num = "";	//Important		
				$host = parse_url($row["referer"]);
				$query = $host["query"];
				parse_str($query);				
				$url_site = parse_url($site);

				if($host["host"]<>$url_site["host"]){
					$aff_referer = $row["referer"];
					//Attention peut être "?" ou "&" (voir visiteur.php)
					if (strstr($aff_referer,'googlesyndication=1')) { 
						$aff_referer = str_replace('?googlesyndication=1', '', $aff_referer);
						$aff_referer = str_replace('&googlesyndication=1', '', $aff_referer); //ici il ne faut pas htmlentities('&googlesyndication=1')
						$link = $aff_referer;
						if (strlen($aff_referer.strip_tags($MSG_ADWORDS_CONTENT_NETWORK))>60) { $aff_referer=substr($aff_referer,0,30).'...'; }
						echo "<a href=\"$link\" target=\"_new\">".utf8_decode(urldecode($aff_referer))."</a><font color=#666666>&nbsp;&nbsp;".$MSG_ADWORDS_CONTENT_NETWORK."</font>";
					// ------------------------- EN TEST -----------
					} elseif ( strstr($host["host"],'google') && strstr($row["referer"],'source=web')) { //Un peu vague avec seulement source
						if (strlen($aff_referer)>40) { $aff_referer=substr($aff_referer,0,40).'...'; }
						echo "<a href=\"".$row["referer"]."\" target=\"_new\">".utf8_decode(urldecode($aff_referer))."</a> <br>";
						
						if ($num<>"") { //Calcul début 1ere page google ou se trouve le mot clé
							$start  = @floor($cd/$num)*10 ; 
						} else {
							$start  = @floor($cd/10)*10 ; // Default num = 10
						}
						
						$New_link_google = 'http://'.$host["host"].'/search?q='.$q.'&start='.$start;
						$aff_New_link_google = $New_link_google;
						if (strlen($aff_New_link_google)>50) { $aff_New_link_google=substr($aff_New_link_google,0,50).'...'; } 
						echo '&nbsp;&nbsp;Google link:<br>&nbsp;&nbsp;<a href="'.$New_link_google.'" target="_new">'.utf8_decode(urldecode($aff_New_link_google))."</a>";
					//----------------------------------------------
					} else {
						if (strlen($aff_referer)>50) { $aff_referer=substr($aff_referer,0,50).'...'; }
						echo "<a href=\"$row[referer]\" target=\"_new\">".utf8_decode(urldecode($aff_referer))."</a>";
					}
				}
					
				echo "</td>
				<td align=center>".$row["nb_visite"]."</td>
				<td align=center>"; 
				$nb = $row["nb_visite"]*100; 
				if($max_visite!=0){
					$pourcent=bcdiv($nb,$max_visite,2); 
				}
				echo $pourcent."%&nbsp;</td>
				</tr>
				";
			}
		}
		//------------------------------------------------------------------------------------------------      
?>    
<!-- no footer --></TBODY></TABLE>
<!--
			<TR>
			  <TH colSpan=2>
				<form name="form1" method="post" action="<?PHP_SELF;?>">
					<input name="type" type="hidden" value="referant">
					<input name="when" type="hidden" value="<? //echo $when; ?>">
					<input name="display_best_referer" type="hidden" value="<? //echo true; ?>">
					<input class="submit" name="detail_ref" type="submit" value="<? //echo $MSG_DETAIL_REF; ?>" alt="<? //echo $MSG_DETAIL_REF; ?>" >
				</form>
				</TH>
			</TR>
-->			
</TBODY></TABLE><!-- Rows END --></TD></TR><!-- Data END --></TD></TR></TBODY></TABLE><BR>
<?
		//##############################################################################################


		//################################# Affichage page visités hors robots ############################################
?>

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_PAGESVISITES.' ('.$MSG_ROBOTS_EXCLUS.')'; ?></TH>
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
				$nb=$nb_url*100; 
				if($max_visite!=0){
					$pourcent=bcdiv($nb,$max_visite,2);
				}
				$page_vue[]= array($all_pages,$nb_vis, $nb_url, $pourcent);
			
				@usort($page_vue, "CompareValeurs");
				$cpt=0;
				
				//print_r($page_vue);
				while ($page_vue[$cpt][0]){ 
					echo "<tr><td>".utf8_decode($page_vue[$cpt][0])."</td><td align=center>".$page_vue[$cpt][1]."</td><td align=center>".$page_vue[$cpt][2]."</td><td align=center>".$page_vue[$cpt][3]."%</td></tr>";
					$cpt++;
				}
				unset($page_vue);
?>      
</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
<?
		//############################# Affichage Operating system, navigateurs ################
		$max=0;
		$result=mysql_query("select * from ".TABLE_VISITEUR." where date='$when'");
		$nbr_result=mysql_num_rows($result);
		
		
		$AfficheOS=true;
		$AfficheNav=true;
		$AfficheRobots=false;
		include('tab_os_nav_robots.php');

	//###################### Affichage Origine géographique des viteurs (hors robots) ###############################
?>
<TABLE width="100%" CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_DOMAIN_TITRE.' ('.$MSG_ROBOTS_EXCLUS.')'; ?></TH>
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
		$Country = @array_unique($Country);
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
			if(!preg_match($AllBots, $row[agent]) && $trash==false) {
				for($i=0;$i<count($Country);$i++){
					if($row[domaine]==$Country[$i]) {
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
