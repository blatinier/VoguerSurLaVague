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
	include(dirname(__FILE__).'/includes/config_error_reporting.php');

	// PHP5.4 Suppress DateTime warnings (if not set in php.ini) => date_default_timezone_set -> UTC
	if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) {
		date_default_timezone_set(@date_default_timezone_get());
	}

	include_once('application_top.php');
	require ('config_allmystats.php');
		
	// Create /cache directory if not exist
	if (!is_dir(dirname(__FILE__)."/cache")) {
		mkdir (dirname(__FILE__)."/cache");
	}

	if(!isset($histo_data)) { $histo_data = ''; }
	if(!isset($end_header)) { $end_header = ''; }
	
//--------------------------- Login ---------------------------------------------
		//get values
		if(isset($_POST['userlogin']) && isset($_POST['userpass'])) {	
			$_SESSION['userlogin'] = $_POST['userlogin'];
			$_SESSION['userpass'] = $_POST['userpass'];
		} else {
			$_SESSION['userlogin'] = '';
			$_SESSION['userpass'] = '';
		}

		if($user_login == $_SESSION['userlogin'] && ($passwd == $_SESSION['userpass'] || $passwd == md5($_SESSION['userpass']))	){
			$_SESSION['userlogin'] = $user_login;
			$_SESSION['userpass'] = $passwd;

			// ----------------- Write log loging ------------------------------
			$file_log_name = 'loging_log.php';
			$Fnm = 'cache/'.$file_log_name;

			if(!$nb_max_HistoRecord_log) {
				$nb_max_HistoRecord_log = 30;
			}
			
			if(!function_exists('geoip_open')) { // To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in if in page
				require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
			}
			$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
			$record_name = geoip_country_name_by_addr($handle, $_SERVER['REMOTE_ADDR']);
			@geoip_close($handle);

			if(!is_file($Fnm)) {
				fopen($Fnm,"w");
			}

			$fp = fopen($Fnm,"r");
				
			unset($old_Tab_log);
			while (!feof($fp)) {
				$old_Tab_log[] = fgets($fp, 4096);
			}
			fclose($fp);

			if(sizeof($old_Tab_log) > 0) {
				for($nb = 0; $nb < count($old_Tab_log); $nb++){ // nb line php header
					if (isset($old_Tab_log[$nb]) && strstr($old_Tab_log[$nb],"?>")) {
						$end_header = $nb + 2;
						break;
					}
				}
									
				for($nb = $end_header; $nb < count($old_Tab_log); $nb++){ 
					if($nb < ($nb_max_HistoRecord_log + $end_header) - 1) { // - 1 new line - Nb max save login histo
						$histo_data .= $old_Tab_log[$nb];
					}
				}
			}

			$content = "<?php\n";
			$content .= "// ---------- The file should not be called directly -----------\n";
			$content .= "if(strrchr(\$_SERVER['PHP_SELF'] , '/' ) == '/".$file_log_name."' ){\n"; 
			$content .= "\t exit;\n";
			$content .= "}\n";
			$content .= "// ------------------------------------------------------------\n";
			$content .= "?>\n\n";

			$content .= 'Date: '.date('Y-m-d').' '.date('H:i:s').' - IP: '.$_SERVER['REMOTE_ADDR'].' - Country: '.$record_name. "<br>\n"; // New line
			$content .= $histo_data;

			//Write 				
			if ( $file = fopen($Fnm, "w") ) {
				fwrite($file, $content);
				fclose($file);
			}
			// ----------------------------------------------------------

			//------------- Alexa & Google Page Rank --------------------
			 //php.ini must be allow_url_fopen = on for ALEXA, PageRank query
			@ini_set('allow_url_fopen', 1); // trying to put allow_url_fopen to 1

			if(@ini_get('allow_url_fopen')){
				require_once('includes/functions/'.FILENAME_GENERAL_FUNCTIONS);
				//ALEXA Query once by session
				$_SESSION['ALEXA'] = AlexaRanking($site);
						
				//PageRank Query once by session
				$url_image = 'includes/functions/pagerank/images/Img_1/%s.jpg';
				include_once('includes/functions/pagerank/class_pagerank.php');
				$PrG = CalculPagerank('http://'.$site);

				if (ctype_digit($PrG)){
					$_SESSION['PAGERANK'] = '<img src="'.sprintf($url_image,$PrG).'" alt="PageRank '.$PrG.'" title="PageRank '.$PrG.'"border="0" />';					
				} else {
					//$_SESSION['PAGERANK'] = 'err : '.$PrG;
					$_SESSION['PAGERANK'] = $PrG;
				}
			}
       		//----------------------------------------------------------
			//------------ clean MySQL tables allmystats ---------------
			// Par defaut on supprime toute les lignes ant�rieures � 12/2008 
			// ainsi que les lignes avec champs code en bug --> ex 8 , 16 

			require('includes/mysql_tables.php');

			mysql_connect($mysql_host,$mysql_login,$mysql_pass);
			mysql_select_db($mysql_dbnom);
			//Pour serveur php config windows-1251, il n'est pas nÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©cessaire de faire SET NAMES 'utf8 dans visiteurs.php (peut ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Âªtre que c'est dÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©finit dans la page principale ?
			//mais il faut le faire pour la lecture ici.(dans index_frame.php)
			//mysql_query("SET NAMES 'latin1'"); // OK  Default est en latin1 pour server en france si les tables sont en latin1
			mysql_query("SET NAMES 'utf8'"); // OK mais pas mettre de utf8 encode decode et les tables en utf8

			$year_max = 2010; //2008
			$del_date_max = $year_max.'1200000000'; 

			$result = mysql_query("delete from ".TABLE_UNIQUE_VISITOR." where code<'".$del_date_max."'") or die('Erreur SQL! TABLE_UNIQUE_VISITOR: '.$result.'<br>'.mysql_error()); ;
			$result = mysql_query("delete from ".TABLE_UNIQUE_BOT." where code<'".$del_date_max."'") or die('Erreur SQL! TABLE_UNIQUE_BOT: '.$result.'<br>'.mysql_error());

			$year_max = 2012; // 2013-04-16 - Clear bug date empty or bad format in TABLE_UNIQUE_BAD_AGENT
			$del_date_max = $year_max.'1200000000'; 
			$result = mysql_query("delete from ".TABLE_UNIQUE_BAD_AGENT." where code<'".$del_date_max."'") or die('Erreur SQL! TABLE_UNIQUE_BAD_AGENT: '.$result.'<br>'.mysql_error());
			// 2013-04-16 - Clear bug date empty in TABLE_UNIQUE_BAD_AGENT
			$result = mysql_query("delete from ".TABLE_UNIQUE_BAD_AGENT." where date=''") or die('Erreur SQL! TABLE_UNIQUE_BAD_AGENT: '.$result.'<br>'.mysql_error());

			$year_max = 2010; //2008
			$del_date_max = $year_max.'1200000000'; 
			$result = mysql_query("delete from ".TABLE_PAGE_VISITOR." where code<'".$del_date_max."'") or die('Erreur SQL! TABLE_PAGE_VISITOR: '.$result.'<br>'.mysql_error()); ;
			$result = mysql_query("delete from ".TABLE_PAGE_BOT." where code<'".$del_date_max."'") or die('Erreur SQL! TABLE_PAGE_BOT: '.$result.'<br>'.mysql_error());
			$result = mysql_query("delete from ".TABLE_PAGE_BAD_AGENT." where code<'".$del_date_max."'") or die('Erreur SQL! TABLE_PAGE_BAD_AGENT: '.$result.'<br>'.mysql_error());

			for($i = 1969; $i <= $year_max; $i++){
				$result = mysql_query("delete from ".TABLE_DAYS_PAGES." where date like '%".$i."'") or die('Erreur SQL! '.$result.'<br>'.mysql_error()); ;
				$result = mysql_query("delete from ".TABLE_MONTHLY_KEYWORDS." where date like '%".$i."'") or die('Erreur SQL! TABLE_MONTHLY_KEYWORDS: '.$result.'<br>'.mysql_error()); ;
				$result = mysql_query("delete from ".TABLE_DAYS_KEYWORDS." where date like '%".$i."'") or die('Erreur SQL! TABLE_DAYS_KEYWORDS: TABLE_DAYS_PAGES: '.$result.'<br>'.mysql_error()); ;
			}
	
			mysql_close();
       		//----------------------------------------------------------

			header("Location: ".FILENAME_INDEX_FRAME);
		} else {
			if(!isset($_SESSION['errorlog'])) {	$_SESSION['errorlog'] = 0; }
			$_SESSION['errorlog'] = $_SESSION['errorlog'] + 1;	
			?>
			<form action="<?php echo FILENAME_INDEX_FRAME; ?>" method="POST" name="loginout"><br />
			<table align="center" border="1" cellspacing="0" cellpadding="10">
				<tr><td align="center"><?php echo "Login ou mot de passe incorrecte"; ?></td></tr>
				<tr><td align="center"><input name="submitErrLog" type="submit" value="OK"></td></tr>
			</table>
			</form>
			<?php
		}
//------------------------------------------------------------------------------------------

?>