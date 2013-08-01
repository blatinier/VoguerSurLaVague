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
	$time_test = false; // true for test 
	if($time_test == true) {
		$start = (float) array_sum(explode(' ',microtime()));  		
	}

	// -------- Should not be called directly ---------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/visiteur.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------

	// PHP5.4 Suppress DateTime warnings (if not set in php.ini) => date_default_timezone_set -> UTC
	if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) {
		date_default_timezone_set(@date_default_timezone_get());
	}

	//-------------------------------------------------------
	//security
	$date_now =''; 
	$heure = '';
	$jour = '';
	$cp = '';
	//-------------------------------------------------------
			
	include(dirname(__FILE__).'/includes/config_error_reporting.php'); // Pour PHP5.4 (does not always work with PHP5.4 only for this visiteur.php ?)
	
	require_once(dirname(__FILE__).'/config_allmystats.php');
	require_once(dirname(__FILE__).'/config_add.php');
 	require_once(dirname(__FILE__).'/includes/mysql_tables.php');
	require_once(dirname(__FILE__).'/includes/filename.php');
	require_once(dirname(__FILE__).'/includes/functions/'.FILENAME_VISTORS_FUNCTIONS);
	require_once(dirname(__FILE__).'/includes/functions/charset.php');		

	###########################################################
	// -------------------- TEST URL Search engine ------------
	//require(dirname(__FILE__).'/includes/urltest_inc.php');
	// --------------------------------------------------------
	###########################################################
	
	$remote_adrr_ip = $_SERVER['REMOTE_ADDR']; 

	if (@gethostbyaddr($remote_adrr_ip)) {
		$reverse_dns = gethostbyaddr($remote_adrr_ip);
	} else {
		$reverse_dns = "No reverse dns response";
	}

	// Note le cookie installé (by IE or Firefox or Opera, etc)
	if (isset($_COOKIE["AllMyStatsVisites"]) && $_COOKIE["AllMyStatsVisites"] == 'No record this') { // PHP5.4 to not display messages "NOTICE"
		echo $Flag_Exclus_by_cookie;
		return;
	}

	//------------------------------------------------------------------------------------------------------------
	/* Conserve toutes les connexions séparées.(,true) - Si d'autres connexions sont ouvertes avant
	   permet à mysql_connect() de toujours ouvrir une nouvelle connexion - même si $mysql_host,$mysql_login,$mysql_pass identique */
	// Sauve le nom de la base SQL en cours d'utilisation et on la restaure en fin de script
	$current_database = ''; // PHP5.4 not display messages "NOTICE"
	if($r = @mysql_query("SELECT DATABASE()")){
		$current_database = mysql_result($r,0);
	}

	$db_allmystats = mysql_connect($mysql_host,$mysql_login,$mysql_pass,true) or die("Connexion à la base de données impossible");
	mysql_select_db($mysql_dbnom, $db_allmystats) or die('Could not select database.');
	mysql_query("SET NAMES 'utf8'"); 	
	
	if($nom_page == ""){ $nom_page = basename($_SERVER['SCRIPT_NAME']); }
	// La page qui appelle ce script peut être en utf-8 ou iso-8859-1, avec codage é ou &eacute;
	// Si cp1251 cyrillic exclusif ou ascii mais les accents ne seront pas pris
	// Si utf-8 iso-8859-1, cyrillic mixed OK
	$prepare_nom_page = str_replace(" ","",$nom_page);   // Test charset no space else error on cp1251 and cp1251 must be first and only cyrillic caractères 
	
	$encode = mb_detect_encoding($prepare_nom_page, "cp1251,UTF-8,iso-8859-1"); // TOTO new detection
	if($encode <> 'UTF-8') {
		$nom_page = mb_convert_encoding($nom_page,"UTF-8",$encode);
	}

	$nom_page = mysql_real_escape_string($nom_page); //01-08-2010

	// --- ligatures ----
	//http://www.atwebresults.com/ascii-codes.php
	//chr(198) = Æ, chr(230) = æ, chr(156) = œ, chr(140) = Œ, chr(128) = €
	$replace_char_lig = array(utf8_encode(chr(230)),utf8_encode(chr(198)),utf8_encode(chr(156)),utf8_encode(chr(140)),utf8_encode(chr(128)));
	$nom_page = str_replace($replace_char_lig, array('&#xE6;','&#xC6;','&#x153;','&#x152;','&#x80;'), $nom_page);	// OK display utf8
	// ------------------
	$date_now = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
	$heure = date('H:i',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
	$jour = "$date_now%";

	// Actuellement format date seulement pour TABLE_UNIQUE_BAD_AGENT
	// TODO toutes les tables
	$exd_date_now = explode('/', $date_now);
	$MySQL_date_now = $exd_date_now[2].'-'.$exd_date_now[1].'-'.$exd_date_now[0]; 

	//########################################################################################################################################
	// By Reverse DNS - Detect crawler
	// Because some bots are not listed in $ _SERVER['HTTP_USER_AGENT'] but are marked in the reverse dns (eg : msnbot-65-55-213-56.search.msn.com)
	$is_bots_referer = false;
	if( preg_match($detect_bot_on_reverseDNS, $reverse_dns) && !is_crawler($_SERVER['HTTP_USER_AGENT']) ) {	
		$is_bots_referer = true;
		$_SERVER['HTTP_USER_AGENT'] = 'Detected by reverse dns: '.$reverse_dns;
	}

	/* ----- bad user agent and unkown agents in table TABLE_BAD_USER_AGENT ----- */
	$All_bad_user_agent = ''; // PHP5.4 to not display messages "NOTICE"
	$Bad_User_Agent = mysql_query("select * from ".TABLE_BAD_USER_AGENT.""); 
	while($bad_agents = mysql_fetch_array($Bad_User_Agent)){ 
		$All_bad_user_agent .= "+-+".$bad_agents['user_agent'];
	}
	/* ---------------------------------- */
	// By Reverse DNS - Detect bad visitor from config_add.php --> Put in table unique_bad_agent (display tile: Visitor ????)
	if(preg_match($detect_bad_by_reverseDNS, $reverse_dns) && !is_crawler($_SERVER['HTTP_USER_AGENT']) ) {	
		$bad_by_reverseDNS_detected = true;
		$All_bad_user_agent .= "+-+".htmlentities($_SERVER['HTTP_USER_AGENT']);
	}
	/* ---------------------------------- */
	// By referer - Detect bad visitor from config_add.php --> Put in table unique_bad_agent (display tile: Visitor ????)
	if(isset($detect_bad_by_referer) && trim($detect_bad_by_referer <> '')) {
		$referer_format = str_replace('http://', '', urldecode($_SERVER['HTTP_REFERER']));
		if(preg_match($detect_bad_by_referer, $referer_format)) {
			$bad_by_referer_detected = true;	
			$All_bad_user_agent .= "+-+".htmlentities($_SERVER['HTTP_USER_AGENT']);
		}	
	}
	/* ---------------------------------- */
	// By IP - Detect bad visitor from config_add.php --> Put in table unique_bad_agent (display tile: Visitor ????)
	if(isset($BadIpList) && trim($BadIpList <> '')) {
		if (Is_In_BlIpList($remote_adrr_ip, $BadIpList)) { // PHP5.4
			$bad_by_IP_detected = true;	
			$All_bad_user_agent .= "+-+".htmlentities($_SERVER['HTTP_USER_AGENT']);
		}
	}
	/* ---------------------------------- */
	/* --> injection cross-site scripting */
	//$inject_pattern = '/<\?|<script>/i';
	$inject_pattern = '/<\?|\?>|<script>|<a href=/i';
	if(preg_match($inject_pattern, $_SERVER['HTTP_USER_AGENT'])) {
		$bad_by_inject_detected = true;	
		$All_bad_user_agent .= "+-+".htmlentities($_SERVER['HTTP_USER_AGENT']);
	}
	if(isset($_SERVER['REQUEST_URI']) && preg_match($inject_pattern, urldecode($_SERVER['REQUEST_URI']))) {
		$bad_by_inject_detected = true;	
		$url_inject = urldecode($_SERVER['REQUEST_URI']);
		$All_bad_user_agent .= "+-+".htmlentities($_SERVER['HTTP_USER_AGENT']);
	}
	/* ---------------------------------- */

	$All_bad_user_agent .= '+-+';

	//###########################################################################################################################################
	
	//Table Cumule pages mensuel
	$MonthYear = date('m/Y', strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));

	#####################################################################################################
	//Ajout du test de agent car par exemple Googlebot, Googlebot-Image sur même IP (pour affichage des robots).
	//Note: si un visiteur vient de la même IP mais avec un navigateur différent une visite sera compté, mais c'est même mieux.
	//Certains user agents comportent plus de 255 caractères et donc agent = n'est pas OK => multiple visites pour un seul

