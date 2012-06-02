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
$version = "V1.39";

$when = $_POST["when"];
$type = $_POST["type"];

?>

<CENTER>
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->

      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <td width="30%" align="center" valign="middle"><span class="head_large">
		  		AllMyStats<br>
				<span class="head_small"><?php echo $version; ?></span></span></td>
          <TH class=TABLETITLE align="center">
				<div class="head"><? echo $MSG_TITRE; ?></div>
				<a class=SITENAME href="http://<? echo $site; ?>" target="_blank"><? echo $site; ?></a></TH>
		<? 		if ($_SESSION['userpass']) { ?>
          			<td align="center"><a href="logout.php">Déconnexion</a></td>
		<?
				}

				if($user_login==$_SESSION['userlogin'] && $passwd==$_SESSION['userpass'])	{
					 if (!$HTTP_COOKIE_VARS["AllMyStatsVisites"] == 'No record this') {?>
						<tr>
							<td colspan="3" align="center">
							<? echo $MSG_NO_COUNT_VISITS; ?>
							<form name="formlink" method="post" action="index_frame.php">
							  <input type="hidden" name="type"  value="MyVisitsTools">
							  <input class="submitDate" name="submit" type="submit" value="<? echo $MSG_NO_COUNT_VISITS_CLICHERE; ?>">
							</form>
							</td>
						</tr>
				<?	} 
				}

				if($_SESSION['call'] == false) {
					$test_ver = substr($version,1) + 0; //+0 pour mettre en numérique sinon chaine
					$last_ver = @file_get_contents("http://allmystats.wertronic.com/allmystats_check_ver.php?request=".$_SERVER['HTTP_HOST']."&cust_ver=".$test_ver."");
					$_SESSION['call'] = true;
					if ($last_ver>$test_ver) {
					echo '<tr>
						<td colspan="3" align="center">';
						echo "<font color = #CC0000>Nouvelle version disponible:</font><br>";
						echo '<a href="http://allmystats.wertronic.com/fr_download.php" target="_blank">Télécharger AllMyStats V'.$last_ver.'</a>';
					echo '</td>
					</tr>';	
					}
				}					
?>

		<TR>
        </TR>
</TBODY></TABLE>
      <!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
		<? 
		if ($type==""){ 
			echo "<SPAN CLASS=NAVNOHREF>".$MSG_ACCUEIL."</SPAN> "; 
		} else {
?>
			<form name="form1" method="post" action="index_frame.php">
			  <input type="hidden" name="when"  value="<? echo $when; ?>">
			  <input class="submit" name="detail_ref" type="submit" value="<? echo $MSG_ACCUEIL; ?>" alt="<? echo $MSG_ACCUEIL; ?>" >
			</form>
<?		}

		if ($type=="DetailsRobot"){ 
			echo "<SPAN CLASS=NAVNOHREF>".$BUTTON_DETAILS_ROBOTS."</SPAN> "; 
		} elseif ($type=="Allmystats_tools" || $type=="MyVisitsTools" || $type=="password" ||  $type=="add_crawler" ||  $type=="add_bad_user_agent") {

		} else {
?>
			<form name="formDetailsRobot" method="post" action="index_frame.php">
				<input type="hidden" name="type" value="DetailsRobot">
				<input type="hidden" name="when"  value="<? echo $when; ?>">
			    <input class="submit" name="submitDetailsRobot" type="submit" value="<? echo $BUTTON_DETAILS_ROBOTS; ?>" alt="<? echo $BUTTON_DETAILS_ROBOTS; ?>" title="<? echo $BUTTON_DETAILS_ROBOTS; ?>">
			</form>
<?
		} 
?>

