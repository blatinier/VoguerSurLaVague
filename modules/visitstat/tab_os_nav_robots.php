<?
/*
 -------------------------------------------------------------------------
 AllMyStats V1.32 - Statistiques de fréquentation visiteurs et robots
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
 $when sert pour by day - $mois pour by month

*/
include_once('application_top.php');

//-- initialisatio des variables des naviguateurs
$MSIE7=0;
$MSIE6=0;
$MSIE55=0;
$MSIE5=0;
$Lynx=0;
$Opera=0;
$WebTV=0;
$Konqueror=0;
$Netscape=0;
$Bot=0;
$Other=0;
$total=0;

//Pour moteurs recherche
$bot=0;
$Googlebot=0;
$YahooSlurp=0;
$Total_page_visites_bot=0;

//-- initialisation des variables des OS
$WindowsNT=0;
$WindowsXP=0;
$Windows2000=0;
$Windows98=0;
$Windows95=0;
$Mac=0;
$Linux=0;
$FreeBSD=0;
$SunOS=0;
$IRIX=0;
$BeOS=0;
$OS2=0;
$AIX=0;
$AOL=0;
$OtherOS=0;
$totalOS=0;

/*
		// Est déjà effectué dans normal.php
		//------------------------ Mise en tableau de la table bad user agent ----------------
			$Bad_User_Agent=mysql_query("select * from bad_user_agent"); //

			while($bad_agents=mysql_fetch_array($Bad_User_Agent)){ // Mise en tableau des bad agents
				$Matrice_bad_user_agent[] = array($bad_agents['user_agent'], $bad_agents['info'],$bad_agents['type']);
			}
		//-----------------------------------------------------------------------------
*/

if (!$_SESSION['Autres_robots'] || $affiche_only_other_bots<>true) { //Pour accelerer lors de l'ajout des robots
			//$_SESSION['Autres_robots'] = "";
			unset($_SESSION['Autres_robots']);
			
			//-----------------------------------------------------------
			//Mise en tableau et en forme ($AllBots) pour preg_match des bot connus (dans la table + bot en générale (bot, spider , etc)
			//Attention est répété si est en include de normal.php, details_robot.php, cumulpage.php
			//Mais pas include de add_crawler. De plus il faut mettre ici des \ pour $Tab_crawlers[]
			$result1=mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER.""); 
			$AllBots = '/Bot|Slurp|Scooter|Spider|crawl|';

			while($row=mysql_fetch_array($result1)){
				$Form_chaine = str_replace('/','\/',$row['bot_name']);
				$Form_chaine = str_replace('+','\+',$Form_chaine);
				$Form_chaine = str_replace('(','\(',$Form_chaine);
				$Form_chaine = str_replace(')','\)',$Form_chaine);
				$AllBots .= $Form_chaine.'|';
				
				$Tab_crawlers[] = array($Form_chaine,$row['org_name'],$row['crawler_url'],$row['crawler_info']);
			}
			$AllBots = substr($AllBots,0,strlen($AllBots)-1); //supp last |
			$AllBots .= '/i';
			//-----------------------------------------------------------
			//Important pour le test suivant car par exemple Googlebot-Image doit se trouvé avant Googlebot
			array_multisort ($Tab_crawlers, SORT_DESC); 
// ------------------------------------------------------------------------- //
//#################################################################################################
//$result = mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois'");
//$nbr_result = mysql_num_rows($result);

