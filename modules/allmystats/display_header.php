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

	// ---------------- Should not be called directly -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/display_header.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

//--------------------------- style table (for Admin) ---------------
	$table_border_CSS = 'border: 1px solid #000000; border-collapse: collapse; margin-left: auto; margin-right: auto; background-color: #99CCFF; width: 570px;';

	$table_frame_CSS = 'border: 0px solid #000000; border-collapse: collapse; margin-top: 3px; background-color: #99CCFF; width: 100%; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal;'; 
	$table_data_CSS = 'border: 1px solid #000000; border-collapse: collapse; margin-top: 3px; margin-bottom: 3px; margin-left: 3px; margin-right: 3px; background-color: #FAFAFA; color: #000000; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal; width: 99%;'; 
	$td_data_CSS = 'border-width: 1px 1px 0px 0px; border-color: #000000;  border-style: solid; border-collapse: collapse; padding: 3px; font-family: Verdana, Arial, sans-serif; font-size: 10px;'; 
	$table_title_CSS = 'border: 0px solid #000000; font-family: Verdana, Arial, sans-serif; font-size: 14px; font-weight: bold; text-align:center; vertical-align:middle;';
//--------------------------- Style Graph (for Admin)---------------
	$page_view = 'color: #2000FF; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal;';
	$style_visits = 'color: #8F0080; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal;'; 
	$td_txt_CSS = 'color: #000000; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-style: normal;';
//------------------------------------------------------------------------------

include_once('version.php');

if(isset($_POST["when"])) { $when = $_POST["when"]; } else { $when = '';}
if(isset($_POST['mois'])) { $mois = $_POST['mois']; } else { $mois = '';}
if(isset($_POST['type'])) { $type = $_POST['type']; } else { $type = '';}
if(isset($_POST['submitDeleteUpdate'])) { $submitDeleteUpdate = $_POST['submitDeleteUpdate']; } 
if(isset($_POST['submitDeleteInstall'])) { $submitDeleteInstall = $_POST['submitDeleteInstall']; } 

