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
	require ('config_allmystats.php');
  	require('includes/mysql_tables.php');

	 if ($HTTP_COOKIE_VARS["AllMyStatsVisites"] == 'No record this') {
		echo $Flag_Exclus_by_cookie;
	 	return;
	 }

	//--------------- Ip non comptabilisées ------------------------- 
 	$Tab_element_Ip = explode(".",$_SERVER['REMOTE_ADDR']);

 	for($i=0;$i<count($IpExlues);$i++){
		$Tab_element_IpEx = explode(".",$IpExlues[$i]);
		$Nb = count($Tab_element_IpEx);

		for ($Ni=0; $Ni<count($Tab_element_IpEx); $Ni++) {
			if (trim($Tab_element_IpEx[$Ni])) { 
				$Ip_a_tester .= $Tab_element_Ip[$Ni].'.';
			} else { //si . et rien
				$IpExlues[$i] = substr($IpExlues[$i],0,strlen($IpExlues[$i])-1);
			}
		}
		$Ip_a_tester = substr($Ip_a_tester,0,strlen($Ip_a_tester)-1);

		if(@stristr($IpExlues[$i], $Ip_a_tester)) {
			echo $Flag_Exclus_by_IP;
			return;
		}
		
		$Ip_a_tester ='';
	}
 
//---------------------------------------------------------------------
//function connection(){
	require ('config_allmystats.php');
  	require('includes/mysql_tables.php');
	mysql_connect($mysql_host,$mysql_login,$mysql_pass) or die("Connexion à la base de données impossible");
	mysql_select_db($mysql_dbnom);