for($i=0;$i<$nbr_result;$i++) {

	$us_agt = mysql_result($result,$i,"agent");
	$Nbvisites = mysql_result($result,$i,"nb_visite");

		//------- Mise en tableau des Bad user agent reconnus (non compté comme visiteur) -----
			$trash=false;
			if (strlen(trim($us_agt))<=1){ $trash=true; }
			for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){

				//On n'exclus et on ne compte dans cette liste que les user agent déterminés comme Spammer
				if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $us_agt && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
					$bad_and_inconnu = "Nb_".$nb_bad_user_agent;
					$$bad_and_inconnu =$$bad_and_inconnu+$Nbvisites;
					$Bad_Nb_distinct_Ip = "NbIp_".$nb_bad_user_agent;
					$$Bad_Nb_distinct_Ip = $$Bad_Nb_distinct_Ip + 1;

					$bad_user_agent[$nb_bad_user_agent] = array($Matrice_bad_user_agent[$nb_bad_user_agent][0], $$bad_and_inconnu, $$Bad_Nb_distinct_Ip,$Matrice_bad_user_agent[$nb_bad_user_agent][1],$Matrice_bad_user_agent[$nb_bad_user_agent][2]);
					$trash=true;
				}
			}
		//--------------------------------------------------------------------------------------

	if ($trash==false) {

		for($nb_crawlers=0;$nb_crawlers<count($Tab_crawlers);$nb_crawlers++){
	
			$FindCrawler = false;
			if($Tab_crawlers[$nb_crawlers] && eregi($Tab_crawlers[$nb_crawlers][0], $us_agt)) {
	
				$robots = str_replace(' ','',$Tab_crawlers[$nb_crawlers][0]);
				$robots = str_replace('-','',$robots);
				$robots = str_replace('!','',$robots);
				$$robots=$$robots+$Nbvisites;
			
				$Nb_distinct_Ip = "Nb_".$robots;
				$$Nb_distinct_Ip = $$Nb_distinct_Ip + 1;
							
				$Total_page_visites_bot = $Total_page_visites_bot + $Nbvisites;
				//le calcul de $class_robots[$nb_crawlers][3] (%) est fait juste avant l'affichage car total_bot est en cours de calcul.
				if ($Tab_crawlers[$nb_crawlers][2]) {
					$Lien_url = '<a href="'.$Tab_crawlers[$nb_crawlers][2].'" target="_blank">'.$Tab_crawlers[$nb_crawlers][1].'</a>';
				} else {
					$Lien_url = $Tab_crawlers[$nb_crawlers][1];
				}
				$crawler_info = '<br>'.$Tab_crawlers[$nb_crawlers][3];
				$class_robots[$nb_crawlers]=array($Lien_url.$crawler_info,$$robots,$$Nb_distinct_Ip,'',$Tab_crawlers[$nb_crawlers][0]);	

				$FindCrawler = true; break;
			}
		}

		if ($FindCrawler == false ) {
			// Autres robots
			if(preg_match($AllBots, $us_agt)) {

				$Autres_robots[] = $us_agt;
		
				$robots = $MSG_ROBOTS_AUTRES;
				$$robots=$$robots+$Nbvisites;
		
				$Nb_distinct_IpA = "Nb_".$robots;
				$$Nb_distinct_Ip = $$Nb_distinct_Ip+1;
		
				$Total_page_visites_bot = $Total_page_visites_bot + $Nbvisites;
				$class_robots[$nb_crawlers]=array($MSG_ROBOTS_AUTRES,$$robots,$$Nb_distinct_Ip,'','-');	
			}
		//--------------------------------------------------------------------------------------------------------
			if(!preg_match($AllBots, $us_agt)) {
		
				//TODO Netscape 7.0 et + (pas fait car la table user agent était varchar(100) et il n'y avait pas de netscape
				//if (eregi("Netscape", $us_agt)) {
					//$tmp = explode('Netscape', $us_agt);
					//echo '<div align="left">test: '.$tmp .'</div><br>';
				//} et voir si après on peut extraire la version

				/* Get the Browser data */
				if((ereg("Nav", $us_agt)) || (ereg("Gold", $us_agt)) || (ereg("X11", $us_agt)) || (ereg("Mozilla", $us_agt)) || (ereg("Netscape", $us_agt)) AND (!ereg("MSIE", $us_agt))) {
					if (eregi("Netscape", $us_agt)) {
						if(ereg("Netscape6/6.0", $us_agt)) { $browser = "Netscape60"; $total=$total+1; $$browser=$$browser+1;
						} elseif (ereg("Netscape6/6.1", $us_agt)) { $browser = "Netscape61"; $total=$total+1; $$browser=$$browser+1;
						} elseif (ereg("Netscape6/6.2", $us_agt)) { $browser = "Netscape62"; $total=$total+1; $$browser=$$browser+1;
						} else { $browser = "Netscape" ;  $total=$total+1; 	$$browser=$$browser+1;}
					}elseif (eregi("Firefox", $us_agt)) {
						if(ereg("Firefox/1.0", $us_agt)) { $browser = "MozillaFirefox10"; $total=$total+1; $$browser=$$browser+1;
						} elseif (ereg("Firefox/1.4", $us_agt)) { $browser = "MozillaFirefox14"; $total=$total+1; $$browser=$$browser+1;
						} elseif (ereg("Firefox/1.5", $us_agt)) { $browser = "MozillaFirefox15"; $total=$total+1; $$browser=$$browser+1;
						} elseif (ereg("Firefox/2.0", $us_agt)) { $browser = "MozillaFirefox20"; $total=$total+1; $$browser=$$browser+1;
						} elseif (ereg("Firefox/3.0", $us_agt)) { $browser = "MozillaFirefox30"; $total=$total+1; $$browser=$$browser+1;
						} else { $browser = "Firefox" ;  $total=$total+1; 	$$browser=$$browser+1;} 
					} elseif (ereg("Safari", $us_agt)) { 
						$browser = "Safari"; $total=$total+1; $$browser=$$browser+1;
					} else { $browser = "Other";$total=$total+1; $$browser=$$browser+1;}

				} elseif (ereg("MSIE 7.0", $us_agt) && !ereg("AOL", $us_agt)) { $browser = "MSIE7";  $total=$total+1; $$browser=$$browser+1;
				} elseif (ereg("MSIE 6.0", $us_agt) && !ereg("AOL", $us_agt)) { $browser = "MSIE6";  $total=$total+1; $$browser=$$browser+1;
				} elseif (ereg("MSIE 5.5", $us_agt) && !ereg("AOL", $us_agt)) { $browser = "MSIE55"; $total=$total+1; $$browser=$$browser+1;
				} elseif (ereg("MSIE 5.0", $us_agt) && !ereg("AOL", $us_agt)) { $browser = "MSIE5"; $total=$total+1; $$browser=$$browser+1;
				} elseif (ereg("AOL", $us_agt)) { $browser = "AOL";  $total=$total+1;$$browser=$$browser+1;
				} elseif (ereg("Lynx", $us_agt)) { $browser = "Lynx"; $total=$total+1; $$browser=$$browser+1;
				} elseif (ereg("Opera", $us_agt)) { $browser = "Opera"; $total=$total+1; $$browser=$$browser+1;
				} elseif (ereg("WebTV", $us_agt)) { $browser = "WebTV"; $total=$total+1; $$browser=$$browser+1;
				} elseif (ereg("Konqueror", $us_agt)) { $browser = "Konqueror"; $total=$total+1; $$browser=$$browser+1;
	/*	
				} else {
					$browser = "Other";
					$total=$total+1; 
					$$browser=$$browser+1;
					echo '<div align="left">Autres Browser ='.$us_agt.'</div>';		
	*/	
				}

				//Get the Operating System data 
				//07/2008 wertronic.com mise à jour
					if(ereg("Windows NT 6.0", $us_agt)) { $os = "WindowsVista"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("Windows NT 5.2", $us_agt)){ $os = "WindowsServer2003"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("Windows NT 5.1", $us_agt)) { $os = "WindowsXP"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("Windows NT 5.0", $us_agt)) { $os = "Windows2000"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("Windows NT 4", $us_agt))  { $os = "WindowsNT"; $totalOS=$totalOS+1; $$os=$$os+1;}
					//DigExt extension in certain versions of MSIE Grabs content to make it available offline
					elseif(eregi("Windows NT", $us_agt) && eregi("DigExt", $us_agt))  { $os = "WindowsNT"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("Windows 98", $us_agt) || ereg("Win98", $us_agt) || ereg("Windows ME", $us_agt) || ereg("Win 9x 4.90", $us_agt)) { $os = "Windows98";$totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("Windows 95", $us_agt)) { $os = "Windows95"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif((ereg("Mac", $us_agt)) || (ereg("PPC", $us_agt))) { $os = "Mac"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("Linux", $us_agt)) { $os = "Linux"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("FreeBSD", $us_agt)) { $os = "FreeBSD"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("Unix", $us_agt)) { $os = "Unix"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("SunOS", $us_agt)) { $os = "SunOS"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("IRIX", $us_agt)) { $os = "IRIX"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("BeOS", $us_agt)) { $os = "BeOS"; $totalOS=$totalOS+1; $$os=$$os+1; }
					elseif(ereg("OS/2", $us_agt)) { $os = "OS2"; $totalOS=$totalOS+1; $$os=$$os+1;}
					elseif(ereg("AIX", $us_agt)) { $os = "AIX"; $totalOS=$totalOS+1; $$os=$$os+1;
					} else {
						//$os = "OtherOS";
						//echo '<div align="left">Autres OS ou Browser ='.$us_agt.'</div>';
						$Autres_OS_brower[] = $us_agt;
					}
	
			} // if(!preg_match($AllBots, $us_agt)) {
		}//Fin if ($FindCrawler == false ) {
	} //Fin if trash
} // Fin de For

