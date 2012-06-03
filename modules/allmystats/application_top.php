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
*/
	// - Pas sur session de loging car peut être appelé de stats_in.php ---------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/application_top.php' ){ 
		header('Location: index.php'); //Si appelle direct de la page redirect
	}

session_start(); //Car sinon warning avec stats_in.php qui arrive après visiteur.php et session_start qui est déjà fait
session_save_path();

require_once("includes/filename.php");

?>