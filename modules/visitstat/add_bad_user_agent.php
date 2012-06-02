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
Liste bad
http://www.user-agents.org/

						<b>Version alpha en test</b><br>
						<strong>Pour les experts : attention à ne pas définir des bots/user agent innocents!</strong><br>
						Vous devrez les bloquer si nécessaire avec un fichier .htaccess<br><br>
						Type = S (SPAM) est affiché en rouge dans Liste Bad user agent et n'est pas compté comme visiteur ni comme robot<br>
						Type = I (robot inconnu) est compt&eacute; comme visiteur mais n'est pas affich&eacute;.<br>
						Le user agent est recherché à l'identique (chaîne de caractères identique) et non dans la chaîne.<br>
						<b>Note:</b> si aucun bad user agent n'est Détecté à partir de cette liste, le tableau Bad user agent ne sera pas affiché.<br>
						Voir aussi: <a href=\"http://www.user-agents.org/\" target=\"_blank\">user-agents.org : Liste user agent</a><br><br>

*/

$submitEditUserAgent = $_POST["submitEditUserAgent"];
$submitInsertBadUserAgent = $_POST["submitInsertBadUserAgent"];
$submitDeleteUserAgent = $_POST["submitDeleteUserAgent"];

$UserAgentName = $_POST["UserAgentName"];
$UserAgentComment = $_POST["UserAgentComment"];
$UserAgentType = $_POST["UserAgentType"];
$UserAgentID = $_POST["UserAgentID"];

$DoInsertUserAgent = $_POST["DoInsertUserAgent"];
$DoEditUserAgent = $_POST["DoEditUserAgent"];
$AnnulerInsertUserAgent = $_POST["AnnulerInsertUserAgent"];

$OkDelete = $_POST["OkDelete"];
$NoDelete = $_POST["NoDelete"];



	if (isset($submitDeleteUserAgent) || isset($submitInsertBadUserAgent) || isset($submitEditUserAgent) ){
		?>
		<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
		  <TBODY>
		  <TR>
			<TD><!-- Data BEGIN -->
			  <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
				<TBODY>
				<TR>
				  <TH class=TABLETITLE>
					<? 
					//------------------------ Delete  ----------------------------------
					//Confirm Delete 
					if (isset($submitDeleteUserAgent)){
						echo $MSG_TOOLS_CONFIRM_DELETE.'<br>'. $UserAgentName ;?>
						<br>
						<form name="Deleteconfirm" method="post" action="<?PHP_SELF;?>">
							<input name="type" type="hidden" value="add_bad_user_agent">
							<input name="UserAgentName" type="hidden" value="<?php echo $UserAgentName; ?>" >
							<input class="submitDate" name="OkDelete" type="submit" value="<? echo $MSG_DELETE; ?>" alt="<? echo $MSG_DELETE; ?>" >
							<input class="submitDate" name="NoDelete" type="submit" value="<? echo $MSG_CANCEL; ?>" alt="<? echo $MSG_CANCEL; ?>" >
						</form>
			 <?php } 
					//------------------------ Insert  ----------------------------------
					if (isset($submitInsertBadUserAgent)){ ?>

						<form name="form1" method="post" action="<?PHP_SELF;?>">
							<table border="0" align="center" cellpadding="3" cellspacing="0">
							  <tr>
								<td align="center">User Agent</td>
								<td align="center"><?php echo $MSG_COMMENTS; ?></td>
								<td align="center"><?php echo $MSG_TYPE; ?></td>
							  </tr>
							  <tr>
								<td><input name="UserAgentName" type="text" size="30"></td>
								<td><input name="UserAgentComment" type="text" size="30"></td>
								<td>
								<select name="UserAgentType" size="1">
								  <option>I</option>
								  <option selected>S</option>
								</select>
								</td>
							  </tr>
						  </table>
							<input name="type" type="hidden" value="add_bad_user_agent">
							<input class="submitDate" name="DoInsertUserAgent" type="submit" value="<? echo $MSG_ADD; ?>" alt="<? echo $MSG_ADD; ?>" >
							<input class="submitDate" name="AnnulerInsertUserAgent" type="submit" value="<? echo $MSG_CANCEL; ?>" alt="<? echo $MSG_CANCEL; ?>" >
						</form>
				<? }
					//------------------------ Editer User agent ----------------------------------
					if (isset($submitEditUserAgent)){ ?>
						<form name="form1" method="post" action="<?PHP_SELF;?>">
							<table border="0" align="center" cellpadding="3" cellspacing="0">
							  <tr>
								<td align="center">User Agent</td>
								<td align="center"><?php echo $MSG_COMMENTS; ?></td>
								<td align="center"><?php echo $MSG_TYPE; ?></td>
							  </tr>
							  <tr>
								<td><input name="UserAgentName" type="text" size="30" value="<?php echo $UserAgentName; ?>"></td>
								<td><input name="UserAgentComment" type="text" size="30" value="<?php echo $UserAgentComment; ?>"></td>
								<td>
								<select name="UserAgentType" size="1">
								  <option>I</option>
								  <option>S</option>
								  <option selected><?php echo $UserAgentType; ?></option>
								</select>
								</td>
							  </tr>
						  </table>
							<input name="type" type="hidden" value="add_bad_user_agent">
							<input name="UserAgentID" type="hidden" value="<?php echo $UserAgentID; ?>" >
							<input class="submitDate" name="DoEditUserAgent" type="submit" value="<? echo 'Modifier'; ?>" alt="<? echo 'Modifier'; ?>" >
							<input class="submitDate" name="AnnulerModifierUserAgent" type="submit" value="<? echo 'Annuler'; ?>" alt="<? echo 'Annuler'; ?>" >
						</form>
				<? } 
					//-----------------------------------------------------------------------
				?>
				 </TH>
		</TR>
		</TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
		<?
	}