if ($AfficheOS==true) {
$AfficheOS=false;

//	<!-- ############################## Liste OS ################################### -->
$show_page_os_nav_robots = "";
$show_page_os_nav_robots = '
	<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
	  <TBODY>
	  <TR>
		<TD><!-- Data BEGIN -->
		  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
			<TBODY>
			<TR>
			  <TH class=TABLETITLE>'.$MSG_OS_TITRE.'</TH>
			  </TR>
			<TR>
			  <TD colSpan=2><!-- Rows BEGIN -->
				<TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
				  <TBODY>
				  <TR>
					<TH WIDTH=33%>'.$MSG_OS.'</TH>
					<TH WIDTH=33%>'.$MSG_VISITEURS.'</TH>
					<TH>'.$MSG_VISITEURS_POURCENTAGE.'</TH></TR>
';

	// pour eviter l'erreur de la division par 0
	if($totalOS==0){
		$totalOS=1;
	}
	else{
		$class_os[]=array("Windows Vista",$WindowsVista,(bcdiv($WindowsVista,$totalOS,4)*100));
		$class_os[]=array("Windows Server 2003",$WindowsServer2003,(bcdiv($WindowsServer2003,$totalOS,4)*100));
		$class_os[]=array("Windows XP",$WindowsXP,(bcdiv($WindowsXP,$totalOS,4)*100));
		$class_os[]=array("Windows 2000",$Windows2000,(bcdiv($Windows2000,$totalOS,4)*100));
		$class_os[]=array("Windows NT 4",$WindowsNT,(bcdiv($WindowsNT,$totalOS,4)*100));
		$class_os[]=array("Windows 98/Me",$Windows98,(bcdiv($Windows98,$totalOS,4)*100));
		$class_os[]=array("Windows 95",$Windows95,(bcdiv($Windows95,$totalOS,4)*100));
		$class_os[]=array("Mac",$Mac,(bcdiv($Mac,$totalOS,4)*100));
		$class_os[]=array("Linux",$Linux,(bcdiv($Linux,$totalOS,4)*100));
		$class_os[]=array("FreeBSD",$FreeBSD,(bcdiv($FreeBSD,$totalOS,4)*100));
		$class_os[]=array("Unix",$Unix,(bcdiv($Unix,$totalOS,4)*100));
		$class_os[]=array("SunOS",$SunOS,(bcdiv($SunOS,$totalOS,4)*100));
		$class_os[]=array("IRIX",$IRIX,(bcdiv($IRIX,$totalOS,4)*100));
		$class_os[]=array("BeOS",$BeOS,(bcdiv($BeOS,$totalOS,4)*100));
		$class_os[]=array("OS/2",$OS2,(bcdiv($OS2,$totalOS,4)*100));
		$class_os[]=array("AIX",$AIX,(bcdiv($AIX,$totalOS,4)*100));
		$class_os[]=array("Autres OS",$OtherOS,(bcdiv($OtherOS,$totalOS,4)*100));
	}
	@usort($class_os,"CompareValeurs");
	
	for($i=0;$i<14;$i++){
		if ($class_os[$i][1]!=0) $show_page_os_nav_robots .= "<tr><td>".$class_os[$i][0]."</td><td align=center>".$class_os[$i][1]."</td><td align=center>".$class_os[$i][2]."%</td>";
	}
	
	$show_page_os_nav_robots .= '
	</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
	';
}

