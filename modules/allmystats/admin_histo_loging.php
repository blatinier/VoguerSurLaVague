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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/admin_histo_loging.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

echo '
	<table style="'.$table_border_CSS.'">
		<tr>
			<td>
			  <table style="'.$table_frame_CSS.'">
				<tr>
					 <td style="'.$table_title_CSS.'">
						History Loging
					 </td>
				</tr>
				<tr>
				  <td colspan="2">
					<table style="'.$table_data_CSS.'" cellpadding="10">
					  <tr>
						<td>';
							if (is_file('cache/loging_log.php')) {
								include_once("cache/loging_log.php"); 
							} else {
								echo 'No File login log';
							}
						echo '
						</td>
					</table>
				</table>
			</td>
		</tr>
	</table>
<p>&nbsp;</p>';


		if(!$error_login) {
			$error_login = 5;
		}

echo '
	<table style="'.$table_border_CSS.'">
		<tr>
			<td>
			  <table style="'.$table_frame_CSS.'">
				<tr>
					 <td style="'.$table_title_CSS.'">
						History Multiple login errors > '.$error_login.'
					 </td>
				</tr>
				<tr>
				  <td colspan="2">
					<table style="'.$table_data_CSS.'" cellpadding="10">
					  <tr>
						<td>';
							if (is_file('cache/loging_error.php')) {
								include_once('cache/loging_error.php'); 
							} else {
								echo 'No Multiple login errors';
							}
							echo '
						</td>
					</table>
				</table>
			</td>
		</tr>
	</table>
<p>&nbsp;</p>';
?>