//}

	if($nom_page==""){ $nom_page=$REDIRECT_URL; }
	if($nom_page==""){ $nom_page="Inconnu"; }

	$date = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
	$heure = date('H:i',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
	$jour="$date%";

//Ajout du test de agent car par exemple Googlebot, Googlebot-Image sur même IP (pour affichage des robots).
//Note: si un visiteur vient de la même IP mais avec un navigateur différent une visite sera compté, mais c'est même mieux.
//Certains user agents comportent plus de 255 caractères et donc agent = n'est pas OK multiple visites pour un seul
$result = mysql_query("select * from ".TABLE_VISITEUR." where date like '".$jour."' and ip='".$_SERVER['REMOTE_ADDR']."' and agent='".substr($_SERVER['HTTP_USER_AGENT'],0,255)."' ");
$row = mysql_fetch_array($result);

if ($row['code'] != ""){ 
//Correction rapide avec @ -- A voir pourquoi ce warning (25-08-2009) seulement sur osc
	$nb_visite=$row['nb_visite']+1;
	@mysql_query("update ".TABLE_VISITEUR." set nb_visite='".$nb_visite."' where code='".$row[code]."'"); //warning ligne 72
	$verif = @mysql_query("select * from ".TABLE_PAGE." where code='".$row[code]."' and page='".utf8_encode($nom_page)."'");
	$verif_row = @mysql_fetch_array($verif); //seul warning possible sur cette ligne et quand ?

	if ($verif_row['page'] != ""){
		$p_visite=$verif_row[nb_visite]+1;
		$requete="update ".TABLE_PAGE." set nb_visite='".$p_visite."' where code='".$row[code]."' and page='".$verif_row[page]."'";
		mysql_query($requete);
	} else {
		mysql_query("insert into ".TABLE_PAGE." values ('".$row[code]."','".utf8_encode($nom_page)."','1','".$heure."')");
	}

} else {
	// détermination du code permettant la jointure entre la table visiteur et page
	$result=mysql_query("select max(code) as cd from ".TABLE_VISITEUR."");

	$dateY = date('Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
	$dateM = date('m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
	if($last_code=mysql_fetch_array($result)){
		$the_last = $last_code['cd'];
		$anneemois=substr($the_last,0,4)."/".substr($the_last,4,2);
		$verif_date = date('Y/m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));

		if($anneemois==$verif_date){
			//sera auto incrémente par la table MySQL 
			$cp = ""; 
		} else {
			$cp = $dateY.$dateM."00000001"; // est fixé et ne sera pas auto incremente par la table MySQL
		}
	} else {
		$cp = $dateY.$dateM."00000001";
	}
	
	//Tous les $REMOTE_ADDR sont remplacés par $_SERVER['REMOTE_ADDR'] car sinon ne fonctionne pas avec register_globals = Off
	//$host = @gethostbyaddr($_SERVER['REMOTE_ADDR'])." - IP: ".$_SERVER['REMOTE_ADDR'];
	if (@gethostbyaddr($_SERVER['REMOTE_ADDR'])) {
		$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	} else {
		$host = "No reverse dns response";
	}
/*
A essayer si
	if (trim(@gethostbyaddr($_SERVER['REMOTE_ADDR'] ==""){
		$host = "No reverse dns response";
	} elseif (@gethostbyaddr($_SERVER['REMOTE_ADDR'])) {
		$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	}
*/

	//$url=explode(".",$host);
	//$nb=count($url);

	//------------------------ Détermination du pays ------------------------------------------
	include_once("lib/geoip/geoip.inc");
				$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
				$record_name = geoip_country_name_by_addr($handle,$_SERVER['REMOTE_ADDR']);
				if ($record_name){ 
					$domaines = $record_name; 
				}

	//--------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------
//If googlesyndication && &go= n'est pas reperable adwords avec redirection
//Google à changé ses liens aux environ du 15-12-2008
//ex:http://googleads.g.doubleclick.net/pagead/ads?client=ca-pub-2846800145996233&dt=1229857641799&lmt=1228954495&output=html&slotname=2138990625&correlator=1229857641799&url=http%3A%2F%2Fwww.diamonotv.com
//A voir pour yahoo A vérifier -- pas OK search;_ylt= des fois aussi en recherche naturelle
//http://fr.search.yahoo.com/search;_ylt=A1f4cfOFlkVJPRwA2EBjAQx.?fr2=sg-gac&sado=1&p=grossiste&fr=slv8-divx&ei=UTF-8&pop=1&rd=r1
//recherche naturelle
//http://fr.search.yahoo.com/search?p=metrre+un+site+en+ligne&fr=yfp-lo&ei=UTF-8&rd=r1

//Pour Google syndication et googleads.g.doubleclick.net
	if (strstr($_SERVER['HTTP_REFERER'],'googlesyndication.com') || strstr($_SERVER['HTTP_REFERER'],'googleads.g.doubleclick.net')) {	
		$syndic = $_SERVER['HTTP_REFERER'];
		$Url_syndication = explode('&url=',$syndic);
		$Url_syndication = explode('&',$Url_syndication[1]);

		$Referer = urldecode($Url_syndication[0]);
		//Pour Google syndication
		if (strstr($Referer,'?')) { // si ? existe déjà mettre &
			$Referer = urldecode($Url_syndication[0]). '&googlesyndication=1'; 
		} else {
			$Referer = urldecode($Url_syndication[0]). '?googlesyndication=1';		
		}

	} else { //Normal
		$Referer = $_SERVER['HTTP_REFERER'];
	}
	//------------------------------------------
	$very_bad_agent = '/<\?|<script/i'; // drop this user agent -- inject
	if(!preg_match($very_bad_agent, $_SERVER['HTTP_USER_AGENT'])) {	
		//-------------------- Pour images.google -----------------
		if (strstr($Referer,'images.google') != '') {
			$url   = parse_url( $Referer );
			$query = $url["query"];
			$host  = $url["host"];
			parse_str($query);

			// Repérer le nom de Domaine dans l'URL
			preg_match("/^(http:\/\/)?([^\/]+)/i",$Referer,$chaines);
			$test_host = $chaines[2];
			$Referer = "http://".$chaines[2].trim(utf8_decode($prev));
		}
		//--------------------------------------------------------
		// Si new $cp est définit plus haut, sinon auto incremente par la table
		$sql1 = "insert into ".TABLE_VISITEUR." values('".$_SERVER['HTTP_USER_AGENT']."','".$Referer."','".$_SERVER['REMOTE_ADDR']."','".$date."','".$host."','".$cp."','".$domaines."','1');";
		$result = mysql_query($sql1) or die('Erreur 1 SQL! '.$sql1.'::'.mysql_error());  

		$result = mysql_query("select max(code) as cd from ".TABLE_VISITEUR."");
		$last_code = mysql_fetch_array($result);
		$sql = "insert into ".TABLE_PAGE." values ('".$last_code['cd']."','".utf8_encode($nom_page)."','1','".$heure."');";

		$result=mysql_query($sql) or die('Erreur  2 SQL! '.$sql.'::'.mysql_error());  
	}
}

//mysql_close();
?>
