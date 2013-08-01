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
	// - Pas sur session de loging car peut être appelé de stats_in.php ---------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/application_top.php' ){ 
		header('Location: index.php'); // Si appelle direct de la page redirect
	}


	// PHP5.4 Suppress DateTime warnings (if not set in php.ini) => date_default_timezone_set -> UTC
	if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")) {
		date_default_timezone_set(@date_default_timezone_get());
	}
				
session_start(); //Car warning avec stats_in.php qui arrive après visiteur.php et session_start qui est déjà fait
session_save_path();

require_once("includes/filename.php");

?>