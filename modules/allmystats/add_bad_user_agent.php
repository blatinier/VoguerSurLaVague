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
Liste bad
http://www.user-agents.org/

*/
	// ---------------- Ne doit pas être appelé directement -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/add_bad_user_agent.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

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
		echo "
		<table style=\"".$table_border_CSS."\">
		  <tr>
			<td>
			  <table style=\"".$table_frame_CSS."\">
				<tr>
				 <th style=\"".$table_title_CSS."\">";

					//------------------------ Delete  ----------------------------------
					//Confirm Delete 
					if (isset($submitDeleteUserAgent)){
						echo MSG_TOOLS_CONFIRM_DELETE.'<br>'. $UserAgentName ;?>
						<br>
						<form name="Deleteconfirm" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
							<input name="type" type="hidden" value="add_bad_user_agent">
							<input name="UserAgentName" type="hidden" value="<?php echo $UserAgentName; ?>" >
							<input class="submitDate" name="OkDelete" type="submit" value="<?php echo MSG_DELETE; ?>" alt="<?php echo MSG_DELETE; ?>" >&nbsp;&nbsp;
							<input class="submitDate" name="NoDelete" type="submit" value="<?php echo MSG_CANCEL; ?>" alt="<?php echo MSG_CANCEL; ?>" >
						</form>
			 <?php } 
					//------------------------ Insert  ----------------------------------
					if (isset($submitInsertBadUserAgent)){ 
						echo '
						<form name="form1" method="post" action="'.$_SERVER['PHP_SELF'].'">
							<table style="'.$table_data_CSS.'">
							  <tr>
								<th style="'.$td_data_CSS.'">User Agent</th>
								<th style="'.$td_data_CSS.'">'.MSG_COMMENTS.'</th>
								<th style="'.$td_data_CSS.'">'.MSG_TYPE.'</th>
							  </tr>
							  <tr>
								<td><input name="UserAgentName" type="text" size="30"></td>
								<td><input name="UserAgentComment" type="text" size="30"></td>
								<td>
								<select name="UserAgentType" size="1">
								  <option>I</option>
								  <option>A</option>
								  <option selected>S</option>
								</select>
								</td>
							  </tr>
						  </table>
							<input name="type" type="hidden" value="add_bad_user_agent">
							<input class="submitDate" name="DoInsertUserAgent" type="submit" value="'.MSG_ADD.'" alt="'.MSG_ADD.'" >&nbsp;&nbsp;
							<input class="submitDate" name="AnnulerInsertUserAgent" type="submit" value="'.MSG_CANCEL.'" alt="'.MSG_CANCEL.'">
						</form>';

						//------------- Affichage Operating system, navigateurs --------------
						//Display OS, browser or Bot (Spam, Unknown or not display)
						//Ne pas afficher si dans liste Bots ou dans liste Bad agent
						$display_operating_system = true;
						$display_browsers = true;
						$display_bad_user_agent = true;
						$when_date = $when; // date d/m/Y or m/Y
						//echo 'Date : '.$when.'<br>'; // if empty display all
						//$display_Other = true; //echo display other & unknown OS & Browser
						include(FILENAME_DISPLAY_OS_BROWSER);
			
						//-----------------------------------------------------------------------------
						//Mise en en forme ($AllBots) pour preg_match des bot connus (dans la table + bot en général (bot, spider , etc)
						$result_bots = mysql_query("select bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER.""); 
						$AllBots = '/Bot|Slurp|Scooter|Spider|crawl|'; //del Agent because error on user agent
						while($row = mysql_fetch_array($result_bots)){
							$Form_chaine = str_replace('/','\/',$row['bot_name']);
							$Form_chaine = str_replace('+','\+',$Form_chaine);
							$Form_chaine = str_replace('(','\(',$Form_chaine);
							$Form_chaine = str_replace(')','\)',$Form_chaine);
							$AllBots .= $Form_chaine.'|';
						}
						$AllBots = substr($AllBots, 0, strlen($AllBots)-1); //delete last "|"
						$AllBots .= '/i';
					  //-------------------------------------------------------------------------
						unset($Tab_user_agent);
						//Lecture et Affichage de la liste des bad user_agent
						$result_agent = mysql_query("select user_agent from ".TABLE_BAD_USER_AGENT.""); 
						while($row = mysql_fetch_array($result_agent)){
							$Tab_user_agent[] = $row['user_agent'];
						}
			
						$Other_browsers_os_bots = array_unique($Other_browsers_os_bots);
						usort($Other_browsers_os_bots,"CompareValeurs");
																																
						for($i = 0; $i <= count($Other_browsers_os_bots); $i++){
							if (!in_array($Other_browsers_os_bots[$i], $Tab_user_agent) && !preg_match($AllBots, $Other_browsers_os_bots[$i]) && $Other_browsers_os_bots[$i] != ';' ) {
								if (trim($Other_browsers_os_bots[$i])) { $Unknown[] = $Other_browsers_os_bots[$i]; }
							}
						}

						echo '
							<table style="'.$table_border_CSS.'">
								<tr>
									<td style="'.$table_data_CSS.' font-weight:lighter; text-align:left;">';
										if ($Unknown) {
											echo '<center><strong>'.MSG_USER_AGENT_UNKNOWN_LIST.'</strong></center><br><br>';	//$Other_browsers_os_bots[$i] != ';' pollution ?
											for($i = 0; $i <= count($Unknown); $i++){
												echo $Unknown[$i].'<br>';
											}
										} else {
											echo '<center>'.MSG_USER_AGENT_NO_UNKNOWN_LIST.'</center>';
										}
	
							echo '</td>
								</tr>
							</table>';

				 }
					//------------------------ Editer User agent ----------------------------------
					if (isset($submitEditUserAgent)){
						echo '
						<form name="form1" method="post" action="'.$_SERVER['PHP_SELF'].'">
							<table style="'.$table_data_CSS.'">
							  <tr>
								<th style="'.$td_data_CSS.'">User Agent</th>
								<th style="'.$td_data_CSS.'">'.MSG_COMMENTS.'</th>
								<th style="'.$td_data_CSS.'">'.MSG_TYPE.'</th>
							  </tr>
							  <tr>
								<td><input name="UserAgentName" type="text" size="30" value="'.$UserAgentName.'"></td>
								<td><input name="UserAgentComment" type="text" size="30" value="'.$UserAgentComment.'"></td>
								<td>
								<select name="UserAgentType" size="1">
								  <option>I</option>
								  <option>A</option>
								  <option>S</option>
								  <option selected>'.$UserAgentType.'</option>
								</select>
								</td>
							  </tr>
						  </table>
							<input name="type" type="hidden" value="add_bad_user_agent">
							<input name="UserAgentID" type="hidden" value="'.$UserAgentID.'" >
							<input class="submitDate" name="DoEditUserAgent" type="submit" value="Editer" alt="Editer" >&nbsp;&nbsp;
							<input class="submitDate" name="AnnulerModifierUserAgent" type="submit" value="'.MSG_CANCEL.'" alt="'.MSG_CANCEL.'">
						</form>';
					 } 
					//-----------------------------------------------------------------------
				?>
				 </th>
		</tr>
</table></td></tr></table></td></tr></table><br />
		<?php
	}