if ($AfficheNav==true) {
$AfficheNav=false;

//	<!-- ############################## Liste Navigateurs ################################### -->
$show_page_os_nav_robots .= '
	<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
	  <TBODY>
	  <TR>
		<TD><!-- Data BEGIN -->
		  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
			<TBODY>
			<TR>
			  <TH class=TABLETITLE>'.$MSG_NAV_TITRE.'</TH>
			  </TR>
			<TR>
			  <TD colSpan=2><!-- Rows BEGIN -->
				<TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
				  <TBODY>
				  <TR>
					<TH WIDTH=33%>'.$MSG_NAV.'</TH>
					<TH WIDTH=33%>'.$MSG_VISITEURS.'</TH>
					<TH>'.$MSG_VISITEURS_POURCENTAGE.'</TH></TR>
';
	
	// pour éviter l'erreur de la division par 0
	if($total==0){
		$total=1;
	}
	else{
		$class_nav[]=array("Internet Exlorer 7",$MSIE7,(bcdiv($MSIE7,$total,4)*100));
		$class_nav[]=array("Internet Exlorer 6",$MSIE6,(bcdiv($MSIE6,$total,4)*100));
		$class_nav[]=array("Internet Exlorer 5.5",$MSIE55,(bcdiv($MSIE55,$total,4)*100));
		$class_nav[]=array("Internet Exlorer 5",$MSIE5,(bcdiv($MSIE5,$total,4)*100));

		$class_nav[]=array("Netscape 6.0",$Netscape60,(bcdiv($Netscape60,$total,4)*100));
		$class_nav[]=array("Netscape 6.1",$Netscape61,(bcdiv($Netscape61,$total,4)*100));
		$class_nav[]=array("Netscape 6.2",$Netscape62,(bcdiv($Netscape62,$total,4)*100));
		$class_nav[]=array("Netscape Navigator => 7.0",$Netscape,(bcdiv($Netscape,$total,4)*100));

		$class_nav[]=array("Mozilla Firefox 1.0",$MozillaFirefox10,(bcdiv($MozillaFirefox10,$total,4)*100));
		$class_nav[]=array("Mozilla Firefox 1.4",$MozillaFirefox14,(bcdiv($MozillaFirefox14,$total,4)*100));
		$class_nav[]=array("Mozilla Firefox 1.5",$MozillaFirefox15,(bcdiv($MozillaFirefox15,$total,4)*100));
		$class_nav[]=array("Mozilla Firefox 2.0",$MozillaFirefox20,(bcdiv($MozillaFirefox20,$total,4)*100));
		$class_nav[]=array("Mozilla Firefox 3.0",$MozillaFirefox30,(bcdiv($MozillaFirefox30,$total,4)*100));
		$class_nav[]=array("Mozilla Firefox Navigator",$Firefox,(bcdiv($Firefox,$total,4)*100));

		$class_nav[]=array("AOL",$AOL,(bcdiv($AOL,$total,4)*100));
		$class_nav[]=array("Lynx",$Lynx,(bcdiv($Lynx,$total,4)*100));
		$class_nav[]=array("Opera",$Opera,(bcdiv($Opera,$total,4)*100));
		$class_nav[]=array("WebTV",$WebTV,(bcdiv($WebTV,$total,4)*100));
		$class_nav[]=array("Konqueror",$Konqueror,(bcdiv($Konqueror,$total,4)*100));
		$class_nav[]=array("Safari",$Safari,(bcdiv($Safari,$total,4)*100));

		$class_nav[]=array("Autres",$Other,(bcdiv($Other,$total,4)*100));	
	}
	@usort($class_nav,"CompareValeurs");
	
	for($i=0;$i<=count($class_nav);$i++){
		if ($class_nav[$i][1]!=0) $show_page_os_nav_robots .= "<tr><td>".$class_nav[$i][0]."</td><td align=center>".$class_nav[$i][1]."</td><td align=center>".$class_nav[$i][2]."%</td>";
	}

	$show_page_os_nav_robots .= '
	</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
	';
}

