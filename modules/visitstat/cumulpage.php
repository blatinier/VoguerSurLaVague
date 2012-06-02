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
//echo $AllBots;
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

		//----------------------------------------------
		//Calcul Nb visiteurs et pages hors bot Robots
		$result = mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois'");
		
		$NbpageVues_HorsBots=0;
		$NbVisites_HorsBots=0;
		while($row=mysql_fetch_array($result)){
			if(!preg_match($AllBots, $row[agent])) {
			//---------------------------------------
				$User_Agent=$row[agent];
				$trash=false;
				for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
					if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
						$trash=true;
					}
				}
			//---------------------------------------
			}

			if(!preg_match($AllBots, $row[agent]) && $trash==false) {		
				$NbpageVues_HorsBots = $NbpageVues_HorsBots+$row[nb_visite];
				$NbVisites_HorsBots = $NbVisites_HorsBots+1;
			}			
		}
		//echo 'Total Visites hors robots = '.$NbVisites_HorsBots.'<br>';
		//echo 'Total Page visitées hors robots = '.$NbpageVues_HorsBots.'<br>'; // Nb de page vue
		//----------------------------------------------

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
		$mois = $_POST["mois"];
		$jm = explode("/",$mois);
		$nbjourdumois = maxDaysInMonth($jm[0], $jm[1]);
		
		// Premier samedi du mois (6eme jour de la semaine) fonction get_first_day
		$premiersamedi =  strftime("%d", get_first_day(6, $jm[0], $jm[1])); 
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
 
		 //echo $graph_byday; //Affichage graph
		 $show_cumul_page .= $graph_byday;
		 $graph_byday ="";

################################################################################################################################################################