//############################## Action ###########################################################
	//Do delete
	if (isset($OkDelete)){
		mysql_query("delete from ".TABLE_BAD_USER_AGENT." where user_agent='".$UserAgentName."'"); 
		echo '<br>Le User Agent: '.$UserAgentName. $MSG_TOOLS_DELETE_SUCCESS.'<br><br>' ;
	}
	//Do insert
	if (isset($DoInsertUserAgent)){
		mysql_query("insert into ".TABLE_BAD_USER_AGENT." values ('','".trim($UserAgentName)."','".trim($UserAgentComment)."','".$UserAgentType."')");
		echo '<br>Le User Agent: '.$UserAgentName. $MSG_TOOLS_ADD_SUCCESS.'<br><br>' ;
		$UserAgentName = ''; $UserAgentComment = ''; $UserAgentType = '';
	}
	//Do edit
	if (isset($DoEditUserAgent)){
		mysql_query("update ".TABLE_BAD_USER_AGENT." set user_agent='".$UserAgentName."', info='".$UserAgentComment."', type='".$UserAgentType."'  where id='".$UserAgentID."' ");
		echo '<br>Le User Agent:: '.$UserAgentName. $MSG_TOOLS_MODIFIE_SUCCESS.'<br><br>' ;
		$UserAgentName = ''; $UserAgentComment = ''; $UserAgentType = '';
	}


	if (isset($AnnulerInsertUserAgent)){
		$UserAgentName = ''; $UserAgentComment = ''; $UserAgentType = '';
	}

//##################################################################################################

			//Lecture et Affichage de la liste des bad user_agent
			$result1=mysql_query("select id, user_agent, info, type from ".TABLE_BAD_USER_AGENT.""); 
			if (!$result1) { //ex: si la table n'existe pas
				echo 'Impossible d\'exécuter la requête : ' . mysql_error();
				exit;
			}

			while($row=mysql_fetch_array($result1)){
				$Tab_bad_user_agent[] = array($row['id'],$row['user_agent'],$row['info'],$row['type']);
			}

			array_multisort ($Tab_bad_user_agent, SORT_DESC); 

				echo "
				<table align=center border=0 border=0>
				  <tr>
					<td>". $MSG_NOTE_BAD_USER_AGENT ."</td>
				  </tr>
				</table>
						";
?>
<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->

      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_BAD_USER_AGENT; ?>
				<br><form name="formBadUserAgent" method="post" action="<?PHP_SELF;?>">
				<input name="type" type="hidden" value="add_bad_user_agent">
				<input class="submitDate" name="submitInsertBadUserAgent" type="submit" value="<? echo $MSG_ADD; ?>" alt="<? echo $MSG_ADD; ?>" >
				</form>
		  </TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
					<TH><? echo $MSG_USER_AGENT; ?></TH>
					<TH><? echo $MSG_COMMENTS; ?></TH>
					<TH><? echo $MSG_TYPE; ?></TH>
					<TH><? echo $MSG_ACTION; ?></TH>

<?php
	for($nb=0;$nb<count($Tab_bad_user_agent);$nb++){
		if ($Tab_bad_user_agent) echo "<tr>
		<td>&nbsp;".$Tab_bad_user_agent[$nb][1]."</td>
		<td>&nbsp;".$Tab_bad_user_agent[$nb][2]."</td>
		<td>&nbsp;".$Tab_bad_user_agent[$nb][3]."</td>
		";

?>
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>
				<form name="formDelete" method="post" action="<?PHP_SELF;?>">
					<input name="type" type="hidden" value="add_bad_user_agent">
					<input name="UserAgentName" type="hidden" value="<?php echo $Tab_bad_user_agent[$nb][1]; ?>">
					<input class="submitDate" name="submitDeleteUserAgent" type="submit" value="<? echo $MSG_DELETE; ?>" alt="<? echo $MSG_DELETE; ?>" >
				</form>
				</td>
				<td>
				<form name="formEdit" method="post" action="<?PHP_SELF;?>">
					<input name="type" type="hidden" value="add_bad_user_agent">
					<input name="UserAgentName" type="hidden" value="<?php echo $Tab_bad_user_agent[$nb][1]; ?>">
					<input name="UserAgentComment" type="hidden" value="<?php echo $Tab_bad_user_agent[$nb][2]; ?>">
					<input name="UserAgentType" type="hidden" value="<?php echo $Tab_bad_user_agent[$nb][3]; ?>">
					<input name="UserAgentID" type="hidden" value="<?php echo $Tab_bad_user_agent[$nb][0]; ?>">
				<input class="submitDate" name="submitEditUserAgent" type="submit" value="<? echo $MSG_EDIT; ?>" alt="<? echo $MSG_EDIT; ?>" >
				</form>
				</td>
			  </tr>
			</table>

		</td>
<?
	}
	
?>      
 </TBODY></TABLE><!-- Rows END --></TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