if ($AfficheRobots==true) {

$AfficheRobots=false;
$affiche_only_other_bots==false;

	############################## Liste Robots ###################################
$show_page_os_nav_robots .= '
	<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
	  <TBODY>
	  <TR>
		<TD><!-- Data BEGIN -->
		  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
			<TBODY>
			<TR>
			  <TH class=TABLETITLE>'.$MSG_ROBOTS_VISITES.'</TH>
			  </TR>
			<TR>
			  <TD colSpan=2><!-- Rows BEGIN -->
				<TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
				  <TBODY>
				  <TR>
					<TH WIDTH=40%>'.$MSG_ROBOTS_PARENT_NAME.'</TH>
					<TH>'.$MSG_ROBOTS_NAME.'</TH>
					<TH>'.$MSG_ROBOTS_NB_PAGE_SCAN.'</TH>
					<TH>'.$MSG_NB_DISTINCT_IP.'</TH>
					<TH>'.$MSG_VISITEURS_POURCENTAGE.'</TH>
				  </TR>
';

	@usort($class_robots,"CompareValeurs");

	$Total_distincts_Ip_bots = 0;
	$Total_distinct_robots = 0;

	for($i=0;$i< count($class_robots);$i++){
		$Total_distincts_Ip_bots = $Total_distincts_Ip_bots + $class_robots[$i][2] ;

		//Insertion et calcul des % de pages vues par les robots
		//(on ne pouvait pas le faire avant puisque $Total_page_visites_bot etait en cours de calcul)
		$class_robots[$i][3] = (bcdiv($class_robots[$i][1],$Total_page_visites_bot,4)*100);

		if ($class_robots[$i][1]!=0)  {
			if ($class_robots[$i][0]<>$MSG_ROBOTS_AUTRES) {$Total_distinct_robots = $Total_distinct_robots+1;}
			$show_page_os_nav_robots .= "<tr><td>".$class_robots[$i][0]."</td><td align=center>".stripslashes($class_robots[$i][4])."</td><td align=center>".$class_robots[$i][1]."</td><td align=center>".$class_robots[$i][2]."</td><td align=center>".$class_robots[$i][3].
			"%</td></tr>";
		}
	}

	$Total_distinct_robots = $Total_distinct_robots;
 	$show_page_os_nav_robots .= "<tr><td align=right><br><strong>Total: &nbsp;</strong><br><center>".$MSG_ROBOTS_KNOW_IN_DATABASE.$Total_distinct_robots."</center></td><td align=center>-</td><td align=center>".$Total_page_visites_bot."</td><td align=center>".$Total_distincts_Ip_bots."</td><td align=center>&nbsp;</td></tr>";

	//############################## Autres robots ########################################
		if ($Autres_robots) { 
			$Autres_robots = array_unique($Autres_robots);
			@usort($Autres_robots,"CompareValeurs");
			$show_page_os_nav_robots .= '<tr><td nowrap colspan=5><Strong><center><br>'.$MSG_ROBOTS_DETAILS.'</center></strong><br>';
			for($i=0;$i<count($Autres_robots);$i++){
				$show_page_os_nav_robots .= '['.$Autres_robots[$i].']<br>';
			}
			$show_page_os_nav_robots .= '</td></tr>';
		}

$show_page_os_nav_robots .= '
		</TBODY></TABLE>
			<tr><td align="center">
			<form name="GestionCrawler" method="post" action="index_frame.php">
				<input name="type" type="hidden" value="add_crawler">
				<input name="when" type="hidden" value="'.$when.'">
				<input name="mois" type="hidden" value="'.$mois.'">
				<input class="submitDate" name="SubmitGestionCrawler" type="submit" value="'.$MSG_TOOLS_BOTS.'" alt="'.$MSG_TOOLS_BOTS.'" title="'.$MSG_TOOLS_BOTS.'">
			</form>
			</tr></td>		
		<!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE>
<br>
';
	//####################################################################################

} //Fin de if ($AfficheRobots==true) {


	//############################## Affiche seulement les robots non référencés dans la base ########################################
} //End if (!$_SESSION['Autres_robots'] || $affiche_only_other_bots<>true) {

	if ($affiche_only_other_bots==true) {
		if ($Autres_robots) {
			$_SESSION['Autres_robots'] = $Autres_robots;
		}
		
$show_page_os_nav_robots .= '
		<a name="focusforminsert"></a>
		<TABLE width="" border=1 CELLPADDING=5 CELLSPACING=0 bgcolor="#FFFFFF">
';

//Note : Obscur fait trés rapidement
//ci-dessous index_frame.php fonctionne mais pas <?PHP_SELF; car dans variable $show_page_os_nav_robots
//		if ($_SESSION['Autres_robots']) { //Commenter cette ligne pour insert robot permanent
					//------------------------ Insert crawler ----------------------------------
					if (isset($InsertCrawler)){
						$show_page_os_nav_robots .= '
						<tr><td>
						<TABLE align="center" CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
						  <TBODY>
						  <TR>
							<TD><!-- Data BEGIN -->
							  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
								<TBODY>
								<TR>
								  <TH class=TABLETITLE>
										<form name="form1" method="post" action="index_frame.php">
											<table border="0" cellspacing="0" cellpadding="3">
											  <tr>
												<td align="center">'.$MSG_TOOLS_BOT_NAME.'</td>
												<td align="center">'.$MSG_TOOLS_BOT_PARENT_NAME.'</td>
												<td align="center">'.$MSG_TOOLS_BOT_URL.'</td>
												<td align="center">'.$MSG_COMMENTS.'</td>
											  </tr>
											  <tr>
												<td align="center"><input name="BotName" type="text" size="20"></td>
												<td align="center"><input name="BotParentName" type="text"></td>
												<td align="center"><input name="BotUrl" type="text"></td>
												<td align="center"><input name="BotComment" type="text"></td>
											  </tr>
										  </table>
											<input name="type" type="hidden" value="add_crawler">
											<input name="when" type="hidden" value="'.$when.'">
											<input name="mois" type="hidden" value="'.$mois.'">
											<input class="submitDate" name="DoInsertCrawler" type="submit" value="'.$MSG_ADD.'" alt="'.$MSG_ADD.'" >
											<input class="submitDate" name="AnnulerInsertCrawler" type="submit" value="'.$MSG_CANCEL.'" alt="'.$MSG_CANCEL.'" >
										</form>
								 </TH>
						</TR>
		  				</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --><BR>
						';
				 }

		//------ Pour formulaire insert robot permanent -------
		if ($_SESSION['Autres_robots'] ) { // Ligne déplacée
		//-----------------------------------------------------

			$_SESSION['Autres_robots'] = array_unique($_SESSION['Autres_robots']);
			@usort($_SESSION['Autres_robots'],"CompareValeurs"); //supprime champ vide
			$robots =$_SESSION['Autres_robots'] ;
			array_multisort ($robots, SORT_ASC); // Ne fonctionne pas sur tableau session
			//si jour mois est vide, si mois jour est vide
			$show_page_os_nav_robots .= '<tr><td nowrap colspan=5><Strong><center><br>'.$MSG_ROBOTS_DETAILS.' '.$when.$mois.'</center></strong><br>';
			for($i=0;$i<count($robots);$i++){
				//echo '['.$_SESSION['Autres_robots'][$i].']<br>';
				$show_page_os_nav_robots .= '['.$robots[$i].']<br>';
			}
			$show_page_os_nav_robots .= '</td></tr>';
		} else {
			$show_page_os_nav_robots .= "<strong><big><font color=#FF0000>Aucun robot inconnu à ajouter.</font></big></strong>";
		}
		//-------------------------------------------------------------------------

$show_page_os_nav_robots .= '
	</table>
	<br>
';

	}
	#################################################################################################################################


	############################## OS ou Browser non déterminés ################################### -->