$show_cumul_page .= "
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE>". $MSG_REF_TITRE. ' '. $mois."</TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
                <TH>". $MSG_REFERANT."</TH>
                <TH>". $MSG_REF_MOTCLE."</TH>
                <TH>". $MSG_VISITEURS."</TH>
			  </TR>";
	
	require "config_moteur.php"; 

	//-------------------------------------------------------------------------------------------
	unset($Tab_referer);
	$result = mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois' order by referer");
	
	$nb_unknow = 0;
	while($row=mysql_fetch_array($result)){
		$referer=parse_url($row[referer]);
		
		if(!preg_match($AllBots, $row['agent'])) { // && $trash == false A voir exlus aussi les "bad user agent"
			//--------------------- extraction mots clés -------------
			$url=parse_url($site);
			$ref=$referer["host"];
	
			if($ref<>$url["host"]){
				//Pour les adwords l'url est trouvée car dans visiteur.php est ajouté au referer $Referer = urldecode($Url_syndication[0]). '?googlesyndication=1' ou '&googlesyndication=1';
				$test_keword[] = array($ref,MotsCles($row[referer],$ref));
			}
			//--------------------------------------------------------
				//--------------- ext referer et nb visite ---------------
				if ($row['referer']) { //
					$Tab_referer[] = $referer['host'];
				} else { //Visiteurs avec user agent et referer vide modif 31-08-2009
					$Tab_referer[] = "Unknown or direct";
					$nb_unknow = $nb_unknow + 1;
				}
		}
	}
		
		//Attention array_unique ne garde que les clés différentes mais garde la chronologie --> faire usort($Tab_referer, "CompareValeurs"); après
		$Tab_referer_unique = @array_unique($Tab_referer);
		@usort($Tab_referer_unique, "CompareValeurs");

		unset($Tab_aff_ref);
		for($i=0;$i<count($Tab_referer_unique);$i++){ 
			$result_agent = mysql_query("select agent from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and date like '%$mois'");
			$test_agent = mysql_fetch_array($result_agent);
		
			//if(!preg_match($AllBots, $test_agent['agent'])) n'est pas nécessaire mais certainement plus rapide comme ça
			if(!preg_match($AllBots, $test_agent['agent'])) { // && $trash == false A voir exlus aussi les "bad user agent" modif 31-08-2009

				if (trim($Tab_referer_unique[$i])<>'') {
					// compte le nombre de visiteurs par referant
					if ( strstr(trim($Tab_referer_unique[$i]), 'google') ) {
						$result = mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and date like '%$mois'");
					} else {
						$result = mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and referer not like '%source=web%' and referer not like '%source=hp%' and date like '%$mois'");
					}

					if (!$result) {
					   echo 'Impossible d\'exécuter la requête : ' . mysql_error();
					   exit;
					}
					$row_nb_visites=mysql_fetch_row($result);
					
					//------------------------------------------
					//tab mots clé/referant
					for($i2=0;$i2<count($test_keword);$i2++){ 
						if(strstr($test_keword[$i2][0],$Tab_referer_unique[$i])) {
								$referer_kew[$i] .= $test_keword[$i2][1].' ';
						}
					}
					//------------------------------------------
					
					if ($row_nb_visites[0] == 0){ //si = 0 => referer vide (donc result mysql vide) user agent et referer vide modif 31-08-2009
						$row_nb_visites[0] = $nb_unknow;
					}

					$Tab_aff_ref[] = array($row_nb_visites[0], $Tab_referer_unique[$i],$referer_kew[$i]); //dans ce sens car on trie sur nb visites
				}
			}
		}
		//--------------------------------------------------
		// Mise en forme mots clés googlesyndication et Affichage
		@array_multisort($Tab_aff_ref,SORT_DESC);

		for($i=0;$i<count($Tab_aff_ref);$i++){ 
			//echo $Tab_aff_ref[$i][2]."<br><br>";
			$Mots_by_referer =  str_replace('] [', ']+-+[', $Tab_aff_ref[$i][2]);
			$Mots_by_referer =  str_replace(']  [', ']+-+[', $Mots_by_referer); // car certains comportent 2 espaces --> A voir où ils sont mis ou supprimmer espaces multiples
			$Mots_by_referer =  str_replace(']   [', ']+-+[', $Mots_by_referer);// car certains comportent 3 espaces --> A voir où ils sont mis
			$Mots_by_referer = $Mots_by_referer; // Tout en minuscule
			//echo $Mots_by_referer."<br><br>";
			
			unset($Tab_motcles);
			$Tab_motcles = explode('+-+',$Mots_by_referer);
			for($j=0;$j<count($Tab_motcles);$j++){ 
				$Tab_motcles[$j] = trim($Tab_motcles[$j]); // Trés important pour array_unique après str_replace
				//echo $Tab_motcles[$j]."<br>";
			}
			
			unset($Tab_motcles_unique);
			$Tab_motcles_unique = array_unique($Tab_motcles);
			
			//----------------------
			unset($tab_keywords);
			for($j=0;$j<count($Tab_motcles_unique);$j++){ 
				if (trim($Tab_motcles_unique[$j])) {
					$tab_keywords[] = array($Tab_motcles_unique[$j], substr_count($Mots_by_referer, $Tab_motcles_unique[$j]));
				}
			}
			
			// Obtient une liste de colonnes
			unset($Mot);
			unset($Nb);
			if ($tab_keywords) {
				foreach ($tab_keywords as $key => $row) {
					$Mot[$key]  = $row[0];
					$Nb[$key] = $row[1];
				}
			}
			
			// Trie les données par volume croissant
			// Ajoute $tab_keywords en tant que dernier paramètre, pour trier par la clé commune
			@array_multisort($Nb, SORT_DESC, $tab_keywords);
			//---------------------------------------------------------------
			//Affichage
			$lenmax=35;
			if (strlen($Tab_aff_ref[$i][1])>$lenmax) {
				$chaine1=substr($Tab_aff_ref[$i][1], 0, $lenmax);
				$chaine2=substr($Tab_aff_ref[$i][1], $lenmax);
				$Tab_aff_ref[$i][1] = $chaine1."<br>".$chaine2;
			}

			//-----------------------------
				$show_cumul_page .= "<tr><td valign=top>".$Tab_aff_ref[$i][1]."</td>
				<td nowrap>"; //Référant
			
				if (count($Tab_motcles_unique)>1) {	
					$show_cumul_page .= "<span> Différents: ". count($Tab_motcles_unique)."</span>";
				}
				for($j=0;$j<count($tab_keywords);$j++){ //Affiche les phrases clés
					
					if (strstr($tab_keywords[$j][0],'googlesyndication=1')) { 
						$tab_keywords[$j][0] = str_replace('?googlesyndication=1', '<font color=#666666>&nbsp;&nbsp;'.utf8_encode($MSG_ADWORDS_CONTENT_NETWORK).'</font><br>', $tab_keywords[$j][0]);
						$tab_keywords[$j][0] = str_replace(htmlentities('&googlesyndication=1'), '<font color=#666666>&nbsp;&nbsp;'.utf8_encode($MSG_ADWORDS_CONTENT_NETWORK).'</font><br>', $tab_keywords[$j][0]);//ici il faut htmlentities('&googlesyndication=1'), voir si ne serait pas mieux au niveau de config moteur
						$tab_keywords[$j][0] = substr(trim($tab_keywords[$j][0]),0,-5); //supp le dernier '<br> '
					}
					
					$show_cumul_page .= "<br><span><font color=#990000>".utf8_decode(urldecode($tab_keywords[$j][0]))."</font></span>";
				}
				$show_cumul_page .= "&nbsp;</td>
				<td nowrap>";
				
				$show_cumul_page .= "<span> Total: ".$Tab_aff_ref[$i][0]."</span>";
				for($j=0;$j<count($tab_keywords);$j++){ //affiche le nombre de visite pour chaque mot ou référant
						$show_cumul_page .= "<br><span><font color=#990000>".utf8_decode(urldecode($tab_keywords[$j][1]))."</font></span>"; 
				}
				$show_cumul_page .= "&nbsp;</td></tr>";
			//----------------------------
		}
		//--------------------------------------------------
		unset($Tab_referer);
		unset($Tab_referer_unique);
		unset($row_nb_visites);
		unset($Tab_aff_ref);
	//--------------------------------------------------------------------------------

