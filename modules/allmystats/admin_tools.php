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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/admin_tools.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

echo '
	<table style="'.$table_border_CSS.'">
		<tr>
			<td>
			  <table style="'.$table_frame_CSS.'">
				<tr>
					 <td style="'.$table_title_CSS.'">'
						.MSG_ADMIN_TOOLS_MENU.'
					 </td>
				</tr>
				<tr>
				  <td colspan="2">
					<table style="'.$table_data_CSS.'">
					  <tr>
						<td>';
							echo '<br><div style="text-align:center;">'.MSG_ADMIN_DOWNLOAD_GEOIP_DAT.'<hr align=\"center\" width=\"50%\" noshade></div><br>';

							include_once("includes/languages/".$langue."/doc_install_code.php"); ?>
						</td>
					</table>
				</table>
			</td>
		</tr>
	</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><br :>
</p>
