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
		//-------------------- Mise en tableau de la table bad user agent - FAUT il refaire ou mettre en funct --------
		unset($Matrice_bad_user_agent); //Important car si vient de normal.php double tout
		$Bad_User_Agent=mysql_query("select * from ".TABLE_BAD_USER_AGENT.""); //
		while($bad_agents=mysql_fetch_array($Bad_User_Agent)){ // Mise en tableau des bad agents
			$Matrice_bad_user_agent[] = array($bad_agents['user_agent'], $bad_agents['info'],$bad_agents['type']);
		}
		//-------------------------------------------------------------------------------------------------------------

?>

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? 
				echo $MSG_REF_TITRE; 
				if($display_best_referer) {
					echo ' - '.$when;
				} 
		  ?></TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
                <TH><? echo $MSG_REFERANT; ?></TH>
                <TH><? echo $MSG_REF_MOTCLE; ?></TH>
                <TH><? echo $MSG_VISITEURS; ?></TH>
			  </TR>
<?	
	require "config_moteur.php"; 

	//-------------------------------------------------------------------------------------------
	unset($Tab_referer);
	$result = mysql_query("select * from ".TABLE_VISITEUR." where date='$when' order by referer");

$nb_unknow = 0;
	while($row = mysql_fetch_array($result)){
		$referer = parse_url($row['referer']);
		
		if(!preg_match($AllBots, $row['agent'])) { // && $trash == false A voir exlus aussi les "bad user agent"
/*
//En test pour afficher la position en face des mots clés  -----------------
			$start="";
			$cd="";
			$referer = parse_url($row['referer']);
			//$host = parse_url($test_agent['referer']);
			$query = $referer["query"];
			parse_str($query);				
			
			echo $row['referer']."<br>";
			
			if (strstr($referer["host"],'google') && strstr($row['referer'],'source=web')) {
			echo "Google Position = ".$cd."<br>";
			} elseif ( strstr($referer["host"],'google') && !strstr($row['referer'],'source=web') && $start ) { 
			echo "Google page start = ".$start."<br>";
			} elseif ( strstr($referer["host"],'google') && !strstr($row['referer'],'source=web') && !$start ) {
			echo "Google 1ere page<br>";
			}
//------------------------------------------------------------------------------
*/
			//--------------------- extraction mots clés -------------
			$url = parse_url($site);
			$ref = $referer["host"];
	
			if($ref<>$url["host"]){
				//Pour les adwords l'url est trouvée car dans visiteur.php est ajouté au referer $Referer = urldecode($Url_syndication[0]). '?googlesyndication=1' ou '&googlesyndication=1';
				$test_keword[] = array($ref,MotsCles($row['referer'],$ref));
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
		//Il faut compter le nombre de visites mais pas si user agent est un bot
		for($i=0;$i<count($Tab_referer_unique);$i++){ 
			$result_agent = mysql_query("select agent, referer from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and date like '%$when'");
			$test_agent = mysql_fetch_array($result_agent);

			//Plus necessaire si utilisation de and agent NOT REGEXP '".$AllBots."' if(!preg_match($AllBots, $test_agent['agent'])) n'est pas nécessaire mais certainement plus rapide comme ça
			if(!preg_match($AllBots, $test_agent['agent'])) { // && $trash == false A voir exlus aussi les "bad user agent" modif 31-08-2009

				if (trim($Tab_referer_unique[$i])<>'') {
					
					//NOTE google est en plein bricolage 31-08-2009
					//as_q au lieu de q pour les keyword
					//Leur nouveau format /url?sa ... source= etc.. avec en plus quelques fois le host du FAI de l'internaute dans la ligne dont la longueur n'en finit plus
					//donc certain mots clés sortes des 255 caractères du champs mysql (était à 200 --> le passer à 255 ou même 512)
					//C'est pour ça que de temps en temps le total mots clé ne correspond pas 
					if ( strstr(trim($Tab_referer_unique[$i]), 'google') ) {
						$result = mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and date like '%$when'");
					} else {
						$result = mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and agent NOT REGEXP '".$AllBots."' and referer not like '%source=web%' and referer not like '%source=hp%' and date like '%$when'");
					}
					
					if (!$result) {
					   echo 'Impossible d\'exécuter la requête : ' . mysql_error();
					   exit;
					}
	
					$row_nb_visites = mysql_fetch_row($result);
					//------------------------------------------
					//tab mots clé/referant
                    $referer_kew = array();
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
			$Mots_by_referer = $Mots_by_referer; //On met en minuscule dans config_moteur.php
			//echo $Mots_by_referer."<br><br>";
			
			unset($Tab_motcles);
			$Tab_motcles = explode('+-+',$Mots_by_referer);
			for($j=0;$j<count($Tab_motcles);$j++){ 
				$Tab_motcles[$j] = trim($Tab_motcles[$j]); // trim trés important pour array_unique après str_replace
				//echo $Tab_motcles[$j]."<br>";
			}
			
			unset($Tab_motcles_unique);
			$Tab_motcles_unique = array_unique($Tab_motcles);
			//print_r($Tab_motcles_unique);
			//----------------------
			unset($tab_keywords);
			for($j=0;$j<=count($Tab_motcles_unique);$j++){ //= sinon n'affiche pas le dernier mot en cours
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
			//Pour affichage googlesyndication
				echo "<tr><td valign=top>".$Tab_aff_ref[$i][1]."</td>
				<td nowrap>"; //Référant
			
				if (count($Tab_motcles_unique)>1) {	
					echo "<span> Différents: ". count($Tab_motcles_unique)."</span>";
				}
				for($j=0;$j<count($tab_keywords);$j++){ //Affiche les phrases clés
					
					if (strstr($tab_keywords[$j][0],'googlesyndication=1')) { 
						$tab_keywords[$j][0] = str_replace('?googlesyndication=1', '<font color=#666666>&nbsp;&nbsp;'.utf8_encode($MSG_ADWORDS_CONTENT_NETWORK).'</font><br>', $tab_keywords[$j][0]);
						$tab_keywords[$j][0] = str_replace(htmlentities('&googlesyndication=1'), '<font color=#666666>&nbsp;&nbsp;'.utf8_encode($MSG_ADWORDS_CONTENT_NETWORK).'</font><br>', $tab_keywords[$j][0]);//ici il faut htmlentities('&googlesyndication=1'), voir si ne serait pas mieux au niveau de config moteur
						$tab_keywords[$j][0] = substr(trim($tab_keywords[$j][0]),0,-5); //supp le dernier '<br> '
					}

					echo "<br><span><font color=#990000>".trim(utf8_decode(urldecode($tab_keywords[$j][0])))."</font></span>";
				}
				echo "&nbsp;</td>
				<td nowrap>";

				echo "<span> Total: ".$Tab_aff_ref[$i][0]."</span>";
				for($j=0;$j<count($tab_keywords);$j++){ //affiche le nombre de visite pour chaque mot ou référant
						echo "<br><span><font color=#990000>".utf8_decode(urldecode($tab_keywords[$j][1]))."</font></span>"; 
				}

				echo "&nbsp;</td></tr>";
				
			//----------------------------
		}

		unset($Tab_referer);
		unset($Tab_referer_unique);
		unset($row_nb_visites);
		unset($Tab_aff_ref);
	//--------------------------------------------------------------------------------
?>      
	 </TBODY></TABLE><!-- Rows END --></TD></TR><TR>
		<?	
		if($display_best_referer) {
		?>
			<TH colSpan=2><SPAN class=TABLEHREF>
				<form name="form1" method="post" action="<?PHP_SELF;?>">
					<input name="when" type="hidden" value="<? echo $when; ?>">
					<input class="submit" name="detail_ref" type="submit" value="<? echo $MSG_RETOUR; ?>" alt="<? echo $MSG_RETOUR; ?>" >
				</form></SPAN></TH>
		<?	
		}
		?>
</TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>

<?	
	##################################### Affichage meilleurs référants ###########################################################
if($display_best_referer) {
?>
	<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
	  <TBODY>
	  <TR>
		<TD><!-- Data BEGIN -->
		  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
			<TBODY>
			<TR>
			  <TH class=TABLETITLE><? echo $MSG_REF_CUMUL; ?></TH>
			  </TR>
			<TR>
			  <TD colSpan=2><!-- Rows BEGIN -->
				<TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
				  <TBODY>
				  <TR>
					<TH><? echo $MSG_REFERANT; ?></TH>
					<TH><? echo $MSG_VISITEURS; ?></TH>
				  </TR>
	<?	
	
			unset($Tab_referer);
		
			$date=explode("/",$when);
			$result=mysql_query("select * from ".TABLE_VISITEUR." where date like '%/".$date[1]."/".$date[2]."' order by referer");
		
			while($row=mysql_fetch_array($result)){
				$referer=parse_url($row[referer]);
				if ($row[referer]) {
					$Tab_referer[] = $referer[host];
				}
			}
			
			//Attention array_unique ne garde que les clés différentes mais garde la chronologie --> faire usort($Tab_referer, "CompareValeurs"); après
			$Tab_referer_unique = @array_unique($Tab_referer);
			@usort($Tab_referer_unique, "CompareValeurs");
	
			for($i=0;$i<count($Tab_referer_unique);$i++){ 
				if (trim($Tab_referer_unique[$i])<>'') {
					$result=mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where referer like '%".$Tab_referer_unique[$i]."%' and date like '%/".$date[1]."/".$date[2]."'");
	
					if (!$result) {
					   echo 'Impossible d\'exécuter la requête : ' . mysql_error();
					   exit;
					}
	
					$row_nb_visites=mysql_fetch_row($result);
					$Tab_aff_ref[] = array($row_nb_visites[0], $Tab_referer_unique[$i]); //dans ce sens car on trie sur nb visites
				}
			}
			
			unset($Tab_referer);
			unset($Tab_referer_unique);
			unset($row_nb_visites);
				
				//------------------------------------------------		
				//Affichage du total adwords googlesyndication pour le mois
				$resultgooglesyndication=mysql_query("select count(*) as somme from ".TABLE_VISITEUR." where referer like '%googlesyndication%' and date like '%/".$date[1]."/".$date[2]."'");
				$row_nb_googlesyndication=mysql_fetch_row($resultgooglesyndication);
			if($row_nb_googlesyndication[0]) {
				echo "<tr><td>Total Adwords réseau de contenu</td><td>".$row_nb_googlesyndication[0]."</td></tr>";
			}
				//------------------------------------------------
			//Affichage 
			@array_multisort($Tab_aff_ref,SORT_DESC);
			for($i=0;$i<count($Tab_aff_ref);$i++){ 
				echo "<tr><td>".$Tab_aff_ref[$i][1]."</td><td>".$Tab_aff_ref[$i][0]."</td></tr>";
			}
			unset($Tab_aff_ref);
	
	?>      
	 </TBODY></TABLE><!-- Rows END --></TD></TR><TR>
			  <TH colSpan=2><SPAN class=TABLEHREF>
				<form name="form1" method="post" action="<?PHP_SELF;?>">
					<input name="when" type="hidden" value="<? echo $when; ?>">
					<input class="submit" name="detail_ref" type="submit" value="<? echo $MSG_RETOUR; ?>" alt="<? echo $MSG_RETOUR; ?>" >
				</form>
			  </SPAN></TH></TR>
	</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END -->
<? 
} // end if($display_best_referer) {
?>	
	</TD></TR></TBODY></TABLE>