//############################## Action ###########################################################
	//Do delete
	if (isset($OkDelete)){
		mysql_query("delete from ".TABLE_BAD_USER_AGENT." where user_agent='".$UserAgentName."'"); 
		echo '<br><div align="center"><font color="#009933"><b>User Agent: '.$UserAgentName. MSG_TOOLS_DELETE_SUCCESS.'</b></font></div><br><br>' ;
	}
	//Do insert
	if (isset($DoInsertUserAgent)){
		mysql_query("insert into ".TABLE_BAD_USER_AGENT." values ('','".trim($UserAgentName)."','".(trim($UserAgentComment))."','".$UserAgentType."')");
		echo '<br><div align="center"><font color="#009933"><b>User Agent: '.$UserAgentName. MSG_TOOLS_ADD_SUCCESS.'</b></font></div><br><br>' ;
		$UserAgentName = ''; $UserAgentComment = ''; $UserAgentType = '';
	}
	//Do edit
	if (isset($DoEditUserAgent)){
		mysql_query("update ".TABLE_BAD_USER_AGENT." set user_agent='".$UserAgentName."', info='".($UserAgentComment)."', type='".$UserAgentType."'  where id='".$UserAgentID."' ");
		echo '<br><div align="center"><font color="#009933"><b>User Agent: '.$UserAgentName. MSG_TOOLS_MODIFIE_SUCCESS.'</b></font></div><br><br>' ;
		$UserAgentName = ''; $UserAgentComment = ''; $UserAgentType = '';
	}

	if (isset($AnnulerInsertUserAgent)){
		$UserAgentName = ''; $UserAgentComment = ''; $UserAgentType = '';
	}