$show_cumul_page .= '
</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
';

	if ($mois==""){
		$mois = date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
	}

	$result=mysql_query("select * from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where date like '%$mois' and v.code=p.code order by heure");
	$nb_visite=0;
	$heure_premier="";
	while($row=mysql_fetch_array($result)){
		if($heure_premier==""){
			$heure_premier=$row[heure];
		}
		$nb_visite=$nb_visite+$row[nb_visite]; //Pour calcul %
		$heure_dernier=$row[heure];
	}
	
	$max_visite=$NbpageVues_HorsBots;
	
	$result=mysql_query("select * from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where v.date like '%$mois' and v.code=p.code order by page");
	$row=mysql_fetch_array($result);
	$url=$row[page];
	$nb_url=0;
	$nb_vis=0;
	$result=mysql_query("select * from ".TABLE_VISITEUR." v,".TABLE_PAGE." p where v.date like '%$mois' and v.code=p.code order by page");

	while($row=mysql_fetch_array($result)){
		$page=$row[page];

		if(!preg_match($AllBots, $row[agent])) {
			//---------------------------------------
				$User_Agent=$row[agent];
				$trash=false;
				for($nb_bad_user_agent=0;$nb_bad_user_agent<count($Matrice_bad_user_agent);$nb_bad_user_agent++){
					if ($Matrice_bad_user_agent[$nb_bad_user_agent][0] == $User_Agent && $Matrice_bad_user_agent[$nb_bad_user_agent][2]=='S') {
						$trash=true;
					}
				}
			//---------------------------------------
		}

		if(!preg_match($AllBots, $row[agent]) && $trash==false) {
			if($url==$page){
				$nb_url=$nb_url+$row[nb_visite];
				$nb_vis++;	
			} else {
				$nb=$nb_url*100; 
				if($max_visite!=0){
					$pourcent=bcdiv($nb,$max_visite,2);
				}
				$page_vue[]= array($url,$nb_vis, $nb_url, $pourcent);
				$url=$page;
				$nb_url=$row[nb_visite];
				$nb_vis=1;
			}
		}
	} // Fin if(!preg_match($AllBots, $row[agent])) {

        $nb=$nb_url*100; 
    	if($max_visite!=0){
    		$pourcent=bcdiv($nb,$max_visite,2);
    	}

$show_cumul_page .= '
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE>'. $MSG_PAGESVISITES.' ('.$MSG_ROBOTS_EXCLUS.')<br>'.$mois.'</TH>
          </TR>

        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
				<tr>
				<th valign="top" align="right">'. $MSG_TOTAL.' : <br><br></th>
				<td valign="top" align="center">'. $NbVisites_HorsBots.'<br></td>
				<td valign="top" align="center">'. $NbpageVues_HorsBots.'<br></td>
				<td valign="top" align="center">&nbsp;<br></td>
				</tr>
              <TR>
                <TH>'.$MSG_PAGE.'</TH>
                <TH>'.$MSG_VISITE.'</TH>
                <TH>'.$MSG_PAGESVISITES.'</TH>
                <TH>'.$MSG_PAGES_POURCENTAGE.'</TH></TR>';
				
	$page_vue[]= array($url,$nb_vis, $nb_url, $pourcent);
	@usort($page_vue, "CompareValeurs");
	$cpt=0;
	while ($page_vue[$cpt][0]<>""){
		$show_cumul_page .= "<tr><td>".utf8_decode($page_vue[$cpt][0])."</td><td align=center>".$page_vue[$cpt][1]."</td><td align=center>".$page_vue[$cpt][2]."</td><td align=center>".$page_vue[$cpt][3]."%</td></tr>";
		$cpt++;
	}
     
