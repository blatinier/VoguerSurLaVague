<?php 
header('Content-Type: text/html; charset=utf-8');
/*
  -------------------------------------------------------------------------
 AllMyStats V1.75 - Statistiques site web - Web traffic analysis
 -------------------------------------------------------------------------
 Copyright (C) 2008-2010 - Herve Seywert
 copyright-GNU-xx.txt
 -------------------------------------------------------------------------
 Web:    http://allmystats.wertronic.com - http://www.wertronic.com
 -------------------------------------------------------------------------
*/

	if (function_exists('error_reporting')) {
		//error_reporting(E_ALL);
		error_reporting(E_ALL ^ E_NOTICE);
	}

	//------ cookie (ne pas compter ses propres visites) ---------------
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
//----------------------------------------------------------------------

	if(file_exists('config_allmystats.php')) {
		require_once('application_top.php');
		require "config_allmystats.php";
  		require('includes/mysql_tables.php');
		require ("includes/languages/".$langue."/main.php");
		require_once('includes/functions/general.php');

		include (FILENAME_LOGIN);
		mysql_connect($mysql_host,$mysql_login,$mysql_pass);
		mysql_select_db($mysql_dbnom);
	
		//Pour serveur php config windows-1251, il n'est pas nÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©cessaire de faire SET NAMES 'utf8 dans visiteurs.php (peut ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Âªtre que c'est dÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Â ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬Ãƒâ€¦Ã‚Â¡ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã…Â¡ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©finit dans la page principale ?
		//mais il faut le faire pour la lecture ici.(dans index_frame.php)
		//mysql_query("SET NAMES 'latin1'"); //OK // Default est en latin1 pour server en france si les tables sont en latin1
		mysql_query("SET NAMES 'utf8'"); //OK mais pas mettre de utf8 encode decode et les tables en utf8
	} else { //config_allmystats.php no exist --> Install
		header("Location: install/install.php");
		exit;
	}
//----------------------------------------------------------------------------------------------
?>
<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<title>AllMyStats - <? echo $site; ?> Web Traffic Analysis</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<?php

include(FILENAME_DISPLAY_HEADER);

switch($type){
	case "test_jetlag": require(FILENAME_UTC_SERVER_TEST); break;
	case "add_crawler": require(FILENAME_ADD_CRAWLER); break;
	case "add_bad_user_agent": require(FILENAME_ADD_BAD_USER_AGENT) ;break;
	case "Allmystats_tools": require(FILENAME_ADMIN_TOOLS); break;
	case "MyVisitsTools": require(FILENAME_ADMIN_VISITS_TOOL); break;
	case "HistoLoging": require(FILENAME_ADMIN_HISTO_LOGING); break;
	case "password": require(FILENAME_ADMIN_PASSW_TOOL); break;
	case "DetailsRobot": require(FILENAME_DETAILS_BOTS); break;
	case "cumul": require(FILENAME_MONTHLY_ARCHIVES_LIST); break;
	case "histo": require(FILENAME_HISTORY_MONTH_LIST); break;
	case "cumulpage": require(FILENAME_MONTHLY_ARCHIVES_EDIT); break;
	default: require(FILENAME_MAIN); break;
}
include(FILENAME_DISPLAY_FOOTER);
?>
</body>
</html>