<?
		if ($type=="cumul"){
			echo "<SPAN CLASS=NAVNOHREF>".$MSG_CUMUL."</SPAN> "; 
		} elseif ($type=="Allmystats_tools" || $type=="MyVisitsTools" || $type=="password" ||  $type=="add_crawler" ||  $type=="add_bad_user_agent") {
		
		} else {
?>
			<form name="form1" method="post" action="index_frame.php">
				<input type="hidden" name="type" value="cumul">
				<input type="hidden" name="when"  value="<? echo $when; ?>">
			    <input class="submit" name="cumul" type="submit" value="<? echo $MSG_CUMUL; ?>" alt="<? echo $MSG_CUMUL; ?>" title="<? echo $MSG_CUMUL; ?>">
			</form>
<?
		}

		if ($type=="histo"){
			echo "<SPAN CLASS=NAVNOHREF>".$MSG_HISTO_MOIS."</SPAN> "; 
		} elseif ($type=="Allmystats_tools" || $type=="MyVisitsTools" || $type=="password" ||  $type=="add_crawler" ||  $type=="add_bad_user_agent") {
		
		} else {
?>
			<form name="form1" method="post" action="index_frame.php">
			  <input type="hidden" name="type" value="histo">
			  <input class="submit" name="historique" type="submit" value="<? echo $MSG_HISTO_MOIS; ?>" alt="<? echo $MSG_HISTO_MOIS; ?>" title="<? echo $MSG_HISTO_MOIS; ?>">
			</form>
<?
			}

		if ($type=="Allmystats_tools"){
?>
			<form name="formtools" method="post" action="index_frame.php">
			  <input type="hidden" name="type" value="password">
			  <input class="submit" name="submitools" type="submit" value="<? echo $MSG_TOOLS_PASSWORD; ?>" alt="<? echo $MSG_TOOLS_PASSWORD; ?>" title="<? echo $MSG_TOOLS_PASSWORD; ?>">
			</form>

			<form name="formtools" method="post" action="index_frame.php">
			  <input type="hidden" name="type" value="MyVisitsTools">
			  <input class="submit" name="submitools" type="submit" value="<? echo $MSG_TOOLS_VISITS; ?>" alt="<? echo $MSG_TOOLS_VISITS; ?>" title="<? echo $MSG_TOOLS_VISITS; ?>">
			</form>

			<form name="formtools" method="post" action="index_frame.php">
			  <input type="hidden" name="type" value="add_crawler">
			  <input class="submit" name="submitools" type="submit" value="<? echo $MSG_TOOLS_BOTS; ?>" alt="<? echo $MSG_TOOLS_BOTS; ?>" title="<? echo $MSG_TOOLS_BOTS; ?>">
			</form>

			<form name="formtools" method="post" action="index_frame.php">
			  <input type="hidden" name="type" value="add_bad_user_agent">
			  <input class="submit" name="submitools" type="submit" value="<? echo $MSG_TOOLS_USER_AGENT; ?>" alt="<? echo $MSG_TOOLS_USER_AGENT; ?>" title="<? echo $MSG_TOOLS_USER_AGENT; ?>">
			</form>

			<form name="formtools" method="post" action="index_frame.php">
			  <input type="hidden" name="type" value="test_jetlag">
			  <input class="submit" name="submitools" type="submit" value="Test UTC" alt="Test UTC" title="Test UTC">
			</form>

<?php
			echo "<SPAN CLASS=NAVNOHREF>".$MSG_TOOLS."</SPAN> "; 
		} else {
?>

			<form name="formtools" method="post" action="index_frame.php">
			  <input type="hidden" name="type" value="Allmystats_tools">
			  <input class="submit" name="submithitools" type="submit" value="<? echo $MSG_TOOLS; ?>" alt="<? echo $MSG_TOOLS; ?>" title="<? echo $MSG_TOOLS; ?>">
			</form>
	
<?
			}
?>
&nbsp;&nbsp;&nbsp;<a href="http://allmystats.wertronic.com/" target="_blank"><img src="images/help3.jpeg" alt="<?php echo $MSG_HELP; ?>" width="16" height="16" border="0" align="absbottom" title="<?php echo $MSG_HELP; ?>"></a>
<br>
<br>