$show_cumul_page .= '
</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
';

	//############################################################################################
	//------------ Affichage Origine géographique des viteurs (hors robots) ----------------------

		$result=mysql_query("select agent, nb_visite ,domaine from ".TABLE_VISITEUR." where date like '%$mois'");
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

		$result=mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois'");
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

$show_cumul_page .= '
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE>'.$MSG_DOMAIN_TITRE.' ('.$MSG_ROBOTS_EXCLUS.')</TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
                <TH>'.$MSG_DOMAIN.' ('.count($Tab_country_pages_visiteurs).')</TH>
                <TH>'.$MSG_NB_VISITEURS.'</TH>
                <TH>'.$MSG_PAGESVISITES.'</TH>
			  </TR>
';

		for($i=0;$i<count($Tab_country_pages_visiteurs);$i++){
			if ($Tab_country_pages_visiteurs[$i][0]=='') { $Tab_country_pages_visiteurs[$i][0] = $MSG_ORIGIN_UNKNOWN;}
			$show_cumul_page .= "<tr>
			<td> 
			<b>".$Tab_country_pages_visiteurs[$i][0]."</b>
			</td>
			<td align=\"left\">
			<img src=\"images/histo-h.gif\" width=\""; 
			$hauteur=bcmul($Tab_country_pages_visiteurs[$i][2],$indice,2);  
			$show_cumul_page .= $hauteur .	"\" height=\"8\">".$Tab_country_pages_visiteurs[$i][2].
			"</td>
			<td align=\"left\">
			<img src=\"images/histo-h.gif\" width=\""; 
			$hauteur=bcmul($Tab_country_pages_visiteurs[$i][1],$indice,2);  
			$show_cumul_page .= $hauteur."\" height=\"8\">".$Tab_country_pages_visiteurs[$i][1].
			"</td>";	
		}

