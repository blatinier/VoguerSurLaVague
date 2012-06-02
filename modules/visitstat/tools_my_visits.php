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
*/
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
?>

<TABLE CELLPADDING=1 CELLSPACING=0 class=TABLEBORDER>
  <TBODY>
  <TR>
    <TD><!-- Data BEGIN -->
      <TABLE CELLPADDING=5 CELLSPACING=0 class=TABLEFRAME><!-- header -->
        <TBODY>
        <TR>
          <TH class=TABLETITLE><? echo $MSG_TITLE_ADMIN_VISITS; ?></TH>
        </TR>
        <TR>
          <TD colSpan=2><!-- Rows BEGIN -->
            <TABLE border=1 CELLPADDING=5 CELLSPACING=0 class=TABLEDATA>
              <TBODY>
	<TR>

<?
		if ($ModifCookie == 'install') {
	  		echo '<TD class=TABLEDATA><center>';
			echo $MSG_COOKIE_INSTALLED;  ?>
			<form name="VerifCookie" method="post" action="index_frame.php">
			        <input type="hidden" name="type" value="MyVisitsTools">
			        <input type="hidden" name="ModifCookie" value="">
			        <input class="submit" name="submitVerif" type="submit" value="<? echo $MSG_VERIFIE; ?>" alt="<? echo $MSG_VERIFIE; ?>" title="<? echo $MSG_VERIFIE; ?>">
	  		</form></center><?	
			echo '</TBODY></TABLE><!-- Rows END --></TD>	  
	  			  </TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>';
			exit; 

		} elseif ($ModifCookie == 'delete') {
	  		echo '<TD class=TABLEDATA><center>';
			echo "Le cookie est supprimé.";  ?>
			<form name="VerifCookie" method="post" action="index_frame.php">
			        <input type="hidden" name="type" value="MyVisitsTools">
			        <input type="hidden" name="ModifCookie" value="">
			        <input class="submit" name="submitVerif" type="submit" value="<? echo $MSG_VERIFIE; ?>" alt="<? echo $MSG_VERIFIE; ?>" title="<? echo $MSG_VERIFIE; ?>">
	  		</form></center><?	
			echo '</TBODY></TABLE><!-- Rows END --></TD>	  
	  			  </TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>';
			exit; 
		}

		if (!$HTTP_COOKIE_VARS["AllMyStatsVisites"] == 'No record this' && $IP_PC_Exclue =='') {
	  		echo '<TD class=TABLEDATA>
			<table width="85%"  border="0" align="center" cellpadding="0" cellspacing="0">
			  <tr>
				<td>';
					echo "<font color =FF0000><strong>".$MSG_VISITS_FROMTHIS_RECORDED."</strong></font>
					<br><br>".$MSG_VISITS_FROMTHIS_RECORDED_DETAILS; ?>
					</b><br><br>
			  
					<strong><?php echo $MSG_IF_BROWSER_ACCEPT_COOKIES; ?></strong>
					<form name="form1" method="post" action="index_frame.php">
							<input type="hidden" name="type" value="MyVisitsTools">
							<input type="hidden" name="ModifCookie" value="install">
							<input class="submit" name="SetCookie" type="submit" value="<? echo $MSG_INSTALL_COOKIE; ?>" alt="<? echo $MSG_INSTALL_COOKIE; ?>" title="<? echo $MSG_INSTALL_COOKIE; ?>">
					</form><br><br><br>
<?php   } else {
	  		echo '<TD class=TABLEDATA>';
		    ?>
			<table width="85%"  border="0" align="center" cellpadding="0" cellspacing="0">
			  <tr>
				<td>
<?
				echo '<font color =009900>'.$MSG_VISITS_FROMTHIS_NOT_RECORDED.'</font><br><br> ';
	
				if ($HTTP_COOKIE_VARS["AllMyStatsVisites"] == 'No record this' && $IP_PC_Exclue) {
					echo $MSG_COOKIE_AND_IP_INSTALLED;
					?>
					<form name="formDeleteCookie" method="post" action="index_frame.php">
							<input type="hidden" name="type" value="MyVisitsTools">
							<input type="hidden" name="ModifCookie" value="delete">
							<input class="submit" name="DeleteCookie" type="submit" value="<? echo $MSG_DELETE_COOKIE; ?>" alt="<? echo $MSG_DELETE_COOKIE; ?>" title="<? echo $MSG_DELETE_COOKIE; ?>">
					</form><br><br><br>			
					<?php
	
				} elseif ($HTTP_COOKIE_VARS["AllMyStatsVisites"] == 'No record this') {
					echo $MSG_COOKIE_INSTALLED.'<br>';?>
					<br><br><strong>
					<?php echo $MSG_IF_YOU_WANT_RECORD_THIS; ?></strong>
					<form name="formDeleteCookie" method="post" action="index_frame.php">
							<input type="hidden" name="type" value="MyVisitsTools">
							<input type="hidden" name="ModifCookie" value="delete">
							<input class="submit" name="DeleteCookie" type="submit" value="<? echo $MSG_DELETE_COOKIE; ?>" alt="<? echo $MSG_DELETE_COOKIE; ?>" title="<? echo $MSG_DELETE_COOKIE; ?>">
					</form><br><br><br>
					<?
				} elseif ($IP_PC_Exclue) {
					echo $MSG_IP_ADRESS_IS_DEFINED_DETAILS; ?>
					<form name="form1" method="post" action="index_frame.php">
						<input type="hidden" name="type" value="MyVisitsTools">
						<input type="hidden" name="ModifCookie" value="install">
						<input class="submit" name="SetCookie" type="submit" value="<? echo $MSG_INSTALL_COOKIE; ?>" alt="<? echo $MSG_INSTALL_COOKIE; ?>" title="<? echo $MSG_INSTALL_COOKIE; ?>">
					</form><br><br><br>
					<?
				}
		} 
?>
		<?php echo $MSG_IP_WHOSE_NOT_RECORDED ; ?>
		<?php		
		if ($IpExlues[0]) {
			for($i=0;$i<count($IpExlues);$i++){
				if(	$IpExlues[$i] == $IP_PC_Exclue) {
					echo '&nbsp;&nbsp;<font color=#FF0000>'.$IpExlues[$i].'</font> --> '.$MSG_THIS_PC.'<br>';
				} else {
					echo '&nbsp;&nbsp;'.$IpExlues[$i].'<br>';
				}
			}
		} else {
			echo $MSG_NO_IP_DEFINITED;
		}
?>
				<br>
				<br>
				</td>
			  </tr>
			</table>

	  </TBODY></TABLE><!-- Rows END --></TD>
	  
	  </TR><!-- no footer --></TBODY></TABLE><!-- Data END --></TD></TR></TBODY></TABLE><BR>