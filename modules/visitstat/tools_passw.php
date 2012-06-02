<?
/*
 -------------------------------------------------------------------------
 AllMyStats V1.39 - Statistiques de fréquentation visiteurs et robots
 -------------------------------------------------------------------------
 Copyright (C) 2008 - Herve Seywert
 -------------------------------------------------------------------------
 Web:   http://www.wertronic.com
 -------------------------------------------------------------------------
 Ce programme est libre, vous pouvez le redistribuer et/ou le modifier
 selon les termes de la Licence Publique Génrale GNU publiée par la Free
 Software Foundation .
 -------------------------------------------------------------------------
*/
?>
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_TITLE_CHGT_PASSWORD; ?></TH>
        </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=5 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
				<TR>
			<td>
<?php
$submitchgtpasslog = $_POST["submitchgtpasslog"];
$DeleteCookie = $_POST["DeleteCookie"];
$newuserlogin = $_POST["newuserlogin"];
$newuserpass = $_POST["newuserpass"];
$comfirmuserpass = $_POST["comfirmuserpass"];
$content = $_POST["content"];


		if (isset($submitchgtpasslog)) {
			if ($comfirmuserpass<>$newuserpass) { ?>
				<table align="center" border="1" cellspacing="0" cellpadding="10">
				<tr><td align="center"> <?php echo $MSG_CONFIRM_PASSWORD_OUT; ?>
				<form action="<?PHP_SELF;?>?type=password" method="POST" name="comfirmpassout"><br /><br />
					<input name="submitcomfirmpassout" type="submit" value="OK">				
				</form>
				</td></tr>
				</table>
				 <?php
				exit;
			}			
									
			@$content.= "\$user_login='".$newuserlogin."';\n";
			@$content.= "\$passwd='".$newuserpass."';\n";
			@$content.= "?>\n";
			$content=stripslashes($content);
			
			$filename='config_allmystats.php';
			//Write 				
			if ( $file = fopen($filename, "w") ) {
				fwrite($file, $content);
				fclose($file);
			}
?>	
				<table align="center" border="1" cellspacing="0" cellpadding="10">
				<tr><td align="center">
					<?php echo $MSG_CHGT_LOGIN_PASSWORD_SUCCESSFULL; ?>
					<form action="index_frame.php" method="POST" name="Ok"><br /><br />
					<input name="submitchgtpasslogsucces" type="submit" value="OK">
					</form>
				</td></tr>
				</table>
		</td>
	  </TBODY></TABLE><!-- Rows END --></TD>
	  
	  </TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
<?php
		exit;
		}

//##################################################################################################

$filename='config_allmystats.php';
			//Lecture et Affichage de la liste des bad user_agent

		$fp = fopen($filename,"r"); //lecture du fichier

		unset($old_Tab_config);
		while (!feof($fp)) { //on parcourt toutes les lignes
				$old_Tab_config[] = fgets($fp, 4096); // lecture du contenu de la ligne
		}


		$content = '';
		for($nb=0;$nb<count($old_Tab_config);$nb++){
			if (strstr($old_Tab_config[$nb],"\$user_login")) {
				$olduser = $user_login;
			} elseif (strstr($old_Tab_config[$nb],"\$passwd")) {
				$oldpassw = $passwd;		
			} elseif (strstr($old_Tab_config[$nb],"?>")) {
			} else {
				@$content.= $old_Tab_config[$nb]; //Sans passw and user
			}
		}

?>
<br>
				<form action="<?PHP_SELF;?>" method="POST" name="chgtpass"><br />
				<table align="center" border="1" cellspacing="0" cellpadding="10">
				<tr><td>
					<table align="center" border="0" cellspacing="0" cellpadding="4">
					<tr>		
					<td><?php echo "New Login: "; ?></td>
					<td><input name="newuserlogin" value="<?php echo $olduser; ?>" type='text' maxlength='20' size='20'/></td>
					</tr>
					<tr>
					<td><?php echo "New Password: " ; ?></td>
					<td><input name="newuserpass" value="<?php echo $oldpassw; ?>" type='password' maxlength='20' size='20'/></td>
					</tr>
					<tr>
					<td><?php echo "Confirm Password: " ; ?></td>
					<td><input name="comfirmuserpass" value="<?php echo $oldpassw; ?>" type='password' maxlength='20' size='20'/></td>
					</tr>
					<tr><td colspan="2" align="center">
					<input name="content" type="hidden" value="<?php echo htmlentities($content); ?>">
					<input name="type" type="hidden" value="password">
					<input name="submitchgtpasslog" type="submit" value="OK">
					</td></tr>
					</table>
				</td></tr>
				</table>
				</form>

<br><br>
</td>
	  </TBODY></TABLE><!-- Rows END --></TD>
	  
	  </TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>