$show_cumul_page .= '    
</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
';

	//################# AFFICHAGE ########################
	echo $show_cumul_page;
	//####################################################


		$result = mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois'"); // Pour bots non définis
		$nbr_result = mysql_num_rows($result);

		$mois_actuelle = date('m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$mois_Visualise = $mois;
	
		$Mois_Annee_visualise = explode("/", $mois_Visualise);
		$mois_visualise = $Mois_Annee_visualise[0]; // mois
		$annee_visualise = $Mois_Annee_visualise[1]; //année
		
		$Mois_Annee_actuelle = explode("/", $mois_actuelle);
		$mois_actuelle = $Mois_Annee_actuelle[0];
		$annee_actuelle = $Mois_Annee_actuelle[1];
		
		if ($annee_actuelle.$mois_actuelle > $annee_visualise.$mois_visualise) { //Pas d'affichage du bouton ajouter robot		
			$dislpay_button_tool_bots = "false"; //Important "false" entre guillemets car affiche le bouton si  $dislpay_button_tool_bots = ""; 
		}	
		$AfficheOS=true;
		$AfficheNav=true;
		$AfficheRobots=true;
		include('tab_os_nav_robots.php'); // Affichage OS, navigateurs, robots

//############################### Mise en cache ###################################################
		//---------------- met en cache si mois entier --------------------------------------------
	/*
	echo "mois_visualise: ".$mois_visualise."<br>";
	echo "Annee_visualise: ".$annee_visualise."<br>";
	echo "mois__actuelle: ".$mois_actuelle."<br>";
	echo "Annee_actuelle: ".$annee_actuelle."<br>";
	*/
	if ($annee_actuelle.$mois_actuelle > $annee_visualise.$mois_visualise) { //on met en cache si annéemois_actuelle > annéemois_Visualise et on archive MySQL

				if (!is_dir("cache")) {
					mkdir ("cache");
				}
	
				if (!is_dir($path_allmystats."cache")) {
					mkdir ($path_allmystats."cache");
				}

				//--------------------------------------------------------------------------------------
				//car path image des fichiers cache  (A voir si faire path abs vers /allmystats/image/ comme dans stats_in.php)
				if (!is_dir($path_allmystats."cache/images")) {
					mkdir ($path_allmystats."cache/images");
				}
			
				if (!file_exists("cache/images/histo-v_black.gif")) {
					copy($path_allmystats."images/histo-v_black.gif", $path_allmystats."cache/images/histo-v_black.gif");
				}
				if (!file_exists("cache/images/histo-v.gif")) {
					copy($path_allmystats."images/histo-v.gif", $path_allmystats."cache/images/histo-v.gif");
				}
				if (!file_exists("cache/images/histo-vv.gif")) {
					copy($path_allmystats."images/histo-vv.gif", $path_allmystats."cache/images/histo-vv.gif");
				}
				if (!file_exists("cache/images/histo-h.gif")) {
					copy($path_allmystats."images/histo-h.gif", $path_allmystats."cache/images/histo-h.gif");
				}
				//--------------------------------------------------------------------------------------

			//$format_date_file_name = str_replace('/', '-', $mois_Visualise);
			$Mois_Annee = explode("/", $mois_Visualise);
			$format_date_file_name = $Mois_Annee[1].'-'.$Mois_Annee[0];
			
			//$Fnm = "cache/stats_".$site."_".$format_date_file_name.".html";
			$Fnm = "cache/stats_".$site."_".$format_date_file_name.".php";

			$inF = fopen($Fnm,"w");
		
			$show_footer = '<div align="center"><strong>AllMyStats Powered by</strong> <A href="http://www.wertronic.com" class="Style1">Wertronic</A><br>Cedstat Release</div><br>';
		
// A voir si on met dans config_allmystats  && $public == true	; et test si dessous	
$page_html = 
'<?
include_once("../application_top.php");
require "../config_allmystats.php";
if(($user_login!=$_SESSION["userlogin"] || $passwd!=$_SESSION["userpass"]))	{ 
	header("location: ../index_frame.php");
}
?>
			<html>
			<head>
			<title>AllMyStats - '. $site.' - '.$format_date_file_name.'</title>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<link rel="stylesheet" type="text/css" href="../stylesheet.css">
			</head>
			<body><table width="100%" border="0" align="center">
			  <tr>
				<td align="center"><big><strong>Archives site: '.$site." - Date: ".$mois_Visualise.'</strong></big><br></td>
			  </tr>
			  <tr>
				<td align="center">'.$show_footer . $show_cumul_page . $show_page_os_nav_robots . $show_footer.'</td>
			  </tr>
			</table></body></html>';
		
			fwrite($inF,$page_html);
			fclose($inF); 

	//------------ Archive tables allmystats MySQL ------------------------------------------
		/*
		//variables dans tab_os_robots.php
		echo "Test total robot: ".$Total_distinct_robots."<br>"; 
		echo "Test ip robot: ".$Total_distincts_Ip_bots."<br>"; //résultat un peu différent de cumul.php ?
		echo "Test total bot robot: ".$Total_page_visites_bot."<br>";
		//variables de cumulpage.php
		echo "Test total visites hors robots: ".$NbVisites_HorsBots."<br>";
		echo "Test total page vues hors robots: ".$NbpageVues_HorsBots."<br>";
		
		$Total_visites = $NbVisites_HorsBots + $Total_distincts_Ip_bots;
		$Total_page_vues = $NbpageVues_HorsBots + $Total_page_visites_bot;
		echo "Total visites: ".$Total_visites."<br>";
		echo "Total page_vues: ". $Total_page_vues."<br><br><br>";
		
		*/
		$Mois_Annee = explode("/", $mois_Visualise);
		$mois = trim($Mois_Annee[0]);
		$annee = trim($Mois_Annee[1]); //trim tjrs important derrière un explode
		$Total_visites = $NbVisites_HorsBots + $Total_distincts_Ip_bots;
		$Total_page_vues = $NbpageVues_HorsBots + $Total_page_visites_bot;

		$result = mysql_query("insert into ".TABLE_ARCHIVE." (annee, mois, visite, visiteur, visites_hors_bot, pages_hors_bot, visites_robot,pages_robots) values('$annee','$mois','$Total_page_vues','$Total_visites','$NbVisites_HorsBots','$NbpageVues_HorsBots','$Total_distincts_Ip_bots','$Total_page_visites_bot')") or die('Erreur SQL! '.$result.'<br>'.mysql_error()); 

		$result = mysql_query("delete from ".TABLE_VISITEUR." where code like '".$annee.$mois."%'") or die('Erreur SQL! '.$result.'<br>'.mysql_error()); 
		$result = mysql_query("delete from ".TABLE_PAGE." where code like '".$annee.$mois."%'") or die('Erreur SQL! '.$result.'<br>'.mysql_error()); ;

		//---------------------------------------------------------------------------------------
	}
//-------------------------------------------------------------------------------------------
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
