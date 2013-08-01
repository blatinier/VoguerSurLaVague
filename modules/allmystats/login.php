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

	// ----------------------------- Login -------------------------------------------------------

		include_once('application_top.php');
		require ("config_allmystats.php");
		require_once("includes/filename.php");

		if(!$error_login) {
			$error_login = 5;
		}

		if(isset($_SESSION['errorlog']) && $_SESSION['errorlog'] == 'BLOCKED') {
			echo '<p align="center"><font color="#FF0000"><strong>
			Multiple erreurs login, vous devez fermer complètement votre navigateur web.<br>
			Multiple login errors, you must close your browser completely.<br>
			</strong></font></p>';
			session_unset();	//remove all the variables in the session
			$_SESSION['errorlog'] = 'BLOCKED';
			exit;
		} elseif (isset($_SESSION['errorlog']) && $_SESSION['errorlog'] >= $error_login){

			// ----------------- Write Multiple error loging ------------------------------
			$file_log_name = 'loging_error.php';
			$Fnm = 'cache/'.$file_log_name;
			
			if(!$nb_max_HistoRecord_log) {
				$nb_max_HistoRecord_log = 30;
			}
			
			if(!is_file($Fnm)) {
				fopen($Fnm,"w");
			}

			$fp = fopen($Fnm,"r");
				
			unset($old_Tab_log);
			while (!feof($fp)) {
				$old_Tab_log[] = fgets($fp, 4096);
			}
			fclose($fp);

			$end_header = '';
			for($nb = 0; $nb < count($old_Tab_log); $nb++){ // Num line end head
				if (strstr($old_Tab_log[$nb],"?>")) {
					$end_header = $nb + 2;
					break;
				}
			}
								
			if(!function_exists('geoip_open')) { //To avoid Fatal error: Cannot redeclare geoip_load_shared_mem() (previously declared in if in page
				require_once(dirname(__FILE__).'/lib/geoip/geoip.inc');
			}

			$handle = geoip_open(dirname(__FILE__)."/lib/geoip/dat/GeoIP.dat", GEOIP_STANDARD);
			$record_name = geoip_country_name_by_addr($handle, $_SERVER['REMOTE_ADDR']);
			@geoip_close($handle);
			
			$histo_data = '';
			for($nb = $end_header; $nb < count($old_Tab_log); $nb++){ // 
				if($nb < ($nb_max_HistoRecord_log + $end_header) - 1) { // - 1 new line - Nb max save login histo
					$histo_data .= $old_Tab_log[$nb];
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
			// ----------------------------------------------------------------------------
			echo '<p align="center"><font color="#FF0000"><strong>
			Multiple erreurs login, vous devez fermer complètement votre navigateur web.<br>
			Multiple login errors, you must close your browser completely.<br>
			</strong></font></p>';
			session_unset();	//remove all the variables in the session
			$_SESSION['errorlog'] = 'BLOCKED';
			exit;
		}
		
		if(!isset($_SESSION['userlogin'])) { $_SESSION['userlogin'] = ''; }
		if(!isset( $_SESSION['userpass'])) {  $_SESSION['userpass'] = ''; }

		if($user_login != $_SESSION['userlogin'] || $passwd != $_SESSION['userpass'])	{
				?>
				<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
				"http://www.w3.org/TR/html4/loose.dtd">
				<html>
				<head>
				<title>AllMyStats - <?php echo $site; ?> Web Traffic Analysis</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<meta name="Description" content="AllMystats - Web Traffic Analysis solution, statistics and analysis for Web sites in real-time, traffic report of website to track visitor behavior." />
				
				<link rel="stylesheet" type="text/css" href="stylesheet.css">
				</head>
				<body>
				<?php

				include(FILENAME_DISPLAY_HEADER);

				?>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<form action="<?php echo FILENAME_DO_LOGIN; ?>" method="POST" name="login"><br />
				<table align="center" border="1" cellspacing="0" cellpadding="10">
				<tr><td>
					<table align="center" border="0" cellspacing="0" cellpadding="4">
					<tr>		
					<td><?php echo 'Login: '; ?></td><td><input name="userlogin" value="<?php echo $_SESSION['userlogin']; ?>" type='text' maxlength='20' size='20'/></td></tr>
					<tr><td></td><td></td></tr>
					<tr><td><?php echo 'Password: ' ; ?></td><td><input name="userpass" value="<?php echo $_SESSION['userpass']; ?>" type='password' maxlength='20' size='20'/></td></tr>
					<tr><td colspan="2" align="center"><input name="submitlogin" type="submit" value="OK"></td></tr>
					</table>
				</td></tr>
				</table>
				</form>
				<br><br><br><br>
				<table border="0" align="center" cellpadding="0" cellspacing="0">
				  <tr>
					<td align="center"><?php include(FILENAME_DISPLAY_FOOTER); ?></td>
				  </tr>
				</table>
			</body>
			</html>
			<?php
			exit;
		 } else {
			//echo 'TEST: is logged';
		 }
?>