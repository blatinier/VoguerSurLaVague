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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/'.FILENAME_ADD_BAD_USER_AGENT ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

if(isset($_POST["submitEditUserAgent"])) { $submitEditUserAgent = $_POST["submitEditUserAgent"]; }
if(isset( $_POST["submitInsertBadUserAgent"])) { $submitInsertBadUserAgent = $_POST["submitInsertBadUserAgent"]; }
if(isset($_POST["submitDeleteUserAgent"])) { $submitDeleteUserAgent = $_POST["submitDeleteUserAgent"]; }
if(isset($_POST["UserAgentName"])) { $UserAgentName = $_POST["UserAgentName"]; }
if(isset($_POST["UserAgentComment"])) { $UserAgentComment = $_POST["UserAgentComment"]; }
if(isset($_POST["UserAgentType"])) { $UserAgentType = $_POST["UserAgentType"]; }
if(isset($_POST["UserAgentID"])) { $UserAgentID = $_POST["UserAgentID"]; }
if(isset($_POST["DoInsertUserAgent"])) { $DoInsertUserAgent = $_POST["DoInsertUserAgent"]; }
if(isset($_POST["DoEditUserAgent"])) { $DoEditUserAgent = $_POST["DoEditUserAgent"]; }
if(isset($_POST["AnnulerInsertUserAgent"])) { $AnnulerInsertUserAgent = $_POST["AnnulerInsertUserAgent"]; }
if(isset($_POST["OkDelete"])) { $OkDelete = $_POST["OkDelete"]; }
if(isset($_POST["NoDelete"])) { $NoDelete = $_POST["NoDelete"]; }
if(isset($_POST["DoImportHTTPtableBadAgent"])) { $DoImportHTTPtableBadAgent = $_POST["DoImportHTTPtableBadAgent"]; }
if(isset($_POST["DoImportLocaltableBadAgent"])) { $DoImportLocaltableBadAgent = $_POST["DoImportLocaltableBadAgent"]; }

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
						echo MSG_TOOLS_CONFIRM_DELETE.'<br>'. stripslashes(htmlentities($UserAgentName)); ?>
						<br>
						<form name="Deleteconfirm" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
							<input name="type" type="hidden" value="add_bad_user_agent">
							<input name="UserAgentName" type="hidden" value="<?php echo htmlentities($UserAgentName); ?>" >
							<input name="UserAgentID" type="hidden" value="<?php echo $UserAgentID; ?>">
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
						// Do not display if in list Bots or list Bad agent
						$display_operating_system = true;
						$display_browsers = true;
						$display_bad_user_agent = true;
						$when_date = $when; // date d/m/Y or m/Y
						//echo 'Date : '.$when.'<br>'; // if empty display all
						//$display_Other = true; //echo display other & unknown OS & Browser
						include(FILENAME_DISPLAY_OS_BROWSER);
			
						unset($Tab_user_agent);
						unset($_SESSION['other_bot']);
						//Lecture et Affichage de la liste des bad user_agent
						$result_agent = mysql_query("select user_agent from ".TABLE_BAD_USER_AGENT.""); 
						while($row = mysql_fetch_array($result_agent)){
							$Tab_user_agent[] = $row['user_agent'];
						}
			
						$Other_browsers_os_bots = array_unique($Other_browsers_os_bots); // $Other_browsers_os_bots is result of include(FILENAME_DISPLAY_OS_BROWSER);
						usort($Other_browsers_os_bots,"CompareValeurs");
																																
						for($i = 0; $i <= count($Other_browsers_os_bots); $i++){
							if (isset($Other_browsers_os_bots[$i]) && !in_array($Other_browsers_os_bots[$i], $Tab_user_agent) && !is_crawler($Other_browsers_os_bots[$i]) && $Other_browsers_os_bots[$i] != ';' ) {
								if (trim($Other_browsers_os_bots[$i])) { $Unknown[] = $Other_type[$i]. ' ['.htmlentities($Other_browsers_os_bots[$i]).']'; }
							}
						}

						echo '
							<table style="'.$table_border_CSS.'">
								<tr>
									<td style="'.$table_data_CSS.' font-weight:lighter; text-align:left;">';
										if (isset($Unknown)) {
											echo '<center><strong>'.MSG_USER_AGENT_UNKNOWN_LIST.'</strong></center><br><br>';	//$Other_browsers_os_bots[$i] != ';' pollution ?
											for($i = 0; $i <= count($Unknown)-1; $i++){ // -1
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
								<td><input name="UserAgentName" type="text" size="30" value="'.stripslashes(htmlentities($UserAgentName)).'"></td>
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
		//mysql_query("delete from ".TABLE_BAD_USER_AGENT." where user_agent='".$UserAgentName."'"); 
		mysql_query("delete from ".TABLE_BAD_USER_AGENT." where id='".$UserAgentID."'"); 
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


	// ------------------------------ Update Table Crawler ---------------------------------------------
	if ( isset($DoImportHTTPtableBadAgent) || isset($DoImportLocaltableBadAgent) ) {
	
		unset($_SESSION['other_bot']);

		if (isset($DoImportHTTPtableBadAgent)) {
			//Important sinon warning avec mod_security sur serveur check - 25-03-2011
			$opts = array(
				'http'=> array(
				'method'=>   "GET",
				'header'=>    'Accept: text/html',
				'user_agent'=>    'allmystats-load_sql'
						)
					); 
							
			$ctx = stream_context_create($opts);

			$filename = "http://allmystats.wertronic.com/download/sql_update/allmystats_bad_user_agent.sql";
		} elseif (isset($DoImportLocaltableBadAgent) ) {
			$filename = 'includes/sql/allmystats_bad_user_agent.sql';		
		}

		if(!@file($filename)) {
			echo "<br><strong>Cannot open file $filename</strong><br>"; 
		} else {
		
			$sql = "DROP TABLE IF EXISTS ".TABLE_BAD_USER_AGENT.";";
			mysql_query($sql); 
			
			$sql = "CREATE TABLE IF NOT EXISTS `".TABLE_BAD_USER_AGENT."` (
				`id` int(5) NOT NULL auto_increment,
				`user_agent` varchar(255) NOT NULL default '',
				`info` varchar(255) NOT NULL default '',
				`type` char(1) NOT NULL default '',
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;";
				mysql_query($sql); 
		
			// Note : On ne peut pas inclure le CREATE TABLE dans le fichier SQL car le préfix de la table est définit par l'utilisateur
			// 'id_crawler' est en autoincrement donc il est supprimé du fichier import sql
			$SQL_query = 'INSERT INTO `'.TABLE_BAD_USER_AGENT.'` (`id`, `user_agent`, `info`, `type`) VALUES ';
			//$SQL_query = 'INSERT INTO `'.TABLE_BAD_USER_AGENT.'` (`user_agent`, `info`, `type`) VALUES ';
			mysql_import_file($filename, $SQL_query) ;	
			if($errmsg <> '') {
				echo '<br><strong>Import error : '.$errmsg.'</strong><br>';
				exit;
			} else {
				$import_success = true;
			}
		}
	}
	// -------------------------------------------------------------------------------------------------

//##################################################################################################
?>
<table width="75%" align="center" cellpadding="5" style="border: 1px solid #000000; border-collapse: collapse;" >
  <tr align="center">
    <td colspan="2" valign="top" style="border: 1px solid #000000; border-collapse: collapse;">
		<?php 
		echo MSG_TOOLS_BAD_AGENT_UPDATE_TABLE; 
		if(isset($import_success) && $import_success == true) {
			echo MSG_TOOLS_BAD_AGENT_IMPORT_SUCCESS;
		}
		?>
	</td>
  </tr>
  <tr>
    <td valign="top" style="border:1px solid #000000; border-collapse:collapse; text-align:center;">
		<?php echo MSG_TOOLS_BAD_AGENT_IMPORT_HTTP.'<br>'; 
			if(@ini_get('allow_url_fopen')){
				echo 'Last Update: '.GetRemoteLastModified ('http://allmystats.wertronic.com/download/sql_update/allmystats_bad_user_agent.sql').'<br>
				<form name="ImportBadAgent" method="post" action="'.$_SERVER['PHP_SELF'].'">
				<input name="type" type="hidden" value="add_bad_user_agent">
				<input name="DoImportHTTPtableBadAgent" type="hidden" value="1">
				<input class="submitDate" name="HTTPUpdatetableBadAgent" type="submit" value="HTTP Update table Bad User Agent" alt="HTTP Update table Bad User Agent" >
				</form>';
			} else {
				echo 'allow_url_fopen is disable in php.ini';
			}
		?>
	</td>
    <td valign="top" style="border: 1px solid #000000; border-collapse: collapse;">
			<?php echo MSG_TOOLS_BAD_AGENT_IMPORT_LOCAL; ?>
			<form name="ImportBadAgent" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input name="type" type="hidden" value="add_bad_user_agent">
			<input name="DoImportLocaltableBadAgent" type="hidden" value="1">
			Puis cliquez --> <input class="submitDate" name="LocalUpdatetableBadAgent" type="submit" value="Local Update table Bad User Agent" alt="Update Local table Bad User Agent" >
			</form>
	</td>
  </tr>
</table>
<br>
<?php
			//Lecture et Affichage de la liste des bad user_agent
			$result1 = mysql_query("select id, user_agent, info, type from ".TABLE_BAD_USER_AGENT.""); 
			if (!$result1) { //ex: si la table n'existe pas
				echo 'Impossible d\'exécuter la requête : ' . mysql_error();
				exit;
			}

			while($row = mysql_fetch_array($result1)){
				$Tab_bad_user_agent[] = array($row['id'], $row['user_agent'], stripslashes(($row['info'])), $row['type']);
			}

			//array_multisort ($Tab_bad_user_agent, SORT_DESC); 
			// Obtient le tableau de la 1ere colonne
			foreach ($Tab_bad_user_agent as $key => $row) {
				$name[$key]  = strtolower($row[1]);
			}
			// Tri les données par $name croissant
			array_multisort($name, SORT_ASC, $Tab_bad_user_agent);

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
					<th style="'.$td_data_CSS.'"> </th>
					<th style="'.$td_data_CSS.'">'.MSG_USER_AGENT.'</th>
					<th style="'.$td_data_CSS.'">'.MSG_COMMENTS.'</th>
					<th style="'.$td_data_CSS.'">'.MSG_TYPE.'</th>
					<th style="'.$td_data_CSS.'">'.MSG_ACTION.'</th>';

	for($nb = 0; $nb < count($Tab_bad_user_agent); $nb++){
		if ($Tab_bad_user_agent)
			echo '
			<tr>
			<td style="'.$td_data_CSS.'">&nbsp;'.($nb+1).'</td>
			<td style="'.$td_data_CSS.'">&nbsp;'.htmlentities($Tab_bad_user_agent[$nb][1]).'</td>
			<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_bad_user_agent[$nb][2].'</td>
			<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_bad_user_agent[$nb][3].'</td>


			<td style="'.$td_data_CSS.'">
			  <table style="border: 0px solid #000000; margin-left: auto; margin-right: auto;">
			  <tr>
				<td>
				<form name="formDelete" method="post" action="'.$_SERVER['PHP_SELF'].'">
					<input name="type" type="hidden" value="add_bad_user_agent">
					<input name="UserAgentID" type="hidden" value="'.$Tab_bad_user_agent[$nb][0].'">
					<input name="UserAgentName" type="hidden" value="'.stripslashes(htmlentities($Tab_bad_user_agent[$nb][1])).'">
					<input class="submitDate" name="submitDeleteUserAgent" type="submit" value="'.MSG_DELETE.'" alt="'.MSG_DELETE.'" >
				</form>
				</td>
				<td>
				<form name="formEdit" method="post" action="'.$_SERVER['PHP_SELF'].'">
					<input name="type" type="hidden" value="add_bad_user_agent">
					<input name="UserAgentName" type="hidden" value="'.stripslashes(htmlentities($Tab_bad_user_agent[$nb][1])).'">
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