if ($Autres_OS_brower[0] && $AfficheOS==true) { 
$show_page_os_nav_robots .= '
<br>
	<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
	  <TBODY>
	  <TR>
		<TD><!-- Data BEGIN -->
		  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
			<TBODY>
			<TR>
			  <TH class=TABLETITLE><'.$MSG_ROBOTS_OS_BROWSER_NOTKNOW.'</TH>
			  </TR>
			<TR>
			  <TD colSpan=2><!-- Rows BEGIN -->
				<TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
				  <TBODY>
					<tr><td nowrap>';
					//$Autres_OS_brower = remove_dups($Autres_OS_brower, 0); // Supprime les doublons d'un tab multi
					$Autres_OS_brower = array_unique($Autres_OS_brower);
					@usort($Autres_OS_brower,"CompareValeurs");
					for($i=0;$i<count($Autres_OS_brower);$i++){
						$show_page_os_nav_robots .= '['.$Autres_OS_brower[$i].']<br>';
					}
					$show_page_os_nav_robots .= '</td></tr>
	</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><br>
	';
   }
	//<!-- ############################## Bad user agent ################################### -->

if ($bad_user_agent) {
$show_page_os_nav_robots .= '
	<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
	  <TBODY>
	  <TR>
		<TD><!-- Data BEGIN -->
		  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
			<TBODY>
			<TR>
			  <TH class=TABLETITLE>'.$MSG_BAD_USER_AGENT.'</TH>
			  </TR>
			<TR>
			  <TD colSpan=2><!-- Rows BEGIN -->
				<TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
				  <TBODY>
				  <TR>
					<TH WIDTH=40%>'.$MSG_USER_AGENT.'</TH>
					<TH>'.$MSG_ROBOTS_NB_PAGE_SCAN.'</TH>
					<TH>'.$MSG_NB_DISTINCT_IP.'</TH>
					<TH>'.$MSG_COMMENTS.'</TH>
				  </TR>
';

					//$bad_user_agent = remove_dups($bad_user_agent, 0); // Supprime les doublons d'un tab multi
					@usort($bad_user_agent,"CompareValeurs");
					for($i=0;$i<count($bad_user_agent);$i++){ 
						if ($bad_user_agent[$i][4]== 'S') {
							$show_page_os_nav_robots .= '<tr><td><font color = #FF0000>'.$bad_user_agent[$i][0].'</font></td><td align=center>'.$bad_user_agent[$i][1].'</td><td align=center>'.$bad_user_agent[$i][2].'</td><td>'.$bad_user_agent[$i][3].'</td></tr>';
						} else {
							$show_page_os_nav_robots .= '<tr><td>'.$bad_user_agent[$i][0].'</td><td align=center>'.$bad_user_agent[$i][1].'</td><td align=center>'.$bad_user_agent[$i][2].'</td><td>'.$bad_user_agent[$i][3].'</td></tr>';
						}
					}
$show_page_os_nav_robots .= '
	</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
';
   } 

echo $show_page_os_nav_robots;

 ?>

	