//##################################################################################################

			//Lecture et Affichage de la liste des bad user_agent
			$result1 = mysql_query("select id, user_agent, info, type from ".TABLE_BAD_USER_AGENT.""); 
			if (!$result1) { //ex: si la table n'existe pas
				echo 'Impossible d\'exécuter la requête : ' . mysql_error();
				exit;
			}

			while($row = mysql_fetch_array($result1)){
				$Tab_bad_user_agent[] = array($row['id'], $row['user_agent'], stripslashes(($row['info'])), $row['type']);
			}

			array_multisort ($Tab_bad_user_agent, SORT_DESC); 

				echo "
				<table align=center border=0 border=0>
				  <tr>
					<td>". MSG_NOTE_BAD_USER_AGENT ."</td>
				  </tr>
				</table>";
				
echo '
	<table style="'.$table_border_CSS.' width: 80%;">
	<tr>
		<td>
		 <table style="'.$table_frame_CSS.'">
       	  <tr>
          <td style="'.$table_title_CSS.'">'.MSG_USER_AGENT.'<br>
          <small><div style=\"font-weight:lighter; text-align:left;\">'.MSG_USER_AGENT_SPAM.'<br>'.MSG_USER_AGENT_UNKNOWN.'<br>'.MSG_USER_AGENT_OTHER.'</div></small>
				<br>
				<form name="formBadUserAgent" method="post" action="'.$_SERVER['PHP_SELF'].'">
					<input name="type" type="hidden" value="add_bad_user_agent">
					<input class="submitDate" name="submitInsertBadUserAgent" type="submit" value="'.MSG_ADD.'" alt="'.MSG_ADD.'" >
				</form>
		</td>
        </tr>
        <tr>
          <td colspan="2">
            <table style="'.$table_data_CSS.'">
              <tr>
					<th style="'.MSG_USER_AGENT.'</th>
					<th style="'.MSG_COMMENTS.'</th>
					<th style="'.MSG_TYPE.'</th>
					<th style="'.MSG_ACTION.'</th>';


	for($nb = 0; $nb < count($Tab_bad_user_agent); $nb++){
		if ($Tab_bad_user_agent)
			echo '
			<tr>
			<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_bad_user_agent[$nb][1].'</td>
			<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_bad_user_agent[$nb][2].'</td>
			<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_bad_user_agent[$nb][3].'</td>


			<td style="'.$td_data_CSS.'">
			  <table style="border: 0px solid #000000; margin-left: auto; margin-right: auto;">
			  <tr>
				<td>
				<form name="formDelete" method="post" action="'.$_SERVER['PHP_SELF'].'">
					<input name="type" type="hidden" value="add_bad_user_agent">
					<input name="UserAgentName" type="hidden" value="'.$Tab_bad_user_agent[$nb][1].'">
					<input class="submitDate" name="submitDeleteUserAgent" type="submit" value="'.MSG_DELETE.'" alt="'.MSG_DELETE.'" >
				</form>
				</td>
				<td>
				<form name="formEdit" method="post" action="'.$_SERVER['PHP_SELF'].'">
					<input name="type" type="hidden" value="add_bad_user_agent">
					<input name="UserAgentName" type="hidden" value="'.$Tab_bad_user_agent[$nb][1].'">
					<input name="UserAgentComment" type="hidden" value="'.$Tab_bad_user_agent[$nb][2].'">
					<input name="UserAgentType" type="hidden" value="'.$Tab_bad_user_agent[$nb][3].'">
					<input name="UserAgentID" type="hidden" value="'.$Tab_bad_user_agent[$nb][0].'">
				<input class="submitDate" name="submitEditUserAgent" type="submit" value="'.MSG_EDIT.'" alt="'.MSG_EDIT.'" >
				</form>
				</td>
			  </tr>
			</table>

		</td>';

	}
	
?>      
</table></td></tr></table></td></tr></table><br />
