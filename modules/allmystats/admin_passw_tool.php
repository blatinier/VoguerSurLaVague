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

	// ---------------- Should not be called directly -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/admin_passw_tool.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

if(isset($_POST["submitchgtpasslog"])) { $submitchgtpasslog = $_POST["submitchgtpasslog"]; }
if(isset( $_POST["newuserlogin"])) { $newuserlogin = $_POST["newuserlogin"]; }
if(isset($_POST["newuserpass"])) { $newuserpass = $_POST["newuserpass"]; }
if(isset($_POST["comfirmuserpass"])) { $comfirmuserpass = $_POST["comfirmuserpass"]; }
if(isset($_POST["content"])) { $content = $_POST["content"]; }

echo '
	<table style="'.$table_border_CSS.'">
		<tr>
			<td>
			  <table style="'.$table_frame_CSS.'">
				<tr>
					 <td style="'.$table_title_CSS.'">'
						.MSG_ADMIN_TOOLS_CHGT_PASSW.'
					 </td>
				</tr>
				<tr>
				  <td colspan="2">
					<table style="'.$table_data_CSS.'">
					  <tr>
						<td>';

		if (isset($submitchgtpasslog)) {
			if ($comfirmuserpass<>$newuserpass) { ?>
				<table align="center" border="1" cellspacing="0" cellpadding="10">
				<tr>
					<td align="center"> <?php echo MSG_ADMIN_TOOLS_CONFIRM_PASSW_OUT; ?>
					<form action="<?php echo $_SERVER['PHP_SELF'];?>?type=password" method="POST" name="comfirmpassout"><br /><br />
						<input name="type" type="hidden" value="password">
						<input name="submitcomfirmpassout" type="submit" value="OK">				
					</form>
					</td>
				</tr>
				</table>
				 <?php
				exit;
			}			
									
			$filename='config_allmystats.php';
			$fp = fopen($filename,"r");
	
			unset($old_Tab_config);
			while (!feof($fp)) {
				$old_Tab_config[] = fgets($fp, 4096);
			}
	
			$content = '';
			for($nb=0;$nb<count($old_Tab_config);$nb++){
				if (strstr($old_Tab_config[$nb],"\$user_login")) {
					$olduser = $user_login;
				} elseif (strstr($old_Tab_config[$nb],"\$passwd")) {
					$oldpassw = $passwd;		
				} elseif (strstr($old_Tab_config[$nb],"?>")) {
				
				} else {
					@$content.= $old_Tab_config[$nb]; //Sans passw and user and end php
				}
			}
			@$content.= "\$user_login='".$newuserlogin."';\n";

			if($newuserpass != $oldpassw) { //compatibility with //< v1.74
				@$content .= "\$passwd='".md5($newuserpass)."';\n"; // Change to md5 if modified
				//@$content.= "\$passwd='".$newuserpass."';\n";
			} else {
				@$content.= "\$passwd='".$newuserpass."';\n";
			}

			@$content.= "?>\n";
			$content = stripslashes($content);

			//Write 				
			if ( $file = fopen($filename, "w") ) {
				fwrite($file, $content);
				fclose($file);
			}

?>	
				<table align="center" border="1" cellspacing="0" cellpadding="10">
					<tr>
						<td align="center">
						<?php echo MSG_ADMIN_TOOLS_CHGT_USER_PASSW_SUCCESS; ?>
						<form action="<?php echo FILENAME_INDEX_FRAME; ?>" method="POST" name="Ok"><br /><br />
						<input name="submitchgtpasssucces" type="submit" value="OK">
						</form>
						</td>
					</tr>
				</table>
			</td>
			</table></td>
					  
</tr></table></td></tr></table><br />
<?php
			exit;
		}

//##################################################################################################

		$filename='config_allmystats.php';
		$fp = fopen($filename,"r");

		unset($old_Tab_config);
		while (!feof($fp)) {
			$old_Tab_config[] = fgets($fp, 4096);
		}

		//On ne passe plus le content en POST car génère errors with mod_security
		for($nb=0;$nb<count($old_Tab_config);$nb++){
			if (strstr($old_Tab_config[$nb],"\$user_login")) {
				$olduser = $user_login;
			} elseif (strstr($old_Tab_config[$nb],"\$passwd")) {
				$oldpassw = $passwd;		
			}
		}

				//disabled="disabled"

				echo '<br>
				<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="chgtpass"><br />
					<table align="center" style="white-space:nowrap;">
						<tr>
						<td>
							<table align="center" border="0" cellspacing="0" cellpadding="5">
								<tr>		
									<td>New Login: </td>
									<td><input name="newuserlogin" value="'.$olduser.'" type="text" maxlength="20" size="20"/></td>
								</tr>
								<tr>
									<td>New Password: </td>
									<td><input name="newuserpass" value="'.$oldpassw.'" type="password" maxlength="20" size="20"/></td>
								</tr>
								<tr>
									<td>Confirm Password: </td>
									<td><input name="comfirmuserpass" value="'.$oldpassw.'" type="password" maxlength="20" size="20"/></td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<input name="type" type="hidden" value="password">
										<input name="submitchgtpasslog" type="submit" value="OK">
									</td>
								</tr>
							</table>
						</td>
						</tr>
					</table>
				</form>'; ?>

<br><br>
</td>
	  </table></td>
	  
	  </tr></table></td></TR></table><br />
	  