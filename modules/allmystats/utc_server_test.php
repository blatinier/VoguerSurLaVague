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
/*
Vérifie si la variable $UTC dans config_allmystats.php est bien réglée

*/
	// ---------------- Should not be called directly -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/utc_server_test.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

require "config_allmystats.php";

		echo "<div align=\"center\"><strong>Test configuration UTC</strong></div><br>";

		//$date = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		//$heure = date('H:i',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Allmystats - V&eacute;rification de la configuration UTC</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<div align="center">
<?php 
		echo "Actuellement \$UTC = \"".$UTC."\"; dans config_allmystats.php<br><br>";

?>
		<table border="0" align="center" cellpadding="7">
		  <tr>
			<td nowrap="nowrap" style="font-size:10px; vertical-align:top;">
			<?php
				echo '------------------------------------------------------<br>';
				echo MSG_INSTALL_TIME_ZONE_SERVER.'<br>';

				if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) {
					echo "<strong>".date_default_timezone_get().'</strong><br><br>';
				} elseif (ini_get('date.timezone')) {
					echo "<strong>".ini_get('date.timezone').'</strong><br><br>';
				}

				$date = date('d/m/Y');
				$heure = date('H:i');
				echo MSG_INSTALL_DATE_TIME_SERVER.'<br>';
				echo MSG_HOUR." : <strong>".$date."</strong><br>";
				echo "Heure : <strong>".$heure."</strong><br>";
				echo '------------------------------------------------------<br>';
				
			?>
			</td></tr>
			<tr><td>				
			<?php				
				
				if(isset($_POST["T_UTC"])) {
					$T_UTC = $_POST["T_UTC"];
				}
				if(!isset($T_UTC)) { $T_UTC = $UTC; }
				$date = date('d/m/Y',strtotime($T_UTC." hours", strtotime(date("Y-m-d H:i:s"))));
				$heure = date('H:i',strtotime($T_UTC." hours", strtotime(date("Y-m-d H:i:s"))));
				echo "<font color=#FF0000>".MSG_INSTALL_SAME_DATE."</font><br>";
				echo "<big>Date : <strong>".$date."</strong></big><br>";
				echo "<Big>".MSG_HOUR." : <strong>".$heure."</strong></big>
				<br><br>
				Dans le cas contraire &eacute;diter le fichier <strong>config_allmystats.php</strong> et r&eacute;gler \$UTC<br>
				Otherwise, edit the file <strong>config_allmystats.php</strong> and set \$UTC <br>";	
			?>
			</td>
			<td style="font-size:10px; vertical-align:top;">
				<script type="text/javascript">
				// Get current date
				var now = new Date();
				// List the months
				var months = new Array('01','02','03','04','05','06','07','08','09','10','11','12');
				// What day number is it
				var date = ((now.getDate()<10) ? "0" : "")+ now.getDate();
				// Convert year to four figure format
				function y2k(number){return (number < 1000) ? number + 1900 : number;}

				today =  date + "/" + months[now.getMonth()] + "/" + (y2k(now.getYear())) ;
								
				Today = new Date;
				Hour = Today.getHours();
				if(Hour<10) { Hour = "0" + Hour ; }
				Min = Today.getMinutes();

				PCDate = "<strong><?php echo MSG_INSTALL_YOURPC_TIME; ?></strong><br>" + today + " <br> " + Hour + ":" + Min;
				</script>
											
				<script type="text/javascript">
					document.write(PCDate);
				</script>	
			
			
			</td>


		  </tr>
		</table>
							<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="Testutc">
<?php		
								echo "Aide au réglage UTC<br>".MSG_JETLAG." \$UTC = "; ?>
								<select name="T_UTC">
								  <option value="<?php echo $T_UTC ?>" selected><?php echo $T_UTC ?></option>
								  <option value="-1">-1</option>
								  <option value="-2">-2</option>
								  <option value="-3">-3</option>
								  <option value="-4">-4</option>								  
								  <option value="-5">-5</option>								  
								  <option value="-6">-6</option>								  
								  <option value="-7">-7</option>								  
								  <option value="-8">-8</option>								  
								  <option value="-9">-9</option>
								  <option value="-10">-10</option>
								  <option value="-11">-11</option>								  
								  <option value="0">0</option>
								  <option value="+1">+1</option>								  
								  <option value="+2">+2</option>								  
								  <option value="+3">+3</option>								  
								  <option value="+4">+4</option>								  
								  <option value="+5">+5</option>								  
								  <option value="+6">+6</option>								  
								  <option value="+7">+7</option>							  
								  <option value="+8">+8</option>								  
								  <option value="+9">+9</option>								  
								  <option value="+10">+10</option>								  
								  <option value="+11">+11</option>								  
								  <option value="+12">+12</option>								  
								</select>
								<input type="hidden" name="type" value="test_jetlag">
								<input type="submit" name="sublmit_Test_utc" value="Test UTC">
							</form>
		<p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
</div>
</body>
</html>
