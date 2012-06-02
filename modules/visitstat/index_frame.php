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
include_once('application_top.php');

	//--------------------------- cookie (ne pas compter ses propres visites) ----------------------------------
$SetCookie = $_POST["SetCookie"];
$DeleteCookie = $_POST["DeleteCookie"];

	if (isset($SetCookie)) {
		// Envoi d'un cookie qui s'effacera le 1er janvier 2020
		setcookie("AllMyStatsVisites","No record this",mktime(0,0,0,1,1,2020),"/",$site,0);
	}

	if (isset($DeleteCookie)) {
		// Supprime le cookie
		setcookie("AllMyStatsVisites","",0,"/",$site,0); //OK
	}

//--------------------------------------------------------------------------------------------------------------
$submit_test_jetlag = $_POST["submit_test_jetlag"];
$jetlag_not_ok = $_POST["jetlag_not_ok"];
$jetlag_OK = $_POST["jetlag_OK"];
$langue = $_POST["langue"];
$submit_langage_OK = $_POST["submit_langage_OK"];
$jetlag = $_POST["jetlag"];

?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK REL="stylesheet" TYPE="text/css" HREF="stylesheet.css">
</HEAD>
<BODY>
<?
//----------------------------------------------------------------------------------------------
################################### INSTALL ####################################################
	//if(file_exists('config_allmystats.php') && @mysql_connect($mysql_host,$mysql_login,$mysql_pass)) {
	if(file_exists('config_allmystats.php')) {
		require "config_allmystats.php";
  		require('includes/mysql_tables.php');
	} else { //config_allmystats.php n'existe pas
		require "includes/langues/francais.php";
	?>
		<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
		<TABLE align="center" CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
		  <TBODY>
		  <TR>
			<TD><!-- Data BEGIN -->
			  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
				<TBODY>
				<TR>
				  <TH class=TABLETITLE><? echo $MSG_INSTALL_TITLE; ?></TH>
				  </TR>
				<TR>
				  <TD colSpan=2><!-- Rows BEGIN -->
					<TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
					  <TBODY>
						<TR><TD CLASS=TABLEARCHIVE><br>

						<?
						if (!isset($submit_langage_OK) && !isset($submit_test_jetlag) && !isset($jetlag_not_ok)){
							echo $MSG_INSTALL_LANGUE;
							if ( !$langue ) { $langue = "francais"; } 
						?>
							<form action="<?$PHP_SELF?>" method="post" name="choose_langage">
								<select name="langue">
								  <option selected><? echo $langue ?></option>
								  <option>francais</option>
								  <option>english</option>								  
								</select><br><br>
								<input type="submit" name="submit_langage_OK" value="OK">
							</form>
						<? 
						} else {

							require "includes/langues/".$langue.".php";
							
							if(!$jetlag ) { $jetlag = "0"; }
							echo $MSG_JETLAG; ?> 
							
							<form action="<?$PHP_SELF?>" method="post" name="Test_jetlag">
								<input name="langue" type="hidden" value="<? echo $langue ?>">
								
								<select name="jetlag" 
								<? 
								if (isset($submit_test_jetlag) ) {
									echo 'disabled="disabled">';
								} ?>
								
								  <option value="<? echo $jetlag ?>" selected><? echo $jetlag ?></option>
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
								
								<?
								$date = date('d/m/Y',strtotime($jetlag." hours", strtotime(date("Y-m-d H:i:s"))));
								$heure = date('H:i',strtotime($jetlag." hours", strtotime(date("Y-m-d H:i:s"))));
								
								if (!isset($submit_test_jetlag) ){	?>
									<br>
									<input type="submit" name="submit_test_jetlag" value="Test <? echo $MSG_JETLAG ?>">
									<?
								}
								
								if (isset($submit_test_jetlag) && !isset($jetlag_OK) ){	?>
									<table border="0" align="center" cellpadding="7">
									  <tr>
										<td nowrap><?
											echo $MSG_INSTALL_SAME_DATE."<br>";
											echo "<big>Date = <strong>".$date."</strong></big><br>";
											echo "<Big>Heure = <strong>".$heure."</strong></big><br>";	
										?>
											<input name="langue" type="hidden" value="<? echo $langue ?>">
											<input name="jetlag" type="hidden" value="<? echo $jetlag ?>">
											<input type="submit" name="jetlag_not_ok" value="No">&nbsp;&nbsp;
							 </form>
										<form action="install/install.php" method="post" name="pre_conf_ok">
											<? $path_allmystats_abs = dirname($_SERVER["PHP_SELF"]); ?>
											<input name="langue" type="hidden" value="<? echo $langue ?>">
											<input name="jetlag" type="hidden" value="<? echo $jetlag ?>">
											<input name="path_allmystats_abs" type="hidden" value="<? echo $path_allmystats_abs ?>">
											<input type="submit" name="jetlag_OK" value="Yes">
										</form>
											
											<br>
										</td>
									  </tr>
									</table>									
						 <?
								}

						} 

		exit;
	}
?>
</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
<?php
##################################################################################################
//************************************************************************************************
function CompareValeurs($val1, $val2) {
	if ($val2[1] == $val1[1])
		return(strcmp($val1[0],$val2[0]));
	else
		return($val2[1] - $val1[1]);
}
//************************************************************************************************

require "includes/langues/$langue.php";
mysql_connect($mysql_host,$mysql_login,$mysql_pass);
mysql_select_db($mysql_dbnom);

//################################################################################################
	// ----------------------------- Login -------------------------------------------------------
		if($user_login!=$_SESSION['userlogin'] || $passwd!=$_SESSION['userpass'])	{
				include "header.php";
				?>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<form action="login.php" method="POST" name="login"><br />
				<table align="center" border="1" cellspacing="0" cellpadding="10">
				<tr><td>
					<table align="center" border="0" cellspacing="0" cellpadding="4">
					<tr>		
					<td><?php echo 'Login: '; ?></td><td><input name="userlogin" value="<?php echo $userlogin; ?>" type='text' maxlength='20' size='20'/></td></tr>
					<tr><td></td><td></td></tr>
					<tr><td><?php echo 'Password: ' ; ?></td><td><input name="userpass" value="<?php echo $userpass; ?>" type='password' maxlength='20' size='20'/></td></tr>
					<tr><td colspan="2" align="center"><input name="submitlogin" type="submit" value="OK"></td></tr>
					</table>
				</td></tr>
				</table>
				</form>
				<br><br><br><br>
				<table border="0" align="center" cellpadding="0" cellspacing="0">
				  <tr>
					<td align="center"><?php 
					include "footer.php"; ?></td>
				  </tr>
				</table>

				
</BODY>
				</HTML>
<?php
				exit;
		 }
//######################################################################################################

include "header.php";

$type = $_POST["type"];

switch($type){
	case "test_jetlag": require "test_utc_server.php";break;
	case "add_crawler": require "add_crawler.php";break;
	case "add_bad_user_agent": require "add_bad_user_agent.php";break;
	case "Allmystats_tools": require "tools_admin.php";break;
	case "MyVisitsTools": require "tools_my_visits.php";break;
	case "password": require "tools_passw.php";break;
	case "help": require "help.php";break;
	case "referant": require "referant.php";break;
	case "DetailsRobot": require "details_robot.php";break;
	case "cumul": require "cumul.php";break;
	case "histo": require "histomois.php";break;
	case "cumulpage": require "cumulpage.php";break;
	case "archive": require "archive.php";break;
	default: require "normal.php";break;
}
include "footer.php";
?>
</BODY>
</HTML>