?>
<div align="center">
				<?php 
				echo '
				<table style="'.$table_border_CSS.'">
					<tr>
						<td>
						  <table style="'.$table_frame_CSS.' margin-top: 3px; margin-bottom: 3px;">
							<tr>
						  <td width="25%" align="center" valign="middle">
							  <span style="font-size:large; color:#EC0000; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-style:italic;">AllMyStats</span>
							  <br>
							  <span style="font-size:small; color:#EC0000; font-family:Arial, Helvetica, sans-serif; font-weight:bold;	font-style:italic;">'.VERSION.'</span>
						  </td>
						  <td style="'.$table_title_CSS.'" align="center">
								<div class="head">'.MSG_REPORT_AUDIENCE.'</div>
								<a class=SiteName href="http://'.$site.'" target="_blank">'.$site.'</a>
						  </td>
						  <td width="15%" align="right">';
							if(isset($_SESSION['userpass'])) { 
								echo '
								<a href="'.FILENAME_LOGOUT.'"><img src="images/icons/logout.gif" border="0" alt="'.MSG_LOGOUT.'" title="'.MSG_LOGOUT.'"></a>&nbsp;&nbsp;&nbsp;';
							} ?>
						   </td>
								<?php
								if(!isset($_SESSION['userlogin'])) {
									$_SESSION['userlogin'] = '';
								}
								
								if(!isset($_COOKIE["AllMyStatsVisites"])) { $_COOKIE["AllMyStatsVisites"] = ''; }
								if($user_login == $_SESSION['userlogin'] && $passwd == $_SESSION['userpass']){
									 if($_COOKIE["AllMyStatsVisites"] != 'No record this') { ?>
										<tr>
										<td colspan="3" align="center">
										<?php echo MSG_NO_COUNT_VISITS; ?>
										<form name="formlink" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
											 <input type="hidden" name="type"  value="MyVisitsTools">
											 <input class="submitDate" name="submit" type="submit" value="<?php echo MSG_CLICK_HERE; ?>">
										</form>
										</td>
										</tr><?php	
									} 
								}
				
								// Test if new version disponible
								$installed_version = substr(VERSION, 1) + 0; //+0 pour mettre en numérique sinon chaine - Note set "allow_url_fopen = On" in php.ini for file_get_contents
								if(!isset($_SESSION['current_version'])) {
									//------------------------------------------------									
									//Important sinon warning avec mod_security sur serveur check - 25-03-2011
									$opts = array(
										  'http'=> array(
											'method'=>   "GET",
											'header'=>    'Accept: text/html',
											'user_agent'=>    'allmystats-check-ver'
										  )
										); 
					
									$ctx = stream_context_create($opts);
									//------------------------------------------------
									$_SESSION['current_version']  = @file_get_contents("http://allmystats.wertronic.com/allmystats_check_ver.php?request=".$_SERVER['HTTP_HOST']."&cust_ver=".$installed_version."", NULL, $ctx);
								} ?>
						<tr>
						</tr>
					  </table>
				</td></tr></table></td></tr></table><br />
			<?php
			if ($user_login == $_SESSION['userlogin'] && $passwd == $_SESSION['userpass'])	{
				if (isset($submitDeleteUpdate)) {
				  delete_directory("update");
				} elseif (isset($submitDeleteInstall)) {
				  delete_directory("install");
				} ?>

				<?php 
				$version_update = false;
				$result = @mysql_query("select * from ".TABLE_UPDATES."");
				while ($row = @mysql_fetch_array($result)){
					if ($row['action'] == 'update to V1.59') {
						$version_update = true;
					}
				}
					
				if (substr(VERSION, 1) + 0 >= 1.59 && !$version_update) {  //+0 pour mettre en numérique sinon chaine
					echo '
					<div style="text-align:center;">
						<font color="#FF0000"><b>Vous devez updater MySQL en utf8<br>You must update MySQL tables</b></font><br> 
						<form name="updatemysqlutf8" method="post" action="update/update_to_utf8.php">
							<input name="submitupdateutf8" type="submit" value="Update MySQL now">
						</form>	
					</div>';					
					exit;
				} 
	
				if (is_dir("update")) {
					echo '
					<div style="text-align:center;">
						<font color="#FF0000"><b>'.MSG_DIRECTORIE_UPDATE_EXIST.'</b></font><br>
						<form name="delete_update" method="post" action="'.$_SERVER['PHP_SELF'].'">
							<input name="submitDeleteUpdate" type="submit" value="Delete /update">
						</form>
					</div>
					<br />';					
				} 
				
				if (is_dir("install")) {
					echo '
					<div style="text-align:center;">
						<font color="#FF0000"><b>'.MSG_DIRECTORIE_INSTALL_EXIST.'</b></font><br>	
						<form name="delete_install" method="post" action="'.$_SERVER['PHP_SELF'].'">
							<input name="submitDeleteInstall" type="submit" value="Delete /install">
						</form>
					</div>
					<br />';					
				} 
				
				if (trim($type) == ""){ 
					echo "<span class=\"HeaderNav\">".MSG_ACCUEIL."</span> "; 
				} else { ?>
					<form name="form1" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="">
					  <input type="hidden" name="when"  value="<?php echo $when; ?>">
					  <input class="submit" name="detail_ref" type="submit" value="<?php echo MSG_ACCUEIL; ?>" alt="<?php echo MSG_ACCUEIL; ?>" >
					</form> <?php
				}

				if ($type == "DetailsRobot") {
					echo "<span class=\"HeaderNav\">".MSG_BOT_VISITS."</span> ";
				} elseif ($type == "Allmystats_tools" || $type == "MyVisitsTools" || $type == "password" ||  $type == "add_crawler" ||  $type == "add_bad_user_agent") {

				} else { ?>
					<form name="formDetailsRobot" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
						<input type="hidden" name="type" value="DetailsRobot">
						<input type="hidden" name="when"  value="<?php echo $when; ?>">
						<input class="submit" name="submitDetailsRobot" type="submit" value="<?php echo MSG_BOT_VISITS; ?>" alt="<?php echo MSG_BOT_VISITS; ?>" title="<?php echo MSG_BOT_VISITS; ?>">
					</form> <?php
				}

				if ($type == "cumul"){
					echo "<span class=\"HeaderNav\">".MSG_MONTHLY."</span> "; 
				} elseif ($type == "Allmystats_tools" || $type == "MyVisitsTools" || $type == "password" ||  $type == "add_crawler" ||  $type == "add_bad_user_agent") {
		
				} else { ?>
					<form name="form1" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
						<input type="hidden" name="type" value="cumul">
						<input type="hidden" name="when"  value="<?php echo $when; ?>">
						<input class="submit" name="cumul" type="submit" value="<?php echo MSG_MONTHLY; ?>" alt="<?php echo MSG_MONTHLY; ?>" title="<?php echo MSG_MONTHLY; ?>">
					</form> <?php
				}
		
				if ($type == "histo"){
					echo "<span class=\"HeaderNav\">".MSG_MONTH_HISTO."</span> "; 
				} elseif ($type == "Allmystats_tools" || $type == "MyVisitsTools" || $type == "password" ||  $type == "add_crawler" ||  $type == "add_bad_user_agent") {
				
				} else { ?>
					<form name="form1" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="histo">
					  <input class="submit" name="historique" type="submit" value="<?php echo MSG_MONTH_HISTO; ?>" alt="<?php echo MSG_MONTH_HISTO; ?>" title="<?php echo MSG_MONTH_HISTO; ?>">
					</form> <?php
				}

				if ($type == "Allmystats_tools"){ ?>
					<form name="formtools" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="password">
					  <input class="submit" name="submitools" type="submit" value="<?php echo MSG_ADMIN_TOOLS_PASSW; ?>" alt="<?php echo MSG_ADMIN_TOOLS_PASSW; ?>" title="<?php echo MSG_ADMIN_TOOLS_PASSW; ?>">
					</form>
		
					<form name="formtools" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="MyVisitsTools">
					  <input class="submit" name="submitools" type="submit" value="<?php echo MSG_ADMIN_TOOLS_MY_VISITS; ?>" alt="<?php echo MSG_ADMIN_TOOLS_MY_VISITS; ?>" title="<?php echo MSG_ADMIN_TOOLS_MY_VISITS; ?>">
					</form>
		
					<form name="formtools" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="add_crawler">
					  <input class="submit" name="submitools" type="submit" value="<?php echo MSG_ADMIN_TOOLS_BOTS; ?>" alt="<?php echo MSG_ADMIN_TOOLS_BOTS; ?>" title="<?php echo MSG_ADMIN_TOOLS_BOTS; ?>">
					</form>
		
					<form name="formtools" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="add_bad_user_agent">
					  <input class="submit" name="submitools" type="submit" value="<?php echo MSG_BAD_USER_AGENT; ?>" alt="<?php echo MSG_BAD_USER_AGENT; ?>" title="<?php echo MSG_BAD_USER_AGENT; ?>">
					</form>
		
					<form name="formtools" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="test_jetlag">
					  <input class="submit" name="submitools" type="submit" value="Test UTC" alt="Test UTC" title="Test UTC">
					</form> <?php
					echo "<span class=\"HeaderNav\">".MSG_ADMIN_TOOLS."</span> "; 
		
				} elseif ($type == "MyVisitsTools"){ ?>
					<form name="form1" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
						 <input type="hidden" name="type" value="HistoLoging">
						 <input class="submit" name="Histo_Loging" type="submit" value="History loging" alt="History loging" title="History loging">
					</form>
		
					<form name="formtools" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="Allmystats_tools">
					  <input class="submit" name="submithitools" type="submit" value="<?php echo MSG_ADMIN_TOOLS; ?>" alt="<?php echo MSG_ADMIN_TOOLS; ?>" title="<?php echo MSG_ADMIN_TOOLS; ?>">
					</form> <?php
		
				} else { ?>
		
					<form name="formtools" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
					  <input type="hidden" name="type" value="Allmystats_tools">
					  <input class="submit" name="submithitools" type="submit" value="<?php echo MSG_ADMIN_TOOLS; ?>" alt="<?php echo MSG_ADMIN_TOOLS; ?>" title="<?php echo MSG_ADMIN_TOOLS; ?>">
					</form> <?php
				}
		
				?>
				&nbsp;&nbsp;&nbsp;<a href="http://allmystats.wertronic.com/" target="_blank"><img src="images/help3.jpeg" alt="<?php echo MSG_HELP; ?>" width="16" height="16" border="0" align="absbottom" title="<?php echo MSG_HELP; ?>"></a>
				</div>
				<br>
			<?php
			} else { // If not logued
				// Display message if new version disponible
				if ($_SESSION['current_version'] > $installed_version) {
					echo '
					<div align="center">
						<b><font color="#CC0000">Nouvelle version disponible:</font></b><br>
						<a href="http://allmystats.wertronic.com/fr_download.php" target="_blank">Télécharger AllMyStats V'.$_SESSION['current_version'].'</a>
					</div><br>';
				}
			}
			?>
