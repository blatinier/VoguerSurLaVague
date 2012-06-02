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
/*
Vérifie si la variable $UTC dans config_allmystats.php est bien réglée

*/

require "config_allmystats.php";

		echo "<center><strong>Test configuration UTC</center></strong><br>";

		$date = date('d/m/Y',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));
		$heure = date('H:i',strtotime($UTC." hours", strtotime(date("Y-m-d H:i:s"))));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Allmystats - V&eacute;rification de la configuration UTC</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Style1 {font-size: 14px}
-->
</style>
</head>
<body>

<? 
		echo "Actuellement \$UTC = \"".$UTC."\"; dans config_allmystats.php<br><br>";
				
?>
		<table border="0" align="center" cellpadding="7">
		  <tr>
			<td nowrap>
			<?
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
							<form action="<?PHP_SELF?>" method="post" name="Testutc">
<?		
								echo "Aide au réglage UTC<br>".$MSG_JETLAG." UTC"; ?>
								<select name="T_UTC">
								  <option value="<? echo $T_UTC ?>" selected><? echo $T_UTC ?></option>
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
</body>
</html>
