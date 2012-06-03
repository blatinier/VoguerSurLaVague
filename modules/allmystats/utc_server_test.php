<?php 
/*
  -------------------------------------------------------------------------
 AllMyStats V1.75 - Statistiques site web - Web traffic analysis
 -------------------------------------------------------------------------
 Copyright (C) 2008-2010 - Herve Seywert
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------
*/
/*
Vérifie si la variable $UTC dans config_allmystats.php est bien réglée

*/
	// ---------------- Ne doit pas être appelé directement -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/utc_server_test.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

require "config_allmystats.php";

		echo "<div align=\"center\"><strong>Test configuration UTC</strong></div><br>";

		$date = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$heure = date('H:i',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Allmystats - V&eacute;rification de la configuration UTC</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
.Style1 {font-size: 14px}
-->
</style>
</head>
<body>
<div align="center">
<?php 
		echo "Actuellement \$UTC = \"".$UTC."\"; dans config_allmystats.php<br><br>";
				
?>
		<table border="0" align="center" cellpadding="7">
		  <tr>
			<td nowrap>
			<?php
				$T_UTC = $_POST["T_UTC"];
				if(!$T_UTC ) { $T_UTC = $UTC; }
				$date = date('d/m/Y',strtotime($T_UTC." hours", strtotime(date("Y-m-d H:i:s"))));
				$heure = date('H:i',strtotime($T_UTC." hours", strtotime(date("Y-m-d H:i:s"))));
				echo "<font color=#FF0000>La date et l'heure doivent &ecirc;tre les m&ecirc;mes que chez vous</font><br>";
				echo "<big>Date = <strong>".$date."</strong></big><br>";
				echo "<Big>Heure = <strong>".$heure."</strong></big><br>
				Dans le cas contraire &eacute;diter le fichier <strong>config_allmystats.php</strong> et r&eacute;gler \$UTC<br>";	
			?>
			</td>
		  </tr>
		</table>
							<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="Testutc">
<?php		
								echo "Aide au réglage UTC<br>".MSG_JETLAG." UTC"; ?>
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