// Since MySQL new tables, it's not necessary Googlebot, Googlebot-Image on same IP count 2 visitors - for the same IP, counting one visitor.
$result = mysql_query("select * from ".TABLE_UNIQUE_VISITOR." where date like '".$jour."' and ip='".$remote_adrr_ip."'");

$row = mysql_fetch_array($result);

if (strpos(html_entity_decode($All_bad_user_agent), '+-+'.$_SERVER['HTTP_USER_AGENT'].'+-+') === false) { // because htmlentities( if is injection cross site

	if(!@is_crawler($_SERVER['HTTP_USER_AGENT']) && !$is_bots_referer) { // @ PHP5.4	

		if ($row['code'] <> ""){ // Ce visiteur est déjà passé aujourd'hui
			
			@mysql_query("update ".TABLE_UNIQUE_VISITOR." set visits=visits+1 where code='".$row['code']."'");
	
			$verif = @mysql_query("select * from ".TABLE_PAGE_VISITOR." where code='".$row['code']."' and page='".$nom_page."'");
			$verif_row = @mysql_fetch_array($verif); //seul warning possible sur cette ligne et quand ?

			if ($verif_row['page'] <> ""){ //Ce visiteur est déjà passé aujourd'hui ET la page a déjà été visitée
				$requete = "update ".TABLE_PAGE_VISITOR." set visits=visits+1 where code='".$row['code']."' and page='".$nom_page."'";
				mysql_query($requete);
				//-------------------------- TABLE_DAYS_PAGES -----------------------------
				//$visited_pages
				$requete = "update ".TABLE_DAYS_PAGES." set visited_pages=visited_pages+1 where pages_name='".$nom_page."' and date='".$date_now."'";
				@mysql_query($requete);
			} else { //Ce visiteur est déjà passé aujourd'hui ET visite une nouvelle page (pour aujourd'hui)
				mysql_query("insert into ".TABLE_PAGE_VISITOR." values ('".$row['code']."','".$nom_page."','1','".$heure."')");
				//-------------------------- TABLE_DAYS_PAGES -----------------------------
				//EN TEST car ... ???
				$result = @mysql_query("select visited_pages from ".TABLE_DAYS_PAGES." where pages_name='".$nom_page."' and date='".$date_now."'"); 
				$row_day = @mysql_fetch_array($result);
				if ($row_day['visited_pages']) {					
					$requete = "update ".TABLE_DAYS_PAGES." set visitors=visitors+1, visited_pages=visited_pages+1 where pages_name='".$nom_page."' and date='".$date_now."'";
					@mysql_query($requete);
				} else {
					$sql_day_pages = "insert into ".TABLE_DAYS_PAGES." (date, pages_name, visitors, visited_pages) values('".$date_now."','".$nom_page."','1','1');";
					@mysql_query($sql_day_pages);  
				}
			}
		
		} else { // ---------------------------------------- VISITEUR -------------------------------------------------------------------
	
			// détermination du code permettant la jointure entre la table visiteur et page
			$result= mysql_query("select max(code) as cd from ".TABLE_UNIQUE_VISITOR.""); 
		
			$dateY = date('Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			$dateM = date('m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			if($last_code = mysql_fetch_array($result)){
				$the_last = $last_code['cd'];
				$anneemois = substr($the_last,0,4)."/".substr($the_last,4,2);
				$verif_date = date('Y/m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		
				if($anneemois == $verif_date){
					//sera auto incrémente par la table MySQL 
					$cp = ""; 
				} else {
					$cp = $dateY.$dateM."00000001"; // est fixé et ne sera pas auto incremente par la table MySQL
				}
			} else {
				$cp = $dateY.$dateM."00000001";
			}

			//------------------------ Détermination du pays ------------------------------------------
			if(!function_exists('geoip_open')) { //To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in if in page
				require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
			}

			$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
			$record_name = geoip_country_name_by_addr($handle,$remote_adrr_ip);
			@geoip_close($handle);

			if ($record_name){ 
				if($record_name == 'Kazakstan') { $record_name = 'Kazakhstan'; } // if geoip.inc already loaded, but with an older version that contains the error: Kazakstan for Kazakhstan
				$country = $record_name; 
			} else {
				$country = '';
			}
		
			//-----------------------------------------------------------------------------------------

				if(isset($_SERVER['HTTP_REFERER'])) {
					$Referer = urldecode($_SERVER['HTTP_REFERER']);
				} else {
					$Referer = '';
				}

				################################################################################################################
				// USED decoding entire url
				$Referer = detect_convert_charset($Referer, 'utf-8');	// Detect and convert UTF8,cp1251,KOI8-R,cp866 to UTF-8 

				################################################################################################################
				/*
				Pour repérer les AdWords Réseau de contenu avec redirection (googleads.g.doubleclick.net)
				http://www.google.com/support/analytics/bin/answer.py?answer=55590
				Le paramètre utilisé pour le marquage automatique est appelé "gclid". Il apparaît dans l'URL de votre page de destination lorsqu'un internaute accède à votre site via l'une de vos annonces. Par exemple, si votre site a pour nom www.mysite.com, lorsqu'un internaute clique sur votre annonce, ce paramètre s'affiche de la manière suivante dans la barre d'adresse :
				www.mysite.com/?gclid=123xyz 
	
				Google syndication et googleads.g.doubleclick.net
				Si Marquage automatique du compte Google est activé, les url's sont taguées pour les mots clé aussi et on n'a pas "/aclk"			
	
				TODO $host_referer in search_engine.php and return array ($keyword, $host_referer, $startpage(if exist)
				*/
				
				$parse_referer = parse_url($Referer); 
				if(isset($parse_referer['host'])) {
					$host_referer = $parse_referer['host']; 
				} else {
					$host_referer = '';
				}

				// Attention on doit appeler la function aprés formatage search_keyword_in du Referer pour reperer AdWords Content Network

				if (strstr($Referer,'googlesyndication.com') || strstr($Referer,'googleads.g.doubleclick.net')) {	//Note: Si marquage automatique Google activé, est tagé avec gclid
					$syndic = $Referer;
					$Url_syndication = explode('&url=',$syndic);
					$Url_syndication = explode('&',$Url_syndication[1]);
			
					$Referer = urldecode($Url_syndication[0]);
					if (strstr($Referer,'?')) { // si ? existe déjà mettre &
						$Referer = urldecode($Url_syndication[0]). '&googlesyndication=1'; 
					} else {
						$Referer = urldecode($Url_syndication[0]). '?googlesyndication=1';		
					}
					$Keyword = mysql_real_escape_string(trim(search_keyword_in($Referer))); 	// URL - if in $Referer "googlesyndication=1" renvoi url, si keyword renvoi keyword
	
				} elseif (strstr($_SERVER['QUERY_STRING'],'gclid=')) { // AdWords "Réseau de contenu" or "keyword" --> Dans le compte AdWords, le marquage automatique doit être activé.
					$Keyword = mysql_real_escape_string(trim(search_keyword_in($Referer))); 	// if in $Referer "googlesyndication=1" renvoi url, si keyword renvoi keyword
					
					if(trim($Keyword == '')){
						if (trim($Referer <>'')) {
							if (strstr($Referer,'?')) { // si ? existe déjà mettre &
								$Referer = $Referer. '&googlesyndicReseauGCLID=1'; 
							} else {
								$Referer = $Referer. '?googlesyndicReseauGCLID=1';		
							}
						} else { // Si referer inconnu
							$Referer = 'Unknown Referer&googlesyndicReseauGCLID=1'; 	
						}
						$Keyword = mysql_real_escape_string(trim(search_keyword_in($Referer))); 	//URL - if in $Referer "googlesyndicReseauGCLID=1" renvoi url à la place avec googlesyndicReseauGCLID=1, si keyword renvoi keyword
	
					} else {
						$Keyword = $Keyword.' (AdWords Keyword)'; // Tag the keyword - MSG_ADWORDS_KEYWORD no language here
						
						if (strstr($Referer,'?')) { // si ? existe déjà mettre & 
							$Referer = $Referer. '&googlesyndicKeywordGCLID=1'; 
						} else {
							$Referer = $Referer. '?googlesyndicKeywordGCLID=1';		
						}
					}
				
				} elseif (strstr($Referer, '/aclk')) { // Si le Marquage automatique du compte Google n'est pas activé (Mais ne comptabilise pas tout) - A voir si fiable
					
					if(trim($Keyword <> '')){
						$Keyword = $Keyword.' (AdWords Keyword)'; //MSG_ADWORDS_KEYWORD no language here		
					}
									
					if (strstr($Referer,'?')) { // si ? existe déjà mettre &
						$Referer = $Referer. '&googlesyndicKeywordACLK=1'; 
					} else {
						$Referer = $Referer. '?googlesyndicKeywordACLK=1';		
					}
	
				} else { //Normal - No AdWords Google syndication
					$Keyword = mysql_real_escape_string(trim(@search_keyword_in($Referer))); 	//PHP5.4 (notice)
				}
				//------------------------------------------

					//-------------------- url mot clé images google (New)------------------
					$query = ''; // PHP5.4 (notice)
					$url = parse_url($Referer);
					if(isset($url["query"])) { $query = $url["query"];  }
					if(isset($url["host"])) { $host = $url["host"]; } else { $host = ''; }
					if(isset($query)){ parse_str($query); }

					if (isset($tbm) && $tbm == 'isch') { 
						if(isset($sout) && $sout == 1 || isset($prev)) { // Ancienne version Google Images - Lien en bas de page Google images "Passer à l'ancienne verion"
							// Ancienne version de Google Images (&sout=1) --> Enverra directement sur la bonne page
							$format_Referer = explode('&prev=',$Referer); 
							$Referer = 'http://'.$host.$format_Referer[1];
						
							$url = parse_url($Referer);
							if(isset($url["query"])) { $query = $url["query"];  }
							if(isset($url["host"])) { $host = $url["host"]; } else { $host = ''; }
							if(isset($query)){ parse_str($query); }
							
							$q = strtr($q, " ", "+"); //replace all space to '+' // Nécessaire ?
							//On re-format Referer et On tri dans main.php - $hl = lang, &q = keyword, $page = page image, $start = start page image (hold version),$sout =(if 1 hold ver Google image) , $ved = position image (new ver google image)
							$Referer = stripslashes('http://'.$host.'/search?tbm=isch&hl='.$hl.'&q='.$q.'&start='.$start.'&sout=1'); // On retombe sur hold ver Google Images --> &sout=1
							$Keyword = mysql_real_escape_string(trim(search_keyword_in($Referer))); 	//Keyword
						} elseif (isset($sout) && $sout <> 1) {
							// ----- 2011-10-21 new Google image --------
							// version standard Google Images 08-2011 --> Toutes les images sont affichées sur une même page et les numéros de page sont fictifs 
							$q = strtr($q, " ", "+"); //replace all space to '+'
							//On re-format Referer et On tri dans main.php - $hl = lang, &q = keyword, $page = page image, $start = start page image (hold version),$sout =(if 1 hold ver Google image) , $ved = position image (new ver google image)
							$Referer = stripslashes('http://'.$host.'/search?tbm=isch&hl='.$hl.'&q='.$q.'&page='.$page.'&start='.$start.'&sout='.$sout.'&ved='.$ved);
							//---------------------------------------------------
						} 
						$host_referer = $host. ' Google Images';
					} elseif (strstr($host,'images.yandex')) { 
						$host_referer = $host. ' Yandex Images';
					} else {
						$host_referer = mysql_real_escape_string($host_referer); // referer of keyword					
					}
					//----------------------------------------------------------------------
		
					// Si new $cp est définit plus haut, sinon auto incremente par la table
					$sql1 = "insert into ".TABLE_UNIQUE_VISITOR." values('".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','".mysql_real_escape_string($Referer)."','".$remote_adrr_ip."','".$date_now."','".mysql_real_escape_string($reverse_dns)."','".$cp."','".$country."','1');";
					$result = mysql_query($sql1) or die('Erreur 1 SQL! '.$sql1.'<br>'.mysql_error());  
		
					$result = mysql_query("select max(code) as cd from ".TABLE_UNIQUE_VISITOR."");
					$last_code = mysql_fetch_array($result);
					$sql = "insert into ".TABLE_PAGE_VISITOR." values ('".$last_code['cd']."','".$nom_page."','1','".$heure."');";
					$result = mysql_query($sql) or die('Erreur  2 SQL! '.$sql.'<br>'.mysql_error()); 
		
					//------------------------- TABLE_DAYS_PAGES ---------------------------
					//Visiteur nouveau --> page déjà vue aujourd'hui ?
					$result = @mysql_query("select visited_pages from ".TABLE_DAYS_PAGES." where pages_name='".$nom_page."' and date='".$date_now."'"); 
					$row_day = @mysql_fetch_array($result);
					if ($row_day['visited_pages']) {					
						$requete = "update ".TABLE_DAYS_PAGES." set visitors=visitors+1, visited_pages=visited_pages+1 where pages_name='".$nom_page."' and date='".$date_now."'";
						@mysql_query($requete);
					} else {
						$sql_day_pages = "insert into ".TABLE_DAYS_PAGES." (date, pages_name, visitors, visited_pages) values('".$date_now."','".$nom_page."','1','1');";
						@mysql_query($sql_day_pages);  
					}
					//----------------------------------------------------------------------
					//#################################################################################################################
					//--------------- MySQL Write Keyword & Referers - Update table day and monthly 
	
					if( $Keyword <> 'TOTAL_VISITS') { //'TOTAL_VISITS' car au cas où visitor inject keyword = 	TOTAL_VISITS
						if (!$host_referer) { // si referer - on comptabilise aussi les referers dans la table (tjrs 'TOTAL_VISITS') 					
							$host_referer = 'MSG_UNKNOWN_OR_DIRECT';
						}
						//--------------------------------------------------------------------------
						//Daily - Test par 'TOTAL_VISITS' si ce referer est déjà présent pour aujourd'hui dans la table 
						$result = @mysql_query("select nb_item from ".TABLE_DAYS_KEYWORDS." where host_referer='".$host_referer."' and keyword='TOTAL_VISITS' and date='".$date_now."'"); 
						$row_item_day = @mysql_fetch_array($result);
						
						if(!$row_item_day['nb_item']) { // Ce referer n'existe pas pour aujourd'hui --> Insert ce nouveau referer
							$sql_day_keywords = "insert into ".TABLE_DAYS_KEYWORDS." (date, host_referer, keyword, nb_item) values('".$date_now."','".$host_referer."','TOTAL_VISITS','1');";
							@mysql_query($sql_day_keywords); 
							
							if(trim($Keyword)) { // Si l'url contient un keyword --> Insert le 1er keyword pour ce referer 
								$sql_monthly_keywords = "insert into ".TABLE_DAYS_KEYWORDS." (date, host_referer, keyword, nb_item) values('".$date_now."','".$host_referer."','".$Keyword."','1');";
								@mysql_query($sql_monthly_keywords); 
							}
						} else { // Ce referer exist déjà
							
							$result = @mysql_query("select nb_item from ".TABLE_DAYS_KEYWORDS." where host_referer='".$host_referer."' and keyword='".$Keyword."' and date='".$date_now."'"); 
							$row_item_day = @mysql_fetch_array($result);
							if($row_item_day['nb_item']) {
								// +1 sur nb_item pour ce referer et ce TOTAL_VISITS
								$requete = "update ".TABLE_DAYS_KEYWORDS." set nb_item=nb_item+1 where host_referer='".$host_referer."' and keyword='TOTAL_VISITS' and date='".$date_now."'";
								@mysql_query($requete);
	
								if(trim($Keyword)) { //si keyword non vide --> +1 sur nb_item pour ce referer et ce keyword 
									$requete = "update ".TABLE_DAYS_KEYWORDS." set nb_item=nb_item+1 where host_referer='".$host_referer."' and keyword='".$Keyword."' and date='".$date_now."'";
									@mysql_query($requete);
								}
							} else { // Nouveau keyword pour ce referer
								// +1 sur nb_item pour ce referer et ce TOTAL_VISITS
								$requete = "update ".TABLE_DAYS_KEYWORDS." set nb_item=nb_item+1 where host_referer='".$host_referer."' and keyword='TOTAL_VISITS' and date='".$date_now."'";
								@mysql_query($requete);
								
								if(trim($Keyword)) { // Insert le nouveau keyword
									$sql_monthly_keywords = "insert into ".TABLE_DAYS_KEYWORDS." (date, host_referer, keyword, nb_item) values('".$date_now."','".$host_referer."','".($Keyword)."','1');";
									@mysql_query($sql_monthly_keywords); 
								}
							}
						} // end if(!preg_match($AllBots, $_S
						//--------------------------------------------------------------------------
	
						// Monthly - Test par 'TOTAL_VISITS' si ce referer est déjà présent pour ce mois dans la table 
						$result = @mysql_query("select nb_item from ".TABLE_MONTHLY_KEYWORDS." where host_referer='".$host_referer."' and keyword='TOTAL_VISITS' and date='".$MonthYear."'"); 
						$row_monthly_keyword = @mysql_fetch_array($result);
						
						if(!$row_monthly_keyword['nb_item']) { //Ce referer n'existe pas --> Insert ce nouveau referer
							$sql_monthly_keywords = "insert into ".TABLE_MONTHLY_KEYWORDS." (date, host_referer, keyword, nb_item) values('".$MonthYear."','".$host_referer."','TOTAL_VISITS','1');";
							@mysql_query($sql_monthly_keywords); 
							
							if($Keyword) { // Si l'url contient un keyword --> Insert le 1er keyword pour ce referer 
								$sql_monthly_keywords = "insert into ".TABLE_MONTHLY_KEYWORDS." (date, host_referer, keyword, nb_item) values('".$MonthYear."','".$host_referer."','".($Keyword)."','1');";
								@mysql_query($sql_monthly_keywords); 
							}
						} else { // Ce referer exist déjà
							
							$result = @mysql_query("select nb_item from ".TABLE_MONTHLY_KEYWORDS." where host_referer='".$host_referer."' and keyword='".($Keyword)."' and date='".$MonthYear."'"); 
							$row_monthly_keyword = @mysql_fetch_array($result);
							if($row_monthly_keyword['nb_item']) {
								// +1 sur nb_item pour ce referer et ce TOTAL_VISITS
								$requete = "update ".TABLE_MONTHLY_KEYWORDS." set nb_item=nb_item+1 where host_referer='".$host_referer."' and keyword='TOTAL_VISITS' and date='".$MonthYear."'";
								@mysql_query($requete);
	
								if(trim($Keyword)) { //si keyword non vide --> +1 sur nb_item pour ce referer et ce keyword 
									$requete = "update ".TABLE_MONTHLY_KEYWORDS." set nb_item=nb_item+1 where host_referer='".$host_referer."' and keyword='".$Keyword."' and date='".$MonthYear."'";
									@mysql_query($requete);
								}
							} else { // Nouveau keyword pour ce referer
								// +1 sur nb_item pour ce referer et ce TOTAL_VISITS
								$requete = "update ".TABLE_MONTHLY_KEYWORDS." set nb_item=nb_item+1 where host_referer='".$host_referer."' and keyword='TOTAL_VISITS' and date='".$MonthYear."'";
								@mysql_query($requete);
								
								if(trim($Keyword)) { // Insert le nouveau keyword
									$sql_monthly_keywords = "insert into ".TABLE_MONTHLY_KEYWORDS." (date, host_referer, keyword, nb_item) values('".$MonthYear."','".$host_referer."','".$Keyword."','1');";
									@mysql_query($sql_monthly_keywords); 
								}
							}
						}
					} // End if( $Keyword <> 'TOTAL_VISITS')
					//-----------------------------------------------------------------

		} // END if ($row['code'] <> ""){ //Ce visiteur est déjà passé aujourd'hui
						
	} else { // END if(!preg_match($AllBots, $_SERVER['HTTP_USE --> ###################################### BOT BOT BOT ###############################################

		//Certains user agents comportent plus de 255 (512 if varchar(512)) caractères et donc agent = n'est pas OK => multiple visites pour un seul
		$result = mysql_query("select * from ".TABLE_UNIQUE_BOT." where date like '".$jour."' and ip='".$remote_adrr_ip."' and agent='".substr($_SERVER['HTTP_USER_AGENT'], 0, 512)."'");
		$row = mysql_fetch_array($result);

		if ($row['code'] <> ""){ // Ce bot est déjà passé aujourd'hui
	
			@mysql_query("update ".TABLE_UNIQUE_BOT." set visits=visits+1 where code='".$row['code']."'");
	
			$verif = @mysql_query("select * from ".TABLE_PAGE_BOT." where code='".$row['code']."' and page='".$nom_page."'");
			$verif_row = @mysql_fetch_array($verif); // warning possible sur cette ligne et quand ?

			if ($verif_row['page'] <> ""){ // Ce bot est déjà passé aujourd'hui ET la page a déjà été visitée
				$requete = "update ".TABLE_PAGE_BOT." set visits=visits+1 where code='".$row['code']."' and page='".$nom_page."'";
				mysql_query($requete);
			} else { // Ce bot est déjà passé aujourd'hui ET visite une nouvelle page (pour aujourd'hui)
				mysql_query("insert into ".TABLE_PAGE_BOT." values ('".$row['code']."','".$nom_page."','1','".$heure."')");
			}
		
		} else { // ---------------------------------------- BOT NOUVEAU -------------------------------------------------------------------
	
			// Détermination du code permettant la jointure entre la TABLE_UNIQUE_BOT et TABLE_PAGE_BOT
			$result= mysql_query("select max(code) as cd from ".TABLE_UNIQUE_BOT.""); 
		
			$dateY = date('Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			$dateM = date('m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			if($last_code = mysql_fetch_array($result)){
				$the_last = $last_code['cd'];
				$anneemois = substr($the_last,0,4)."/".substr($the_last,4,2);
				$verif_date = date('Y/m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		
				if($anneemois == $verif_date){
					$cp = ""; //sera auto incrémente par la table MySQL 
				} else {
					$cp = $dateY.$dateM."00000001"; // est fixé et ne sera pas auto incremente par la table MySQL
				}
			} else {
				$cp = $dateY.$dateM."00000001";
			}

			//------------------------ Détermination du pays ------------------------------------------
			if(!function_exists('geoip_open')) { //To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in if in page
				require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
			}

			$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
			$record_name = geoip_country_name_by_addr($handle, $remote_adrr_ip);
			@geoip_close($handle);
			
			if ($record_name){ 
				if($record_name == 'Kazakstan') { $record_name = 'Kazakhstan'; } // if geoip.inc already loaded, but with an older version that contains the error: Kazakstan for Kazakhstan
				$country = $record_name; 
			} else {
				$country = '';
			}
			//-----------------------------------------------------------------------------------------
				
			if(isset($_SERVER['HTTP_REFERER'])) {
				$Referer = urldecode($_SERVER['HTTP_REFERER']);
			} else {
				$Referer = '';
			}

			//------------------------------------------
			// Si new $cp est définit plus haut, sinon auto incremente par la TABLE_UNIQUE_BOT
			// agent - referer - IP - date - reverse_dns - code - country - visits
			$sql1 = "insert into ".TABLE_UNIQUE_BOT." values('".$_SERVER['HTTP_USER_AGENT']."','".mysql_real_escape_string($Referer)."','".$remote_adrr_ip."','".$date_now."','".mysql_real_escape_string($reverse_dns)."','".$cp."','".$country."','1');";
			$result = mysql_query($sql1) or die('Erreur 1 SQL! '.$sql1.'<br>'.mysql_error());  

			$result = mysql_query("select max(code) as cd from ".TABLE_UNIQUE_BOT."");
			$last_code = mysql_fetch_array($result);
			$sql = "insert into ".TABLE_PAGE_BOT." values ('".$last_code['cd']."','".$nom_page."','1','".$heure."');";
			$result = mysql_query($sql) or die('Erreur 2 SQL! '.$sql.'<br>'.mysql_error()); 
		}
	}

} else { ############################## IS BAD USER AGENT OR UNKOWN and not empty #######################################

		$Bad_User_Agent_query = mysql_query("select * from ".TABLE_BAD_USER_AGENT." where user_agent='".substr($_SERVER['HTTP_USER_AGENT'], 0, 255)."'"); 
		$Bad_User_Agent = mysql_fetch_array($Bad_User_Agent_query);
		$type = $Bad_User_Agent['type'];

		if(isset($_SERVER['HTTP_REFERER'])) {
			$Referer = urldecode($_SERVER['HTTP_REFERER']);
		} else {
			$Referer = '';
		}

		if($bad_by_reverseDNS_detected) {
			$type = 'D';
		} elseif ($bad_by_IP_detected) {
			$type = 'E';
		} elseif ($bad_by_referer_detected) {
			$type = 'F';
		} elseif($bad_by_inject_detected) {
			$type = 'Z';		
		}
		if($url_inject) {
			$Referer .= '<br>Request URL : '.$url_inject;
		}
		
		$result = mysql_query("select * from ".TABLE_UNIQUE_BAD_AGENT." where date like '".$MySQL_date_now."' and ip='".$remote_adrr_ip."' and agent='".substr($_SERVER['HTTP_USER_AGENT'], 0, 255)."'");

		$row = mysql_fetch_array($result);

		if ($row['code'] <> ""){ // Ce visiteur utilisant ce bad user agent est déjà passé aujourd'hui
	
			@mysql_query("update ".TABLE_UNIQUE_BAD_AGENT." set visits=visits+1 where code='".$row['code']."'");
	
			$verif = @mysql_query("select * from ".TABLE_PAGE_BAD_AGENT." where code='".$row['code']."' and page='".$nom_page."'");
			$verif_row = @mysql_fetch_array($verif); 

			if ($verif_row['page'] <> ""){ // Ce visiteur utilisant ce bad user agent ET la page a déjà été visitée
				$requete = "update ".TABLE_PAGE_BAD_AGENT." set visits=visits+1 where code='".$row['code']."' and page='".$nom_page."'";
				mysql_query($requete);
			} else { // Ce visiteur est déjà passé aujourd'hui ET visite une nouvelle page (pour aujourd'hui)
				mysql_query("insert into ".TABLE_PAGE_BAD_AGENT." values ('".$row['code']."','".$nom_page."','1','".$heure."')");
			}
		
		} else { // ---------------------------------------- VISITEUR BAD USER AGENT NOUVEAU -------------------------------------------------------------------
	
			// détermination du code permettant la jointure entre la TABLE_PAGE_BAD_AGENT et TABLE_PAGE_BAD_AGENT
			$result= mysql_query("select max(code) as cd from ".TABLE_UNIQUE_BAD_AGENT.""); 
		
			$dateY = date('Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			$dateM = date('m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
			if($last_code = mysql_fetch_array($result)){
				$the_last = $last_code['cd'];
				$anneemois = substr($the_last,0,4)."/".substr($the_last,4,2);
				$verif_date = date('Y/m',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		
				if($anneemois == $verif_date){
					//sera auto incrémente par la table MySQL 
					$cp = ""; 
				} else {
					$cp = $dateY.$dateM."00000001"; // est fixé et ne sera pas auto incremente par la table MySQL
				}
			} else {
				$cp = $dateY.$dateM."00000001";
			}

			//------------------------ Détermination du pays ------------------------------------------
			//http://geolite.maxmind.com/download/geoip/api/php/ChangeLog --> 1.8	2009-04-02	* Fixed spelling of Kazakhstan, was Kazakstan
			if(!function_exists('geoip_open')) { // To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in if in page
				require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
			}

			$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
			$record_name = geoip_country_name_by_addr($handle,$remote_adrr_ip);
			@geoip_close($handle);

			if ($record_name){ 
				if($record_name == 'Kazakstan') { $record_name = 'Kazakhstan'; } // if geoip.inc already loaded, but with an older version that contains the error: Kazakstan for Kazakhstan
				$country = $record_name; 
			} else {
				$country = '';
			}

			//------------------------------------------
			// Si new $cp est définit plus haut, sinon auto incremente par la table
			$sql1 = "insert into ".TABLE_UNIQUE_BAD_AGENT." values('".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','".$type."', '".mysql_real_escape_string($Referer)."','".$remote_adrr_ip."','".$MySQL_date_now."','".mysql_real_escape_string($reverse_dns)."','".$cp."','".$country."','1');";
			$result = mysql_query($sql1) or die('Erreur 1 SQL! '.$sql1.'<br>'.mysql_error());  
		
			$result = mysql_query("select max(code) as cd from ".TABLE_UNIQUE_BAD_AGENT."");
			$last_code = mysql_fetch_array($result);
			$sql = "insert into ".TABLE_PAGE_BAD_AGENT." values ('".$last_code['cd']."','".$nom_page."','1','".$heure."');";
			$result = mysql_query($sql) or die('Erreur  2 SQL! '.$sql.'<br>'.mysql_error()); 
			//----------------------------------------------------------------------

	} //End } else { //---------- VISITEUR NOUVEAU --
			//#################################################################################################################

} // End } else { // EST UN BAD USER AGENT OR UNKOWN mais non vide
	
	
	//################################################# END ######################################################################

//mysql_close($db_allmystats);

//Restaure le nom de la base SQL initale				
if($current_database <> '' && $current_database != @mysql_query("SELECT DATABASE()")){ // Add $current_database <> '' because is initialized with an empty string (PHP5.4)
	mysql_select_db($current_database);
}

		if($time_test == true) {
			$end = (float) array_sum(explode(' ',microtime()));  
			echo '<pre>										Visitor Traitement : '.sprintf("%.4f", $end-$start) . ' sec</pre>';
		}

?>