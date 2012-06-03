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

	// ---------------- Ne doit pas être appelé directement -------------------
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/logout.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

include_once('application_top.php');

	//remove all the variables in the session
	session_unset();
	
	// destroy the session
	session_destroy(); 
	header("Location: ".FILENAME_INDEX_FRAME);
?>