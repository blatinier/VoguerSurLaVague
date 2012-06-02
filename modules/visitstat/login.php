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
require "config_allmystats.php";
require "includes/langues/$langue.php";

//----------------------------------------------------------------
		//get values
		if(isset($_POST['userlogin'])) {	
			$userlogin = $_POST['userlogin'];
		} else {
			$userlogin = '';
		}
		
		if(isset($_POST['userpass'])) {	
			$userpass = $_POST['userpass'];
		} else {
			$userpass = '';
		}
//------------------------------------------------------------------------------------------
//--------------------------- Login config.php ---------------------------------------------

		if($user_login==$userlogin && $passwd==$userpass)	{
			$rightsite=$sitelog;
			$rightadmin=$admin;
			$validuser=1;
		}	
		
		if($validuser==1) {
			include_once('application_top.php');
			$_SESSION['userlogin']=$user_login;
			$_SESSION['userpass']=$passwd;
       		header("Location: index_frame.php");
		} else {
?>
			<form action="index_frame.php" method="POST" name="loginout"><br />
			<table align="center" border="1" cellspacing="0" cellpadding="10">
				<tr><td align="center"><?php echo "Login ou mot de passe incorrecte"; ?></td></tr>
				<tr><td align="center"><input name="submitloginout" type="submit" value="OK"></td></tr>
			</table>
			</form>
<?php
		}
//------------------------------------------------------------------------------------------
?>
