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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/admin_tools.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------

	//--------------- Test si Ip non comptabilisées ------------------------- 
	$IP_PC_Exclue ='';
 	$Tab_element_Ip = explode(".",$_SERVER['REMOTE_ADDR']);

 	for($i=0;$i<count($IpExlues);$i++){
		$Tab_element_IpEx = explode(".",$IpExlues[$i]);
		$Nb = count($Tab_element_IpEx);

		for ($Ni=0; $Ni<count($Tab_element_IpEx); $Ni++) {
			if (trim($Tab_element_IpEx[$Ni])) { 
				$Ip_a_tester .= $Tab_element_Ip[$Ni].'.';
			} else { //si . et rien
				$IpExlues[$i] = substr($IpExlues[$i],0,strlen($IpExlues[$i])-1);
			}
		}
		$Ip_a_tester = substr($Ip_a_tester,0,strlen($Ip_a_tester)-1);

		if(@stristr($IpExlues[$i], $Ip_a_tester)) {
			$IP_PC_Exclue = $_SERVER['REMOTE_ADDR'];
		}
		
		$Ip_a_tester ='';
	}
//---------------------------------------------------------------------


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
