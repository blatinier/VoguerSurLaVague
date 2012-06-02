<?php
include_once('application_top.php');

	//remove all the variables in the session
	session_unset();
	
	// destroy the session
	session_destroy(); 
	header("Location: index_frame.php");
?>