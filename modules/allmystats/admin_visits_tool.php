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
	if(strrchr($_SERVER['PHP_SELF'] , '/' ) == '/admin_visits_tool.php' ){ 
		header('Location: index.php');
	}
	// ------------------------------------------------------------------------


$ModifCookie = $_POST["ModifCookie"];

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
						.MSG_INSTALL_COOKIE.'
					 </td>
				</tr>
				<tr>
				  
				  <td colspan="2">

					<table style="'.$table_data_CSS.'" cellpadding="10" cellspacing="0">
					  <tr>
						<td>'; // Debut Tableau tjrs 
							
							if ($ModifCookie == 'install') {
								echo '
								<div style="text-align:center;">'
									.MSG_COOKIE_INSTALLED.'
									<form name="VerifCookie" method="post" action="'.FILENAME_INDEX_FRAME.'">
										<input type="hidden" name="type" value="MyVisitsTools">
										<input type="hidden" name="ModifCookie" value="">
										<input class="submit" name="submitVerif" type="submit" value="'.MSG_CHECK.'" alt="'.MSG_CHECK.'" title="'.MSG_CHECK.'">
									</form>
								</div>
						</td>
					   </tr>
					</table>

				   </td>							  
				 </tr>
				</table>

			  </td>
			</tr>
		</table>
		<br>';
		exit; 
							} elseif ($ModifCookie == 'delete') {
								echo '
								<div style="text-align:center;">'
									.MSG_COOKIE_DELETED.'
									<form name="VerifCookie" method="post" action="'.FILENAME_INDEX_FRAME.'">
										<input type="hidden" name="type" value="MyVisitsTools">
										<input type="hidden" name="ModifCookie" value="">
										<input class="submit" name="submitVerif" type="submit" value="'.MSG_CHECK.'" alt="'.MSG_CHECK.'" title="'.MSG_CHECK.'">
									</form>
								</div>
						</td>
					   </tr>
					</table>
				   </td>							  
				 </tr>
				</table>
			  </td>
			</tr>
		</table>
		<br>';
		exit; 
							} 

		if (!$_COOKIE["AllMyStatsVisites"] == 'No record this' && $IP_PC_Exclue =='') {
					echo "<font color=#FF0000><strong>".MSG_VISITS_FROMTHIS_RECORDED."</strong></font>
					<br><br>".MSG_VISITS_FROMTHIS_RECORDED_DETAILS; ?>
					</b><br><br>
			  
					<strong><?php echo MSG_IF_BROWSER_ACCEPT_COOKIES; ?></strong>
					<form name="form1" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
							<input type="hidden" name="type" value="MyVisitsTools">
							<input type="hidden" name="ModifCookie" value="install">
							<input class="submit" name="SetCookie" type="submit" value="<?php echo MSG_INSTALL_COOKIE; ?>" alt="<?php echo MSG_INSTALL_COOKIE; ?>" title="<?php echo MSG_INSTALL_COOKIE; ?>">
					</form><br><br><br><?php
		 } else {
	  		echo '
				<font color=#009900>'.MSG_VISITS_FROMTHIS_NOT_RECORDED.'</font><br><br> ';
	
				if ($_COOKIE["AllMyStatsVisites"] == 'No record this' && $IP_PC_Exclue) {
					echo MSG_COOKIE_AND_IP_INSTALLED;
					?>
					<form name="formDeleteCookie" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
							<input type="hidden" name="type" value="MyVisitsTools">
							<input type="hidden" name="ModifCookie" value="delete">
							<input class="submit" name="DeleteCookie" type="submit" value="<?php echo MSG_DELETE_COOKIE; ?>" alt="<?php echo MSG_DELETE_COOKIE; ?>" title="<?php echo MSG_DELETE_COOKIE; ?>">
					</form><br><br><br>			
					<?php
	
				} elseif ($_COOKIE["AllMyStatsVisites"] == 'No record this') {
					echo MSG_COOKIE_INSTALLED.'<br>';?>
					<br><br><strong>
					<?php echo MSG_IF_YOU_WANT_RECORD_THIS; ?></strong>
					<form name="formDeleteCookie" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
							<input type="hidden" name="type" value="MyVisitsTools">
							<input type="hidden" name="ModifCookie" value="delete">
							<input class="submit" name="DeleteCookie" type="submit" value="<?php echo MSG_DELETE_COOKIE; ?>" alt="<?php echo MSG_DELETE_COOKIE; ?>" title="<?php echo MSG_DELETE_COOKIE; ?>">
					</form><br><br><br>
					<?php
				} elseif ($IP_PC_Exclue) {
					echo MSG_IP_ADRESS_IS_DEFINED_DETAILS; ?>
					<form name="form1" method="post" action="<?php echo FILENAME_INDEX_FRAME; ?>">
						<input type="hidden" name="type" value="MyVisitsTools">
						<input type="hidden" name="ModifCookie" value="install">
						<input class="submit" name="SetCookie" type="submit" value="<?php echo MSG_INSTALL_COOKIE; ?>" alt="<?php echo MSG_INSTALL_COOKIE; ?>" title="<?php echo MSG_INSTALL_COOKIE; ?>">
					</form><br><br><br>
					<?php
				}
		} 

		echo MSG_IP_WHOSE_NOT_RECORDED ;
	
		if ($IpExlues[0]) {
			for($i=0;$i<count($IpExlues);$i++){
				if(	$IpExlues[$i] == $IP_PC_Exclue) {
					echo '&nbsp;&nbsp;<font color=#FF0000>'.$IpExlues[$i].'</font> --> '.MSG_THIS_PC.'<br>';
				} else {
					echo '&nbsp;&nbsp;'.$IpExlues[$i].'<br>';
				}
			}
		} else {
			echo MSG_NO_IP_DEFINITED;
		}
?>
				<br>
				<br>
<!--
				</td>
			  </tr>
			</table>

	  </table></td>
	  
</tr></table></td></tr></table><br />
-->

						</td>
					   </tr>
					</table>
				   </td>							  
				 </tr>
				</table>
			  </td>
			</tr>
		</table>
