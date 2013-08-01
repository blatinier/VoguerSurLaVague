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
 $when used for by day - $mois pour by month
*/

	// ---------------- Should not be called directly -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/'.FILENAME_ADD_CRAWLER ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

include_once('application_top.php');

//------------------------------------------------
if(isset($_POST["InsertCrawler"])) { $InsertCrawler = $_POST["InsertCrawler"];  } //Pour affichage formulaire (est dans FILENAME_DISPLAY_BOTS)
if(isset( $_POST["EditCrawler"])) { $EditCrawler = $_POST["EditCrawler"]; }
if(isset($_POST["DeleteCrawler"])) { $DeleteCrawler = $_POST["DeleteCrawler"]; }
if(isset($_POST["DoInsertCrawler"])) { $DoInsertCrawler = $_POST["DoInsertCrawler"]; }
if(isset($_POST["DoEditCrawler"])) { $DoEditCrawler = $_POST["DoEditCrawler"]; }
if(isset($_POST["DoAnnulerInsertCrawler"])) { $DoAnnulerInsertCrawler = $_POST["DoAnnulerInsertCrawler"]; }
if(isset($_POST["BotName"])) { $BotName = $_POST["BotName"]; }
if(isset($_POST["BotParentName"])) { $BotParentName = $_POST["BotParentName"]; }
if(isset($_POST["BotUrl"])) { $BotUrl = $_POST["BotUrl"]; }
if(isset($_POST["BotComment"])) { $BotComment = $_POST["BotComment"]; }
if(isset($_POST["BotID"])) { $BotID = $_POST["BotID"]; }
if(isset($_POST["OkDelete"])) { $OkDelete = $_POST["OkDelete"]; }
if(isset($_POST["NoDelete"])) { $NoDelete = $_POST["NoDelete"]; }
if(isset($_POST["DoImportHTTPtableCrawler"])) { $DoImportHTTPtableCrawler = $_POST["DoImportHTTPtableCrawler"]; }
if(isset($_POST["DoImportLocaltableCrawler"])) { $DoImportLocaltableCrawler = $_POST["DoImportLocaltableCrawler"]; }
//------------------------------------------------
	
	// ------------------------------ Update Table Crawler ---------------------------------------------
	if ( isset($DoImportHTTPtableCrawler) || isset($DoImportLocaltableCrawler) ) {
	
		unset($_SESSION['other_bot']);
		
		if (isset($DoImportHTTPtableCrawler)) {
			//Important sinon warning avec mod_security sur serveur check - 25-03-2011
			$opts = array(
				'http'=> array(
				'method'=>   "GET",
				'header'=>    'Accept: text/html',
				'user_agent'=>    'allmystats-load_sql'
						)
					); 
							
			$ctx = stream_context_create($opts);

			$filename = "http://allmystats.wertronic.com/download/sql_update/allmystats_crawler.sql";
		} elseif (isset($DoImportLocaltableCrawler) ) {
			$filename = 'includes/sql/allmystats_crawler.sql';		
		}

		if(!@file($filename)) {
			echo "<br><strong>Cannot open file $filename</strong><br>"; 
		} else {
		
			$sql = "DROP TABLE IF EXISTS ".TABLE_CRAWLER.";";
			mysql_query($sql); 
			
			$sql = "CREATE TABLE IF NOT EXISTS `".TABLE_CRAWLER."` (
				`id_crawler` smallint(5) NOT NULL auto_increment,
				`crawler_user_agent` varchar(255) collate utf8_unicode_ci default NULL,
				`org_name` varchar(50) collate utf8_unicode_ci default NULL,
				`bot_name` varchar(50) collate utf8_unicode_ci default NULL,
				`crawler_url` varchar(255) collate utf8_unicode_ci default NULL,
				`crawler_info` varchar(255) collate utf8_unicode_ci default NULL,
				`crawler_ip` varchar(16) collate utf8_unicode_ci default NULL,
				PRIMARY KEY  (`id_crawler`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;";
				mysql_query($sql); 
		
			// Note : On ne peut pas inclure le CREATE TABLE dans le fichier SQL car le préfix de la table est définit par l'utilisateur
			// 'id_crawler' est en autoincrement donc il est supprimé du fichier import sql
			$SQL_query = 'INSERT INTO `'.TABLE_CRAWLER.'`(`id_crawler`, `crawler_user_agent`, `org_name`, `bot_name`, `crawler_url`, `crawler_info`, `crawler_ip`) VALUES ';
			//$SQL_query = 'INSERT INTO `'.TABLE_CRAWLER.'`(`crawler_user_agent`, `org_name`, `bot_name`, `crawler_url`, `crawler_info`, `crawler_ip`) VALUES ';
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

	if (isset($DeleteCrawler) || isset($EditCrawler) ){

		echo "
		<table style=\"".$table_border_CSS."\">
		  <tr>
			<td>
			  <table style=\"".$table_frame_CSS."\">
				<tr>
				  <td style=\"".$table_data_CSS."\">";

					//------------------------ Delete crawler ----------------------------------
					//Confirm Delete crawler
					if (isset($DeleteCrawler)){
						echo '<font color=#FF0000>'.MSG_TOOLS_CONFIRM_DELETE.':</font><br>'.$BotName ;?>
						<br>
						<form name="Deleteconfirm" method="post" action="<?php $_SERVER['PHP_SELF'];?>">
							<input name="type" type="hidden" value="add_crawler">
							<input name="when" type="hidden" value="<?php echo $when; ?>">
							<input name="mois" type="hidden" value="<?php echo $mois; ?>">				
							<input name="BotName" type="hidden" value="<?php echo $BotName; ?>" >
							<input name="BotParentName" type="hidden"  value="<?php echo $BotParentName; ?>" >
							<input class="submitDate" name="OkDelete" type="submit" value="<?php echo MSG_DELETE; ?>" alt="<?php echo MSG_DELETE; ?>" >&nbsp;&nbsp;
							<input class="submitDate" name="NoDelete" type="submit" value="<?php echo MSG_CANCEL; ?>" alt="<?php echo MSG_CANCEL; ?>" >
						</form>	 <?php 
					} 

					//------------------------ Insert crawler est effectué dans FILENAME_DISPLAY_BOTS ----------------------------------

					//------------------------ Editer crawler ----------------------------------
					if (isset($EditCrawler)){ 
						echo MSG_NOTE_ADD_BOT;
						?>						
						<div align="center">
						<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<?php
							echo '
							<table style="'.$table_data_CSS.'">
							  <tr>
								<th style="'.$td_data_CSS.'">'.MSG_BOT_NAME.'</td>
								<th style="'.$td_data_CSS.'">'.MSG_ADMIN_BOT_PARENT_NAME.'</td>
								<th style="'.$td_data_CSS.'">'.MSG_TOOLS_BOT_URL.'</td>
								<th style="'.$td_data_CSS.'">'.MSG_COMMENTS.'</td>
							  </tr>'; ?>
							  <tr>
								<td align="center"><input name="BotName" type="text" size="20" value="<?php echo mysql_real_escape_string($BotName); ?>"></td>
								<td align="center"><input name="BotParentName" type="text" value="<?php echo mysql_real_escape_string($BotParentName); ?>"></td>
								<td align="center"><input name="BotUrl" type="text" value="<?php echo mysql_real_escape_string($BotUrl); ?>"></td>
								<td align="center"><input name="BotComment" type="text" value="<?php echo mysql_real_escape_string($BotComment); ?>"></td>
							  </tr>
						  </table>
	
							<input name="type" type="hidden" value="add_crawler">
							<input name="when" type="hidden" value="<?php echo $when; ?>">
							<input name="mois" type="hidden" value="<?php echo $mois; ?>">				
							<input name="BotID" type="hidden" value="<?php echo $BotID; ?>" >
							<input class="submitDate" name="DoEditCrawler" type="submit" value="<?php echo 'OK'; ?>" alt="<?php echo 'OK'; ?>" >&nbsp;&nbsp;
							<input class="submitDate" name="AnnulerModifierCrawler" type="submit" value="<?php echo MSG_CANCEL; ?>" alt="<?php echo MSG_CANCEL; ?>" >
						</form>
						</div>
				<?php } 
					//-----------------------------------------------------------------------
				?>
				 </td>
		</tr>
</table></td></tr></table></td></tr></table><br />
		<?php
	}
//############################## Action ###########################################################
	//Do delete
	if (isset($OkDelete)){
		if(mysql_query("delete from ".TABLE_CRAWLER." where org_name='".$BotParentName."' and bot_name='".$BotName."' ")) { 
			echo '<br><div align="center"><font color="#009933"><b>'.MSG_BOTS.': '.$BotName.' '.$BotParentName. MSG_TOOLS_DELETE_SUCCESS.'</b></font></div><br><br>' ;
			unset($_SESSION['other_bot']);
		} else {
			echo "Error";
		}
	}
	//Do insert
	if (isset($DoInsertCrawler)){
		mysql_query("insert into ".TABLE_CRAWLER." values ('','','".$BotParentName."','".$BotName."','".$BotUrl."','".trim($BotComment)."','')");
		// Supprime de la liste des robots non référencés dans la base
		for($i=0; $i < count($_SESSION['other_bot']); $i++){
			if ( strstr($_SESSION['other_bot'][$i], $BotName)) {
				//echo '<br><div align="center"><font color="#009933"><b>'.MSG_BOTS.': '.$BotName. MSG_TOOLS_ADD_SUCCESS .'</b></font></div><br><br>' ;
				$_SESSION['other_bot'][$i] = "";
			}
		}
		// Msg success
		echo '<br><div align="center"><font color="#009933"><b>'.MSG_BOTS.': '.$BotName. MSG_TOOLS_ADD_SUCCESS .'</b></font></div><br><br>' ;

		$Botsave = $BotName ;
		$BotName = ''; $BotParentName = '';	$BotUrl = ''; $BotComment = '';
	}
	//Do edit
	if (isset($DoEditCrawler)){
		mysql_query("update ".TABLE_CRAWLER." set org_name='".$BotParentName."', bot_name='".$BotName."', crawler_url='".$BotUrl."', crawler_info='".(trim($BotComment))."'  where id_crawler='".$BotID."' ");
		echo '<br><div align="center"><font color="#009933"><b>'.MSG_BOTS.': '.$BotName.MSG_TOOLS_MODIFIE_SUCCESS.'</b></font></div><br><br>' ;
		$BotName = ''; $BotParentName = '';	$BotUrl = ''; $BotComment = '';
	}

	if (isset($DoAnnulerInsertCrawler)){
		$BotName = ''; $BotParentName = '';	$BotUrl = ''; $BotComment = '';
	}

//##################################################################################################

?>
<table width="75%" align="center" cellpadding="5" style="border: 1px solid #000000; border-collapse: collapse;" >
  <tr align="center">
    <td colspan="2" valign="top" style="border: 1px solid #000000; border-collapse: collapse;">
		<?php 
		echo MSG_TOOLS_BOT_UPDATE_TABLE; 
		if(isset($import_success) && $import_success == true) {
			echo MSG_TOOLS_BOT_IMPORT_SUCCESS;
		}
		?>
	</td>
  </tr>
  <tr>
    <td valign="top" style="border:1px solid #000000; border-collapse:collapse; text-align:center;">
		<?php 
			echo MSG_TOOLS_BOT_IMPORT_HTTP.'<br>'; 
			if(@ini_get('allow_url_fopen')){
				echo 'Last Update: '.GetRemoteLastModified ('http://allmystats.wertronic.com/download/sql_update/allmystats_crawler.sql').'<br>
				<form name="ImportCrawler" method="post" action="'.$_SERVER['PHP_SELF'].'">
				<input name="type" type="hidden" value="add_crawler">
				<input name="DoImportHTTPtableCrawler" type="hidden" value="1">
				<input class="submitDate" name="HTTPUpdatetableCrawler" type="submit" value="HTTP Update table Crawler" alt="Update table Crawler" >
				</form>';
			} else {
				echo 'allow_url_fopen is disable in php.ini';
			}
		?>

	</td>
    <td valign="top" style="border: 1px solid #000000; border-collapse: collapse;">
			<?php echo MSG_TOOLS_BOT_IMPORT_LOCAL; ?>
			<form name="ImportCrawler" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input name="type" type="hidden" value="add_crawler">
			<input name="DoImportLocaltableCrawler" type="hidden" value="1">
			Puis cliquez --> <input class="submitDate" name="LocalUpdatetableCrawler" type="submit" value="Local Update table Crawler" alt="Update Local table Crawler" >
			</form>

	</td>

  </tr>
</table>
<br>
<?php

			// Lecture et Affichage de la liste de robots
			$result1 = mysql_query("select id_crawler, bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER." "); 
			if (!$result1) { //ex: si la table n'existe pas
				echo 'Impossible d\'exécuter la requête : ' . mysql_error();
				exit;
			}

			while($row = mysql_fetch_array($result1)){
				$Tab_crawlers[] = array($row['bot_name'], $row['org_name'], $row['crawler_url'], stripslashes(($row['crawler_info'])), $row['id_crawler']);
			}

			// Obtient le tableau de la 1ere colonne
			foreach ($Tab_crawlers as $key => $row) {
				$name[$key]  = strtolower($row[0]);
			}
			// Tri les données par $name croissant
			array_multisort($name, SORT_ASC, $Tab_crawlers);

		echo '
		<table style="'.$table_border_CSS.' width: 80%;">
		  <tr>
			<td>
			  <table style="'.$table_frame_CSS.'">
        <tr>
          <th style="'.$table_title_CSS.'">
		  		'.MSG_ADMIN_TOOLS_BOTS_LIST; ?>
				<br><form name="forminsertcrawler" method="post" action="<?php $_SERVER['PHP_SELF'];?>#focusforminsert">
				<input name="type" type="hidden" value="add_crawler">
				<input name="when" type="hidden" value="<?php echo $when; ?>">
				<input class="submitDate" name="InsertCrawler" type="submit" value="<?php echo MSG_ADD; ?>" alt="<?php echo MSG_ADD; ?>" >
</form>
		  </th>
        <?php
		echo '
		</tr>
        <tr>
          <td colspan="2">
           <table style="'.$table_data_CSS.'">
              <tr>
					<th style="'.$td_data_CSS.'"> </th>
					<th style="'.$td_data_CSS.'">'.MSG_BOT_NAME.'</th>
					<th style="'.$td_data_CSS.'">'.MSG_ADMIN_BOT_PARENT_NAME.'</th>
					<th style="'.$td_data_CSS.'">'.MSG_TOOLS_BOT_URL.'</th>
					<th style="'.$td_data_CSS.'">'.MSG_COMMENTS.'</th>
					<th style="'.$td_data_CSS.'">'.MSG_ACTION.'</th>';


					//###################### Display TABLE CRAWLER #####################################
					for($nb=0 ;$nb < count($Tab_crawlers); $nb++){
						if ($Tab_crawlers) 
						echo '
						<tr>
						<td style="'.$td_data_CSS.'">&nbsp;'.($nb+1).'</td>
						<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_crawlers[$nb][0].'</td>
						<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_crawlers[$nb][1].'</td>
						<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_crawlers[$nb][2].'</td>
						<td style="'.$td_data_CSS.'">&nbsp;'.$Tab_crawlers[$nb][3].'</td>

						<td style="'.$td_data_CSS.'">
							<table style="border: 0px solid #000000; margin-left: auto; margin-right: auto;">
							  <tr>
								<td>
								<form name="formDelete" method="post" action="'.$_SERVER['PHP_SELF'].'#">
									<input name="type" type="hidden" value="add_crawler">
									<input name="when" type="hidden" value="'.$when.'">
									<input name="mois" type="hidden" value="'.$mois.'">				
									<input name="BotName" type="hidden" value="'.$Tab_crawlers[$nb][0].'">
									<input name="BotParentName" type="hidden" value="'.$Tab_crawlers[$nb][1].'">
									<input class="submitDate" name="DeleteCrawler" type="submit" value="'.MSG_DELETE.'" alt="'.MSG_DELETE.'" >
								</form>
								</td>
								<td>
								<form name="formEdit" method="post" action="'.$_SERVER['PHP_SELF'].'#">
									<input name="type" type="hidden" value="add_crawler">
									<input name="when" type="hidden" value="'.$when.'">
									<input name="mois" type="hidden" value="'.$mois.'">				
									<input name="BotName" type="hidden" value="'.$Tab_crawlers[$nb][0].'">
									<input name="BotParentName" type="hidden" value="'.$Tab_crawlers[$nb][1].'">
									<input name="BotUrl" type="hidden" value="'.$Tab_crawlers[$nb][2].'">
									<input name="BotComment" type="hidden" value="'.$Tab_crawlers[$nb][3].'">
									<input name="BotID" type="hidden" value="'.$Tab_crawlers[$nb][4].'">
								<input class="submitDate" name="EditCrawler" type="submit" value="'.MSG_EDIT.'" alt="'.MSG_EDIT.'" >
								</form>
								</td>
							  </tr>
							</table>
						</td>';

					}
				?>
 </table>
  				<div align="center">
				<br>
				<form name="forminsertcrawler" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>#focusforminsert">
				<input name="type" type="hidden" value="add_crawler">
				<input name="when" type="hidden" value="<?php echo $when; ?>">
				<input name="mois" type="hidden" value="<?php echo $mois; ?>">				
				<input class="submitDate" name="InsertCrawler" type="submit" value="<?php echo MSG_ADD; ?>" alt="<?php echo MSG_ADD; ?>" >
				</form>
				</div> 
 
</td></tr></table></td></tr></table><br />
<?php

		if ($when) {
			$when_date = $when;
		} elseif ($mois) {
			$when_date = $mois;		
		} // Si aucun tous qui ne sont pas archivés
		
		$display_bots = false;
		$affiche_only_other_bots = true;
		include(FILENAME_DISPLAY_BOTS);
		echo $show_page_os_nav_robots;
?>
