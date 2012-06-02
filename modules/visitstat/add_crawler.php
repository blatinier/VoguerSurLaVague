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
 $when sert pour by day - $mois pour by month
*/

include_once('application_top.php');

$InsertCrawler = $_POST["InsertCrawler"]; //Pour affichage formulaire (est dans tab_os_nav_robots.php)
$EditCrawler = $_POST["EditCrawler"];
$DeleteCrawler = $_POST["DeleteCrawler"];
$DoInsertCrawler = $_POST["DoInsertCrawler"];
$DoEditCrawler = $_POST["DoEditCrawler"];
$DoAnnulerInsertCrawler = $_POST["DoAnnulerInsertCrawler"];

$BotName = $_POST["BotName"];
$BotParentName = $_POST["BotParentName"];
$BotUrl = $_POST["BotUrl"];
$BotComment = $_POST["BotComment"];
$BotID = $_POST["BotID"];

$OkDelete = $_POST["OkDelete"];
$NoDelete = $_POST["NoDelete"];

	if (isset($DeleteCrawler) || isset($EditCrawler) ){
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
					//------------------------ Delete crawler ----------------------------------
					//Confirm Delete crawler
					if (isset($DeleteCrawler)){
						echo $MSG_TOOLS_CONFIRM_DELETE.':<br>'.$BotName ;?>
						<br>
						<form name="Deleteconfirm" method="post" action="<?PHP_SELF;?>">
							<input name="type" type="hidden" value="add_crawler">
							<input name="when" type="hidden" value="<? echo $when; ?>">
							<input name="mois" type="hidden" value="<? echo $mois; ?>">				
							<input name="BotName" type="hidden" value="<?php echo $BotName; ?>" >
							<input name="BotParentName" type="hidden"  value="<?php echo $BotParentName; ?>" >
							<input class="submitDate" name="OkDelete" type="submit" value="<? echo $MSG_DELETE; ?>" alt="<? echo $MSG_DELETE; ?>" >
							<input class="submitDate" name="NoDelete" type="submit" value="<? echo $MSG_CANCEL; ?>" alt="<? echo $MSG_CANCEL; ?>" >
						</form>
			 <?php } 

					//------------------------ Insert crawler est effectué dans tab_os_nav_robots.php ----------------------------------
						
					//------------------------ Editer crawler ----------------------------------
					if (isset($EditCrawler)){ ?>
						<form name="form1" method="post" action="<?PHP_SELF;?>">
							<table border="0" align="center" cellpadding="3" cellspacing="0">
							  <tr>
								<td align="center"><? echo $MSG_TOOLS_BOT_NAME; ?></td>
								<td align="center"><? echo $MSG_TOOLS_BOT_PARENT_NAME; ?></td>
								<td align="center"><? echo $MSG_TOOLS_BOT_URL; ?></td>
								<td align="center"><? echo $MSG_COMMENTS; ?></td>
							  </tr>
							  <tr>
								<td align="center"><input name="BotName" type="text" size="20" value="<?php echo $BotName; ?>"></td>
								<td align="center"><input name="BotParentName" type="text" value="<?php echo $BotParentName; ?>"></td>
								<td align="center"><input name="BotUrl" type="text" value="<?php echo $BotUrl; ?>"></td>
								<td align="center"><input name="BotComment" type="text" value="<?php echo $BotComment; ?>"></td>
							  </tr>
						  </table>
							<input name="type" type="hidden" value="add_crawler">
							<input name="when" type="hidden" value="<? echo $when; ?>">
							<input name="mois" type="hidden" value="<? echo $mois; ?>">				
							<input name="BotID" type="hidden" value="<?php echo $BotID; ?>" >
							<input class="submitDate" name="DoEditCrawler" type="submit" value="<? echo 'OK'; ?>" alt="<? echo 'OK'; ?>" >
							<input class="submitDate" name="AnnulerModifierCrawler" type="submit" value="<? echo $MSG_CANCEL; ?>" alt="<? echo $MSG_CANCEL; ?>" >
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
		if(mysql_query("delete from ".TABLE_CRAWLER." where org_name='".$BotParentName."' and bot_name='".$BotName."' ")) { 
			echo '<br>'.$MSG_BOT.': '.$BotName.' '.$BotParentName. $MSG_TOOLS_DELETE_SUCCESS.'<br><br>' ;
			//$_SESSION['Autres_robots'] = "";
			unset($_SESSION['Autres_robots']);
		} else {
			echo "Error";
		}
	}
	//Do insert
	if (isset($DoInsertCrawler)){
		mysql_query("insert into ".TABLE_CRAWLER." values ('','','".$BotParentName."','".$BotName."','".$BotUrl."','".$BotComment."','')");
		//Supprime de la liste des robots non référencés dans la base
		for($i=0;$i<count($_SESSION['Autres_robots']);$i++){
			if ( strstr($_SESSION['Autres_robots'][$i], $BotName)) {
				echo '<br>'.$MSG_BOT.': '.$BotName. $MSG_TOOLS_ADD_SUCCESS .'<br><br>' ;
				$_SESSION['Autres_robots'][$i]="";
			}
		}
		$Botsave = $BotName ;
		$BotName = ''; $BotParentName = '';	$BotUrl = ''; $BotComment = '';
	}
	//Do edit
	if (isset($DoEditCrawler)){
		mysql_query("update ".TABLE_CRAWLER." set org_name='".$BotParentName."', bot_name='".$BotName."', crawler_url='".$BotUrl."', crawler_info='".$BotComment."'  where id_crawler='".$BotID."' ");
		echo '<br>'.$MSG_BOT.': '.$BotName.$MSG_TOOLS_MODIFIE_SUCCESS.'<br><br>' ;
		$BotName = ''; $BotParentName = '';	$BotUrl = ''; $BotComment = '';
	}


	if (isset($DoAnnulerInsertCrawler)){
		$BotName = ''; $BotParentName = '';	$BotUrl = ''; $BotComment = '';
	}

//##################################################################################################
//echo "Entrée Lecture TABLE_CRAWLER OK<br>";
			//Lecture et Affichage de la liste de robots
			$result1=mysql_query("select id_crawler, bot_name, org_name, crawler_url, crawler_info from ".TABLE_CRAWLER." "); 
			if (!$result1) { //ex: si la table n'existe pas
				echo 'Impossible d\'exécuter la requête : ' . mysql_error();
				exit;
			}

			while($row=mysql_fetch_array($result1)){
				$Tab_crawlers[] = array($row['bot_name'],$row['org_name'],$row['crawler_url'],$row['crawler_info'],$row['id_crawler']);
			}

			array_multisort ($Tab_crawlers, SORT_DESC); 
?>
<!--	
			<form name="form1" method="post" action="<?PHP_SELF;?>">
				<input name="when" type="hidden" value="<? echo $when; ?>">
			    <input class="submit" name="detail_ref" type="submit" value="<? echo $MSG_RETOUR; ?>" alt="<? echo $MSG_RETOUR; ?>" >
			</form><br><br>
-->

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->

      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_TOOLS_LIST_ROBOT; ?>
				<br><form name="forminsertcrawler" method="post" action="<?PHP_SELF;?>#focusforminsert">
				<input name="type" type="hidden" value="add_crawler">
				<input name="when" type="hidden" value="<? echo $when; ?>">
				<input class="submitDate" name="InsertCrawler" type="submit" value="<? echo $MSG_ADD; ?>" alt="<? echo $MSG_ADD; ?>" >
				</form>
		  </TH>
          </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=2 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
              <TR>
					<TH><? echo $MSG_TOOLS_BOT_NAME; ?></TH>
					<TH><? echo $MSG_TOOLS_BOT_PARENT_NAME; ?></TH>
					<TH><? echo $MSG_TOOLS_BOT_URL; ?></TH>
					<TH><? echo $MSG_COMMENTS; ?></TH>
					<TH><? echo $MSG_ACTION; ?></TH>

<?php
	for($nb=0;$nb<count($Tab_crawlers);$nb++){
		if ($Tab_crawlers) echo "<tr>
		<td>&nbsp;".$Tab_crawlers[$nb][0]."</td>
		<td>&nbsp;".$Tab_crawlers[$nb][1]."</td>
		<td>&nbsp;".$Tab_crawlers[$nb][2]."</td>
		<td>&nbsp;".$Tab_crawlers[$nb][3]."</td>
		";

?>
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>
				<form name="formDelete" method="post" action="<?PHP_SELF;?>#">
					<input name="type" type="hidden" value="add_crawler">
					<input name="when" type="hidden" value="<? echo $when; ?>">
					<input name="mois" type="hidden" value="<? echo $mois; ?>">				
					<input name="BotName" type="hidden" value="<?php echo $Tab_crawlers[$nb][0]; ?>">
					<input name="BotParentName" type="hidden" value="<?php echo $Tab_crawlers[$nb][1]; ?>">
					<input class="submitDate" name="DeleteCrawler" type="submit" value="<? echo $MSG_DELETE; ?>" alt="<? echo $MSG_DELETE; ?>" >
				</form>
				</td>
				<td>
				<form name="formEdit" method="post" action="<?PHP_SELF;?>#">
					<input name="type" type="hidden" value="add_crawler">
					<input name="when" type="hidden" value="<? echo $when; ?>">
					<input name="mois" type="hidden" value="<? echo $mois; ?>">				
					<input name="BotName" type="hidden" value="<?php echo $Tab_crawlers[$nb][0]; ?>">
					<input name="BotParentName" type="hidden" value="<?php echo $Tab_crawlers[$nb][1]; ?>">
					<input name="BotUrl" type="hidden" value="<?php echo $Tab_crawlers[$nb][2]; ?>">
					<input name="BotComment" type="hidden" value="<?php echo $Tab_crawlers[$nb][3]; ?>">
					<input name="BotID" type="hidden" value="<?php echo $Tab_crawlers[$nb][4]; ?>">
				<input class="submitDate" name="EditCrawler" type="submit" value="<? echo $MSG_EDIT; ?>" alt="<? echo $MSG_EDIT; ?>" >
				</form>
				</td>
			  </tr>
			</table>
		</td>
<?
	}
?>
 </TBODY></TABLE><!-- Rows END -->
  				<div align="center">
				<br>
				<form name="forminsertcrawler" method="post" action="<?PHP_SELF;?>#focusforminsert">
				<input name="type" type="hidden" value="add_crawler">
				<input name="when" type="hidden" value="<? echo $when; ?>">
				<input name="mois" type="hidden" value="<? echo $mois; ?>">				
				<input class="submitDate" name="InsertCrawler" type="submit" value="<? echo $MSG_ADD; ?>" alt="<? echo $MSG_ADD; ?>" >
				</form>
				</div> 
 
 </TD></TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>
<?
//-------------------------------- AFF OS navigateurs robots -----------------------

$AfficheOS = false;
$AfficheNav = false;
$AfficheRobots = false;
$affiche_only_other_bots = true;

	if (isset($DoInsertCrawler)){
				echo '<br>'.$MSG_BOT.': '.$Botsave. $MSG_TOOLS_ADD_SUCCESS .'<br>' ;
	}

	$result = mysql_query("select * from ".TABLE_VISITEUR." where date like '%$mois'");// Pour bots non définis
	$nbr_result = mysql_num_rows($result);

	include('tab_os_nav_robots.php');
?>
